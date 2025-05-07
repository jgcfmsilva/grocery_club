@extends('layouts.pages.my-account.layout')

@section('account-content')
    <h3 class="text-3xl font-bold text-gray-800 mb-4">Membership</h3>

    <div class="max-w-3xl mx-auto px-4 py-12">
        @if (authUser()->isMember())
            <div class="border-5 border-gray-300 rounded-xl p-8 text-center shadow-lg">
                <h2 class="text-2xl font-bold mb-4 text-dark">🎉 You're an active member!</h2>
                <p class="mb-2 font-semibold text-dark">As a member, you now have access to the following benefits:</p>
                <ul class="text-left list-disc list-inside space-y-1 text-green-600 font-medium">
                    <li>✅ View your full order and transaction history</li>
                    <li>✅ Place new orders at any time</li>
                    <li>✅ Access exclusive membership statistics and insights</li>
                </ul>
            </div>
        @else
            <div class="border-2 border-gray-300 rounded-xl p-8 shadow-lg text-center">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">⛔ You're not a member yet</h2>
                <p class="text-dark mb-6">
                    To activate your account and enjoy full features, please pay the
                    <span class="font-semibold text-dark">{{ \App\Utils\Constants::MEMBERSHIP_FEE }}€</span> membership fee.
                </p>
                <p class="mb-2 font-semibold text-dark text-left">Without membership, you won't be able to:</p>
                <ul class="text-left list-disc list-inside space-y-1 text-red-500 font-medium mb-10">
                    <li>❌ View your full order and transaction history</li>
                    <li>❌ Place new orders at any time</li>
                    <li>❌ Access exclusive membership statistics and insights</li>
                </ul>

                <form method="POST" action="{{ route('my-account.membership.pay') }}">
                    @csrf
                    <button type="submit"
                        class="w-full bg-primary hover:!bg-orange-400 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-200">
                        Pay and Activate Account
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
