@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <h1>Login</h1>

        @if ($errors->any())
            <div class="error-box">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login">

            @csrf

            <div class="form-group">
                <label>Email</label>

                <input type="email"
                       name="email"
                       required>
            </div>

            <div class="form-group">
                <label>Password</label>

                <input type="password"
                       name="password"
                       required>
            </div>

            <button class="btn">
                Login
            </button>

        </form>

        <p class="bottom-text">
            Belum punya akun?
            <a href="/register">Register</a>
        </p>

    </div>

</div>

@endsection