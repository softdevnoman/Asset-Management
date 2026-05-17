@extends('layouts.app')

@section('content')
<div class="w-full max-w-md px-4">
    <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8">

        <div class="flex justify-center mb-6">
            <a href="#" class="flex items-center space-x-2">
                <span class="text-blue-600">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z" fill="currentColor" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z" fill="currentColor" />
                    </svg>
                </span>
                <span class="text-2xl font-bold tracking-tight text-gray-900">Vuexy</span>
            </a>
        </div>
        <h4 class="text-xl font-bold text-gray-800 mb-1 text-center">Adventure starts here 🚀</h4>
        <p class="text-sm text-gray-500 mb-6 text-center">Make your app management easy and fun!</p>

        <form id="formAuthentication" class="space-y-4" action="{{ route('register') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Name</label>
                <input
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @else border-gray-200 @enderror"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your name"
                    autofocus />
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Email</label>
                <input
                    type="text"
                    class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @else border-gray-200 @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email" />
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1" for="password">Password</label>
                <input
                    type="password"
                    id="password"
                    class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @else border-gray-200 @enderror"
                    name="password"
                    placeholder="············" />
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1" for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    name="password_confirmation"
                    placeholder="············" />
            </div>

            <div class="flex items-center pl-1">
                <input class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" type="checkbox" id="terms-conditions" name="terms" required />
                <label class="ml-2 text-xs text-gray-600" for="terms-conditions">
                    I agree to <a href="javascript:void(0);" class="text-blue-500 hover:underline">privacy policy & terms</a>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-sm transition duration-150 shadow-sm mt-2">
                Sign up
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline font-medium">
                <span>Sign in instead</span>
            </a>
        </p>
    </div>
</div>
@endsection
