@extends('layouts.app')

@section('content')
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8">
            <div class="flex justify-center mb-6">
                <a href="#" class="flex items-center space-x-2">
                    <span>
                        <img src="{{ asset('assets/img/icons/brands/logo.png') }}" alt="Logo"
                            style="height: 32px; width: auto; max-width: 100%; object-fit: contain;">
                    </span>
                    <span class="text-2xl font-bold tracking-tight text-gray-900">{{ config('app.name', 'Asset Management') }}</span>
                </a>
            </div>

            <h4 class="text-xl font-bold text-gray-800 mb-2 text-center">Two-Step Verification 💬</h4>
            <p class="text-sm text-gray-500 mb-6 text-center">
                We sent a verification code to your email address: <br>
                <strong class="text-gray-800">{{ request('email') ?? session('email') ?? 'your email' }}</strong>. <br>
                Enter the 6-digit code below or click the link in your email.
            </p>

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form class="space-y-4" action="{{ route('verification.otp.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ request('email') ?? session('email') }}" />

                <div>
                    <label for="otp" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">6-Digit Verification Code</label>
                    <input type="text"
                        class="w-full px-4 py-3 border rounded-lg text-center text-xl font-bold tracking-widest text-gray-800 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('otp') border-red-500 @else border-gray-200 @enderror"
                        id="otp" name="otp" maxlength="6" placeholder="______" autofocus required />
                    @error('otp')
                        <p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg text-sm transition duration-150 shadow-sm mt-4">
                    Verify Organization & Continue
                </button>
            </form>

            <div class="text-center mt-6 text-sm text-gray-600">
                Didn't get the code? 
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline font-medium">Return to login</a>
            </div>
        </div>
    </div>
@endsection
