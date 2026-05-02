@foreach($messages as $message)
    @include('chat.message', ['message' => $message])
@endforeach
