@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h4 class="mb-4">Login Pengguna</h4>
            <form method="POST" action="{{ route('user.login') }}">
                @csrf

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
