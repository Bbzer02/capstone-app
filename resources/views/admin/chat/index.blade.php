@extends('layouts.admin')

@section('title', 'Chat Conversations')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Chat Conversations</h1>
            <p class="mt-2 text-gray-600">Communicate with job applicants</p>
        </div>

        @if($conversations->count() > 0)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="divide-y divide-gray-100">
                @foreach($conversations as $conversation)
                    @php
                        $unread = $conversation['unread_count'] > 0;
                    @endphp
                    <div class="px-6 py-4 hover:bg-gray-50 cursor-pointer transition-colors duration-150 flex items-center justify-between {{ $unread ? 'bg-blue-50/40' : '' }}"
                         onclick="window.location='{{ route('admin.chat.show', [$conversation['applicant']->id, $conversation['job']->id]) }}'">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-11 h-11 bg-blue-500 rounded-full flex items-center justify-center">
                                                <span class="text-white font-semibold text-base">
                                                    {{ substr($conversation['applicant']->user->name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-semibold {{ $unread ? 'text-gray-900' : 'text-gray-800' }}">
                                                {{ $conversation['applicant']->user->name }}
                                            </h3>
                                            <p class="text-xs text-gray-500">
                                                {{ $conversation['applicant']->user->email }}
                                            </p>
                                            <p class="text-xs font-medium text-blue-600 mt-1 truncate">
                                                {{ $conversation['job']->title }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    @if($conversation['latest_message'])
                                        <div class="mt-2">
                                            <p class="text-xs {{ $unread ? 'text-gray-900 font-medium' : 'text-gray-600' }} line-clamp-1">
                                                {{ $conversation['latest_message']->message }}
                                            </p>
                                            <p class="text-[11px] text-gray-400 mt-0.5">
                                                {{ $conversation['latest_message']->time_ago }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex flex-col items-end space-y-2 ml-4">
                                    @if($unread)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-semibold bg-blue-500 text-white shadow-sm">
                                            {{ $conversation['unread_count'] }}
                                        </span>
                                    @endif
                                    <span class="text-[11px] text-gray-400">
                                        {{ $conversation['latest_message']?->created_at?->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                    </div>
                @endforeach
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations yet</h3>
                <p class="mt-1 text-sm text-gray-500">Start chatting with applicants after they apply for jobs.</p>
            </div>
        @endif
    </div>
</div>
@endsection
