@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="auth-wrapper">

    <div class="auth-card">

        <h1>Register</h1>

        <form method="POST" action="/register">

            @csrf

            <div class="form-group">
                <label>Nama</label>

                <input type="text"
                       name="name"
                       required>
            </div>

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
                Register
            </button>

        </form>

        <p class="bottom-text">
            Sudah punya akun?
            <a href="/login">Login</a>
        </p>

    </div>

</div>

@endsection