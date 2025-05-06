@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Change Password</h3>

    <div class="container py-4">
        <form method="POST" action="{{ route('my-account.change-password.update') }}">
            @csrf
            @method('POST')

            <div class="mb-3">
    <label for="current_password" class="form-label">Current Password</label>
    <div style="position: relative;">
        <input type="password" name="current_password" id="current_password" class="form-control pr-10" required autocomplete="current-password" style="padding-right: 2.5rem;">
        <button type="button" class="btn toggle-password" data-target="current_password" tabindex="-1" style="position: absolute; top: 50%; right: 0.5rem; transform: translateY(-50%); border: none; background: transparent; padding: 0 0.5rem; height: 100%; line-height: 1;">
            <span class="fa fa-eye"></span>
        </button>
    </div>
</div>

            <div class="mb-3">
    <label for="password" class="form-label">New Password</label>
    <div style="position: relative;">
        <input type="password" name="password" id="password" class="form-control pr-10" required autocomplete="new-password" style="padding-right: 2.5rem;">
        <button type="button" class="btn toggle-password" data-target="password" tabindex="-1" style="position: absolute; top: 50%; right: 0.5rem; transform: translateY(-50%); border: none; background: transparent; padding: 0 0.5rem; height: 100%; line-height: 1;">
            <span class="fa fa-eye"></span>
        </button>
    </div>
</div>

            <div class="mb-10">
    <label for="password_confirmation" class="form-label">Confirm New Password</label>
    <div style="position: relative;">
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control pr-10" required autocomplete="new-password" style="padding-right: 2.5rem;">
        <button type="button" class="btn toggle-password" data-target="password_confirmation" tabindex="-1" style="position: absolute; top: 50%; right: 0.5rem; transform: translateY(-50%); border: none; background: transparent; padding: 0 0.5rem; height: 100%; line-height: 1;">
            <span class="fa fa-eye"></span>
        </button>
    </div>
</div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">Change Password</button>
            </div>
        </form>
    </div>
<script>
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === 'password') {
                input.type = 'text';
                btn.querySelector('span').classList.remove('fa-eye');
                btn.querySelector('span').classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                btn.querySelector('span').classList.remove('fa-eye-slash');
                btn.querySelector('span').classList.add('fa-eye');
            }
        });
    });
</script>
@endsection

