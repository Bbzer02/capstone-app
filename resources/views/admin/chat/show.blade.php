@extends('layouts.admin')

@section('title', 'Chat with ' . $applicant->user->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl shadow-md border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <span class="text-white font-semibold text-lg">
                                {{ substr($applicant->user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $applicant->user->name }}</h1>
                            <p class="text-gray-600">{{ $applicant->user->email }}</p>
                            <p class="text-sm text-blue-600 font-medium">{{ $job->title }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.applicants.show', $applicant->id) }}" 
                           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            View Application
                        </a>
                        <a href="{{ route('admin.chat.index') }}" 
                           class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            Back to Conversations
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col overflow-hidden" style="height: 550px;">
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 bg-gray-50">
                @foreach($messages as $message)
                    @include('admin.chat.message', ['message' => $message])
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
    let lastSentMessage = null;
    let lastSentTime = null;

    function autoResizeTextarea() {
        messageInput.style.height = 'auto';
        messageInput.style.height = Math.min(messageInput.scrollHeight, 128) + 'px';
    }
    
    messageInput.addEventListener('input', autoResizeTextarea);
    
    messageInput.addEventListener('input', function() {
        sendButton.disabled = !this.value.trim() || isSending;
    });

    function scrollToBottom() {
        setTimeout(() => {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }, 100);
    }

    function sendMessage() {
        const message = messageInput.value.trim();
        
        if (!message || isSending) return;
        const now = Date.now();
        if (lastSentMessage === message && lastSentTime && (now - lastSentTime) < 2000) {
            return;
        }
        
        const formData = new FormData(messageForm);
        formData.set('message', message);
        
        isSending = true;
        lastSentMessage = message;
        lastSentTime = now;
        sendButton.disabled = true;
        messageInput.disabled = true;
        
        fetch('{{ route("admin.chat.send") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messageElement = document.createElement('div');
                messageElement.innerHTML = data.html;
                messagesContainer.appendChild(messageElement);
                messageInput.value = '';
                messageInput.style.height = 'auto';
                scrollToBottom();
                lastMessageId = data.message.id;
                setTimeout(() => {
                    lastSentMessage = null;
                    lastSentTime = null;
                }, 3000);
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.error || 'Failed to send message. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        confirmButton: 'px-6 py-2.5 rounded-lg font-medium bg-red-600 text-white hover:bg-red-700 transition-all duration-200'
                    },
                    buttonsStyling: false
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to send message. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc2626',
                customClass: {
                    confirmButton: 'px-6 py-2.5 rounded-lg font-medium bg-red-600 text-white hover:bg-red-700 transition-all duration-200'
                },
                buttonsStyling: false
            });
        })
        .finally(() => {
            isSending = false;
            messageInput.disabled = false;
            sendButton.disabled = !messageInput.value.trim();
            messageInput.focus();
        });
    }

    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    setInterval(function() {
        if (!isOnline || isSending) return;
        const checkFormData = new FormData();
        checkFormData.append('applicant_id', document.querySelector('input[name="applicant_id"]').value);
        checkFormData.append('job_id', document.querySelector('input[name="job_id"]').value);
        checkFormData.append('last_message_id', lastMessageId);
        
        fetch('{{ route("admin.chat.new-messages") }}', {
            method: 'POST',
            body: checkFormData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.messages && data.messages.length > 0) {
                const newMessages = data.messages.filter(msg => {
                    if (lastSentMessage && msg.message === lastSentMessage && msg.sender_type === 'admin') {
                        return false;
                    }
                    return msg.id > lastMessageId;
                });
                
                if (newMessages.length > 0) {
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data.html;
                    Array.from(tempDiv.children).forEach((messageElement, index) => {
                        if (index < newMessages.length) {
                            messagesContainer.appendChild(messageElement.cloneNode(true));
                        }
                    });
                    scrollToBottom();
                    lastMessageId = newMessages[newMessages.length - 1].id;
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
    sendButton.disabled = !messageInput.value.trim();
});
</script>
@endsection
