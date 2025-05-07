@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Change Password</h3>

    <div class="container py-4">
        <form method="POST" action="{{ route('my-account.change-password.update') }}">
            @csrf
            @method('POST')

            <div class="mb-3">
                <label for="current_password" class="form-label">Current Password</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required autocomplete="current-password">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" id="password" class="form-control" required autocomplete="new-password">
            </div>

            <div class="mb-10">
                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required autocomplete="new-password">
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
        </form>
    </div>
@endsection

