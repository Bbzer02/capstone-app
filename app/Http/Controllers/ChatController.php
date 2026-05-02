<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Show chat interface for applicants
    public function show($applicantId, $jobId)
    {
        $applicant = Applicant::findOrFail($applicantId);
        $job = Job::findOrFail($jobId);
        
        // Check if the applicant belongs to the authenticated user
        if ($applicant->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        $messages = Message::where('applicant_id', $applicantId)
                          ->where('job_id', $jobId)
                          ->with(['admin', 'applicant'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark messages as read
        Message::where('applicant_id', $applicantId)
               ->where('job_id', $jobId)
               ->where('sender_type', 'admin')
               ->where('is_read', false)
               ->update(['is_read' => true, 'read_at' => now()]);

        return view('chat.show', compact('applicant', 'job', 'messages'));
    }

    // Send message from applicant
    public function sendMessage(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'job_id' => 'required|exists:jobs,id',
            'message' => 'required|string|max:1000'
        ]);

        $applicant = Applicant::findOrFail($request->applicant_id);
        
        // Check if the applicant belongs to the authenticated user
        if ($applicant->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check for duplicate messages (same message sent within last 5 seconds)
        $recentMessage = Message::where('applicant_id', $request->applicant_id)
            ->where('job_id', $request->job_id)
            ->where('sender_type', 'applicant')
            ->where('message', $request->message)
            ->where('created_at', '>', now()->subSeconds(5))
            ->first();

        if ($recentMessage) {
            return response()->json(['error' => 'Duplicate message'], 400);
        }

        $message = Message::create([
            'applicant_id' => $request->applicant_id,
            'job_id' => $request->job_id,
            'sender_type' => 'applicant',
            'message' => $request->message,
        ]);

        $message->load('applicant');

        return response()->json([
            'success' => true,
            'message' => $message,
            'html' => view('chat.message', ['message' => $message])->render()
        ]);
    }

    // Get new messages (for real-time updates)
    public function getNewMessages(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'job_id' => 'required|exists:jobs,id',
            'last_message_id' => 'nullable|integer'
        ]);

        $applicant = Applicant::findOrFail($request->applicant_id);
        
        // Check if the applicant belongs to the authenticated user
        if ($applicant->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = Message::where('applicant_id', $request->applicant_id)
                       ->where('job_id', $request->job_id)
                       ->with(['admin', 'applicant']);

        if ($request->last_message_id) {
            $query->where('id', '>', $request->last_message_id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Mark new admin messages as read
        $newAdminMessages = $messages->where('sender_type', 'admin')->where('is_read', false);
        foreach ($newAdminMessages as $message) {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'html' => view('chat.messages', ['messages' => $messages])->render()
        ]);
    }
}