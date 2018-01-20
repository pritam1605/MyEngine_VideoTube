@extends('templates.default')

@section('content')
    <reset-password email="{{ $email }}" token="{{ $token }}"></reset-password>
@endsection