@extends('layouts.app')

@section('content')
    <home-component
        nome='{{auth()->user()->name}}'></home-component>
@endsection
