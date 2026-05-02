<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $applications = Applicant::where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere('email', $user->email);
            })
            ->with(['job', 'interview'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('applications.index', compact('applications'));
    }

    public function show(Applicant $application)
    {
        // Ensure the application belongs to the authenticated user
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->load(['job', 'interview', 'messages']);
        
        return view('applications.show', compact('application'));
    }

    public function getStatusProgress($status)
    {
        $statuses = [
            'pending' => [
                'step' => 1,
                'title' => 'Application Submitted',
                'description' => 'Your application has been received and is under review.',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'blue'
            ],
            'shortlisted' => [
                'step' => 2,
                'title' => 'Shortlisted',
                'description' => 'Congratulations! You have been shortlisted for this position.',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'yellow'
            ],
            'interview_scheduled' => [
                'step' => 3,
                'title' => 'Interview Scheduled',
                'description' => 'An interview has been scheduled for you.',
                'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'color' => 'purple'
            ],
            'approved' => [
                'step' => 4,
                'title' => 'Approved',
                'description' => 'Your application has been approved! Final review in progress.',
                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'green'
            ],
            'hired' => [
                'step' => 5,
                'title' => 'Hired',
                'description' => 'Congratulations! You have been hired for this position.',
                'icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
                'color' => 'emerald'
            ],
            'rejected' => [
                'step' => 0,
                'title' => 'Not Selected',
                'description' => 'Unfortunately, you were not selected for this position.',
                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'red'
            ]
        ];

        return $statuses[$status] ?? $statuses['pending'];
    }

    public function editDocuments(Applicant $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $application->load(['job']);

        // If admin has not permitted editing and status is not rejected, block editing
        if (!$application->is_editable_by_user && $application->status !== 'rejected') {
            return redirect()->route('applications.show', $application)
                ->with('error', 'Editing is currently not allowed for this application.');
        }

        return view('applications.edit-documents', compact('application'));
    }

    public function updateDocuments(Request $request, Applicant $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        if (!$application->is_editable_by_user && $application->status !== 'rejected') {
            return redirect()->route('applications.show', $application)
                ->with('error', 'Editing is currently not allowed for this application.');
        }

        $validated = $request->validate([
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'cover_letter_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'transcript' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'portfolio' => 'nullable|file|mimes:pdf,zip|max:20480',
            'additional_documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,zip|max:10240',
        ]);

        $basePath = 'applicants/' . $application->id;
        $updates = [];

        if ($request->hasFile('resume')) {
            $path = $request->file('resume')->store($basePath, 'public');
            $updates['resume_path'] = $path;
        }

        if ($request->hasFile('cover_letter_file')) {
            $path = $request->file('cover_letter_file')->store($basePath, 'public');
            $updates['cover_letter_path'] = $path;
        }

        if ($request->hasFile('transcript')) {
            $path = $request->file('transcript')->store($basePath, 'public');
            $updates['transcript_path'] = $path;
        }

        if ($request->hasFile('certificate')) {
            $path = $request->file('certificate')->store($basePath, 'public');
            $updates['certificate_path'] = $path;
        }

        if ($request->hasFile('portfolio')) {
            $path = $request->file('portfolio')->store($basePath, 'public');
            $updates['portfolio_path'] = $path;
        }

        if ($request->hasFile('additional_documents')) {
            $existing = is_array($application->additional_documents) ? $application->additional_documents : [];
            foreach ($request->file('additional_documents') as $file) {
                $storedPath = $file->store($basePath, 'public');
                $existing[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $storedPath,
                ];
            }
            $updates['additional_documents'] = $existing;
        }

        if (!empty($updates)) {
            $application->update($updates);
        }

        return redirect()->route('applications.show', $application)->with('success', 'Documents updated successfully.');
    }

    public function deleteDocument(Request $request, Applicant $application, string $type)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $typeToColumn = [
            'resume' => 'resume_path',
            'cover_letter' => 'cover_letter_path',
            'transcript' => 'transcript_path',
            'certificate' => 'certificate_path',
            'portfolio' => 'portfolio_path',
        ];

        if (!isset($typeToColumn[$type])) {
            abort(404);
        }

        $column = $typeToColumn[$type];
        $path = $application->$column;
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
        $application->update([$column => null]);

        return back()->with('success', ucfirst(str_replace('_', ' ', $type)) . ' deleted.');
    }

    public function deleteAdditionalDocument(Request $request, Applicant $application, int $index)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        $documents = is_array($application->additional_documents) ? $application->additional_documents : [];

        if (!isset($documents[$index])) {
            abort(404);
        }

        $doc = $documents[$index];
        if (!empty($doc['path']) && Storage::disk('public')->exists($doc['path'])) {
            Storage::disk('public')->delete($doc['path']);
        }

        unset($documents[$index]);
        $documents = array_values($documents);
        $application->update(['additional_documents' => $documents]);

        return back()->with('success', 'Additional document removed.');
    }

    public function destroy(Request $request, Applicant $application)
    {
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this application.');
        }

        // Delete associated interview if present
        if ($application->interview) {
            $application->interview->delete();
        }

        // Delete associated messages
        if ($application->messages()->exists()) {
            $application->messages()->delete();
        }

        // Delete stored files
        $fileColumns = [
            'resume_path',
            'cover_letter_path',
            'transcript_path',
            'certificate_path',
            'portfolio_path',
        ];

        foreach ($fileColumns as $col) {
            $path = $application->$col;
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $additional = is_array($application->additional_documents) ? $application->additional_documents : [];
        foreach ($additional as $doc) {
            if (!empty($doc['path']) && Storage::disk('public')->exists($doc['path'])) {
                Storage::disk('public')->delete($doc['path']);
            }
        }

        // Finally delete application
        $application->delete();

        return redirect()->route('applications.index')->with('success', 'Application deleted successfully.');
    }
}