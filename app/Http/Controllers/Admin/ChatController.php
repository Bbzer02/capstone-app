<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Applicant;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // Show all conversations for admin
    public function index()
    {
        $conversations = Message::select('applicant_id', 'job_id')
            ->with(['applicant.user', 'job'])
            ->groupBy('applicant_id', 'job_id')
            ->get()
            ->map(function ($message) {
                $latestMessage = Message::where('applicant_id', $message->applicant_id)
                    ->where('job_id', $message->job_id)
                    ->orderBy('created_at', 'desc')
                    ->first();
                
                $unreadCount = Message::where('applicant_id', $message->applicant_id)
                    ->where('job_id', $message->job_id)
                    ->where('sender_type', 'applicant')
                    ->where('is_read', false)
                    ->count();

                return [
                    'applicant' => $message->applicant,
                    'job' => $message->job,
                    'latest_message' => $latestMessage,
                    'unread_count' => $unreadCount
                ];
            })
            ->sortByDesc('latest_message.created_at');

        return view('admin.chat.index', compact('conversations'));
    }

    // Show specific conversation
    public function show($applicantId, $jobId)
    {
        $applicant = Applicant::with('user')->findOrFail($applicantId);
        $job = Job::findOrFail($jobId);
        
        $messages = Message::where('applicant_id', $applicantId)
                          ->where('job_id', $jobId)
                          ->with(['admin', 'applicant.user'])
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Mark messages as read
        Message::where('applicant_id', $applicantId)
               ->where('job_id', $jobId)
               ->where('sender_type', 'applicant')
               ->where('is_read', false)
               ->update(['is_read' => true, 'read_at' => now()]);

        return view('admin.chat.show', compact('applicant', 'job', 'messages'));
    }

    // Send message from admin
    public function sendMessage(Request $request)
    {
        $request->validate([
            'applicant_id' => 'required|exists:applicants,id',
            'job_id' => 'required|exists:jobs,id',
            'message' => 'required|string|max:1000'
        ]);

        // Check for duplicate messages (same message sent within last 5 seconds)
        $recentMessage = Message::where('applicant_id', $request->applicant_id)
            ->where('job_id', $request->job_id)
            ->where('sender_type', 'admin')
            ->where('admin_id', Auth::guard('admin')->id())
            ->where('message', $request->message)
            ->where('created_at', '>', now()->subSeconds(5))
            ->first();

        if ($recentMessage) {
            return response()->json(['error' => 'Duplicate message'], 400);
        }

        $message = Message::create([
            'applicant_id' => $request->applicant_id,
            'job_id' => $request->job_id,
            'sender_type' => 'admin',
            'admin_id' => Auth::guard('admin')->id(),
            'message' => $request->message,
        ]);

        $message->load('admin');

        return response()->json([
            'success' => true,
            'message' => $message,
            'html' => view('admin.chat.message', ['message' => $message])->render()
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

        $query = Message::where('applicant_id', $request->applicant_id)
                       ->where('job_id', $request->job_id)
                       ->with(['admin', 'applicant.user']);

        if ($request->last_message_id) {
            $query->where('id', '>', $request->last_message_id);
        }

        $messages = $query->orderBy('created_at', 'asc')->get();

        // Mark new applicant messages as read
        $newApplicantMessages = $messages->where('sender_type', 'applicant')->where('is_read', false);
        foreach ($newApplicantMessages as $message) {
            $message->markAsRead();
        }

        return response()->json([
            'success' => true,
            'messages' => $messages,
            'html' => view('admin.chat.messages', ['messages' => $messages])->render()
        ]);
    }
}