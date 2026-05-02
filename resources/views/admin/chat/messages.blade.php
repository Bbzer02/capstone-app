@foreach($messages as $message)
    @include('admin.chat.message', ['message' => $message])
@endforeach
