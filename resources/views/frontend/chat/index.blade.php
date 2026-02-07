@extends('frontend.layouts.app')

@section('content')
    <div id="chat-box">
        @foreach($messages as $message)
            <div>{{ $message->message }}</div>
        @endforeach
    </div>

    <form id="chat-form">
        @csrf
        <input type="hidden" id="receiver_id" value="{{ $receiverId }}">
        <input type="text" id="message">
        <button type="submit">Send</button>
    </form>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').content;


        // Send message
        document.getElementById('chat-form').addEventListener('submit', function (e) {
            e.preventDefault();

            let msg = document.getElementById('message').value;
            let receiverId = document.getElementById('receiver_id').value;

            axios.post("{{ route('frontend.message.send') }}", {
                message: msg,
                receiver_id: receiverId
            });

            document.getElementById('chat-box').innerHTML += `<div>You: ${msg}</div>`;
            document.getElementById('message').value = '';
        });
        console.log("this is frontend echo",window.Echo);
        // Receive message
        window.Echo.private('chat.{{ auth()->id() }}')
            .listen('.message.sent', (e) => {
                document.getElementById('chat-box').innerHTML +=
                    `<div>Them: ${e.message.message}</div>`;
            });
    </script>
@endsection