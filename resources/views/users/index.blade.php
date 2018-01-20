@extends('templates.default')

@section('content')
    <user-dashboard :user="{{ $user }}"></user-dashboard>
@endsection