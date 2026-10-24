@extends('layouts.app')
@section('title', 'Admin Dashboard')
@section('content')
    <h1>Welcome, {{ auth()->user()->first_name }}</h1>
@endsection