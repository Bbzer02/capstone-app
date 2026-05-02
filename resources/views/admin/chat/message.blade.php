<div class="flex {{ $message->isFromAdmin() ? 'justify-end' : 'justify-start' }} mb-1 px-4">
    <div class="max-w-full text-xs">
        <p class="mb-0.5 font-semibold {{ $message->isFromAdmin() ? 'text-blue-600 text-right' : 'text-emerald-600 text-left' }}">
            {{ $message->isFromAdmin() ? 'You (Admin)' : ($message->applicant->user->name ?? 'Applicant') }}
        </p>
        <p class="text-sm text-gray-900 whitespace-pre-wrap break-words {{ $message->isFromAdmin() ? 'text-right' : 'text-left' }}">
            {{ $message->message }}
        </p>
        <p class="mt-0.5 text-[10px] text-gray-400 {{ $message->isFromAdmin() ? 'text-right' : 'text-left' }}">
            {{ $message->time_ago }}
        </p>
    </div>
</div>
