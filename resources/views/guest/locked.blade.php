@extends('layouts.app')

@section('title', 'Log in required')
@section('page_title', 'Log in required')
@section('page_subtitle', 'This part of the dormitory is for signed-in tenants.')

@push('styles')
    <style>
        .gate {
            max-width: 520px;
            margin: 60px auto;
            text-align: center;
            font-family: 'Nunito Sans', sans-serif;
        }

        .gate-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 28px;
            border-radius: 50%;
            background: #e8dada;
            color: #7a3b3b;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gate-icon svg {
            width: 34px;
            height: 34px;
        }

        .gate-title {
            margin: 0 0 12px;
            font-family: 'Inria Serif', serif;
            font-size: 1.7rem;
            font-weight: 700;
            color: #000;
        }

        .gate-text {
            margin: 0 auto 26px;
            max-width: 440px;
            font-size: .95rem;
            font-weight: 600;
            line-height: 1.5;
            color: #00000099;
        }

        .gate-btn {
            display: block;
            width: 100%;
            max-width: 360px;
            margin: 0 auto 22px;
            padding: 14px 0;
            background: #7a3b3b;
            border-radius: 2px;
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            text-decoration: none;
            transition: background .2s ease;
        }

        .gate-btn:hover {
            background: #672C2C;
        }

        .gate-new {
            font-size: .85rem;
            font-weight: 600;
            color: #000;
        }

        .gate-new a {
            color: #7a3b3b;
            font-weight: 800;
            text-decoration: none;
        }

        .gate-new a:hover {
            text-decoration: underline;
        }
    </style>
@endpush

@section('content')
    <div class="gate">
        <div class="gate-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                stroke-linejoin="round" aria-hidden="true">
                <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
            </svg>
        </div>

        <h2 class="gate-title">Please log in to use this feature</h2>
        <p class="gate-text">Your profile details, tenant records, and account settings are only available to signed-in
            tenants. You're currently browsing as a guest.</p>

        <a href="{{ route('login') }}" class="gate-btn">Login</a>

        <p class="gate-new">New to D'Aces? <a href="{{ route('login', ['view' => 'signup']) }}">Create an account</a></p>
    </div>
@endsection