<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Models\Applicant;

class DocumentController extends Controller
{
    public function downloadResume(Applicant $applicant)
    {
        // Check if user is authorized to download this document
        if (auth()->check() && $applicant->user_id === auth()->id()) {
            // User downloading their own document
            $this->authorize('view', $applicant);
        } elseif (auth()->guard('admin')->check()) {
            // Admin downloading applicant document
            // Allow admin to download any document
        } else {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!$applicant->resume_path || !Storage::disk('public')->exists($applicant->resume_path)) {
            abort(404, 'Resume not found.');
        }

        $filePath = Storage::disk('public')->path($applicant->resume_path);
        $fileName = 'Resume_' . $applicant->name . '_' . $applicant->job->title . '.pdf';

        return Response::download($filePath, $fileName);
    }

    public function downloadCoverLetter(Applicant $applicant)
    {
        // Check if user is authorized to download this document
        if (auth()->check() && $applicant->user_id === auth()->id()) {
            // User downloading their own document
            $this->authorize('view', $applicant);
        } elseif (auth()->guard('admin')->check()) {
            // Admin downloading applicant document
            // Allow admin to download any document
        } else {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!$applicant->cover_letter_path || !Storage::disk('public')->exists($applicant->cover_letter_path)) {
            abort(404, 'Cover letter not found.');
        }

        $filePath = Storage::disk('public')->path($applicant->cover_letter_path);
        $fileName = 'CoverLetter_' . $applicant->name . '_' . $applicant->job->title . '.pdf';

        return Response::download($filePath, $fileName);
    }

    public function downloadTranscript(Applicant $applicant)
    {
        // Check if user is authorized to download this document
        if (auth()->check() && $applicant->user_id === auth()->id()) {
            // User downloading their own document
            $this->authorize('view', $applicant);
        } elseif (auth()->guard('admin')->check()) {
            // Admin downloading applicant document
            // Allow admin to download any document
        } else {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!$applicant->transcript_path || !Storage::disk('public')->exists($applicant->transcript_path)) {
            abort(404, 'Transcript not found.');
        }

        $filePath = Storage::disk('public')->path($applicant->transcript_path);
        $fileName = 'Transcript_' . $applicant->name . '_' . $applicant->job->title . '.pdf';

        return Response::download($filePath, $fileName);
    }

    public function downloadCertificate(Applicant $applicant)
    {
        // Check if user is authorized to download this document
        if (auth()->check() && $applicant->user_id === auth()->id()) {
            // User downloading their own document
            $this->authorize('view', $applicant);
        } elseif (auth()->guard('admin')->check()) {
            // Admin downloading applicant document
            // Allow admin to download any document
        } else {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!$applicant->certificate_path || !Storage::disk('public')->exists($applicant->certificate_path)) {
            abort(404, 'Certificate not found.');
        }

        $filePath = Storage::disk('public')->path($applicant->certificate_path);
        $fileName = 'Certificate_' . $applicant->name . '_' . $applicant->job->title . '.pdf';

        return Response::download($filePath, $fileName);
    }

    public function downloadPortfolio(Applicant $applicant)
    {
        // Check if user is authorized to download this document
        if (auth()->check() && $applicant->user_id === auth()->id()) {
            // User downloading their own document
            $this->authorize('view', $applicant);
        } elseif (auth()->guard('admin')->check()) {
            // Admin downloading applicant document
            // Allow admin to download any document
        } else {
            abort(403, 'Unauthorized access to this document.');
        }

        if (!$applicant->portfolio_path || !Storage::disk('public')->exists($applicant->portfolio_path)) {
            abort(404, 'Portfolio not found.');
        }

        $filePath = Storage::disk('public')->path($applicant->portfolio_path);
        $fileName = 'Portfolio_' . $applicant->name . '_' . $applicant->job->title . '.pdf';

        return Response::download($filePath, $fileName);
    }

    public function downloadAdditionalDocument(Applicant $applicant, $documentIndex)
    {
        // Check if user is authorized to download this document
        if (auth()->check() && $applicant->user_id === auth()->id()) {
            // User downloading their own document
            $this->authorize('view', $applicant);
        } elseif (auth()->guard('admin')->check()) {
            // Admin downloading applicant document
            // Allow admin to download any document
        } else {
            abort(403, 'Unauthorized access to this document.');
        }

        $additionalDocuments = $applicant->additional_documents;
        
        if (!$additionalDocuments || !isset($additionalDocuments[$documentIndex])) {
            abort(404, 'Document not found.');
        }

        $document = $additionalDocuments[$documentIndex];
        $filePath = Storage::disk('public')->path($document['path']);

        if (!Storage::disk('public')->exists($document['path'])) {
            abort(404, 'Document file not found.');
        }

        $fileName = $document['name'] ?? 'AdditionalDocument_' . $applicant->name . '.pdf';

        return Response::download($filePath, $fileName);
    }
}