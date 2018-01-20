@extends('templates.default')

@section('content')
    <channel-dashboard channel-slug="{{ $channel_slug }}"></channel-dashboard>
@endsection