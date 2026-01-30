@extends('frontend.layouts.app')
@section('content')
@foreach($messages as $message)
<div>{{ $message }}</div>
@endforeach

<form method="POST" action="{{ route('frontend.message.send') }}">

<input type="text" name="message"/>
<button type="submit">Submit</button>
</form>
@endsection