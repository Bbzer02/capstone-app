@extends('layouts.app')

@section('title', 'Chat with HR - ' . $job->title)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Chat with HR</h1>
                        <p class="text-gray-600 mt-1">{{ $job->title }} - {{ $job->department }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('jobs.show', $job->id) }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            View Job Details
                        </a>
                        <a href="{{ route('dashboard') }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col overflow-hidden" style="height: 550px;">
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 bg-gray-50">
                @foreach($messages as $message)
                    @include('chat.message', ['message' => $message])
                @endforeach
                </div>

                <div class="border-t border-gray-100 bg-white p-3">
                <form id="message-form" class="flex items-end space-x-2">
                    @csrf
                    <input type="hidden" name="applicant_id" value="{{ $applicant->id }}">
                    <input type="hidden" name="job_id" value="{{ $job->id }}">
                    
                    <div class="flex-1 flex items-end space-x-2 bg-gray-100 rounded-full px-4 py-2 border border-transparent focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 transition-all">
                        <textarea 
                            name="message" 
                            id="message-input" 
                            rows="1" 
                            class="flex-1 bg-transparent border-none outline-none resize-none text-gray-900 placeholder-gray-500 text-sm max-h-32 overflow-y-auto"
                            placeholder="Type a message..."
                            required
                            style="min-height: 24px;"
                        ></textarea>
                        <button 
                            type="submit" 
                            id="send-button"
                            class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition-colors duration-200 flex items-center justify-center disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <div id="status-indicator" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                Online
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messages-container');
    const messageForm = document.getElementById('message-form');
    const messageInput = document.getElementById('message-input');
    const sendButton = document.getElementById('send-button');
    const statusIndicator = document.getElementById('status-indicator');
    
    let lastMessageId = {{ $messages->last() ? $messages->last()->id : 0 }};
    let isOnline = true;
    let isSending = false;

    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 128) + 'px';
        const hasText = this.value.trim().length > 0;
        sendButton.disabled = !hasText || isSending;
    });

    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!sendButton.disabled && !isSending) {
                messageForm.dispatchEvent(new Event('submit'));
            }
        }
    });

    sendButton.disabled = !messageInput.value.trim().length > 0;

    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const message = messageInput.value.trim();
        
        if (!message || isSending) return;
        
        isSending = true;
        sendButton.disabled = true;
        
        fetch('{{ route("chat.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messageElement = document.createElement('div');
                messageElement.innerHTML = data.html;
                messagesContainer.appendChild(messageElement.firstElementChild || messageElement);
                messageInput.value = '';
                messageInput.style.height = 'auto';
                scrollToBottom();
                lastMessageId = data.message.id;
            } else {
                alert('Failed to send message. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            isSending = false;
            sendButton.disabled = !messageInput.value.trim().length > 0;
        });
    });

    setInterval(function() {
        if (!isOnline) return;
        const checkFormData = new FormData();
        checkFormData.append('applicant_id', document.querySelector('input[name="applicant_id"]').value);
        checkFormData.append('job_id', document.querySelector('input[name="job_id"]').value);
        checkFormData.append('last_message_id', lastMessageId);
        
        fetch('{{ route("chat.new-messages") }}', {
            method: 'POST',
            body: checkFormData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages.length > 0) {
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                while (tempDiv.firstChild) {
                    messagesContainer.appendChild(tempDiv.firstChild);
                }
                scrollToBottom();
                if (data.messages.length > 0) {
                    lastMessageId = data.messages[data.messages.length - 1].id;
                }
            }
        })
        .catch(error => {
            console.error('Error checking for new messages:', error);
        });
    }, 3000);

    window.addEventListener('online', function() {
        isOnline = true;
        statusIndicator.innerHTML = '<div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>Online';
        statusIndicator.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800';
    });

    window.addEventListener('offline', function() {
        isOnline = false;
        statusIndicator.innerHTML = '<div class="w-2 h-2 bg-red-500 rounded-full mr-2"></div>Offline';
        statusIndicator.className = 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800';
    });

    scrollToBottom();
});
</script>
@endsection
