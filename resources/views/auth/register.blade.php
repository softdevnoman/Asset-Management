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
            <h4 class="text-xl font-bold text-gray-800 mb-1 text-center">Register Your Organization 🚀</h4>
            <p class="text-sm text-gray-500 mb-6 text-center">Get started with your enterprise workspace today!</p>

            <form id="formAuthentication" class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg mb-2">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-blue-800 mb-3">Organization Details</h5>
                    <div class="space-y-3">
                        <div>
                            <label for="company_name"
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Organization / Company Name</label>
                            <input type="text"
                                class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name') border-red-500 @else border-gray-200 @enderror bg-white"
                                id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Acme Corporation" autofocus required />
                            @error('company_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="company_email"
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Organization Email</label>
                            <input type="email"
                                class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_email') border-red-500 @else border-gray-200 @enderror bg-white"
                                id="company_email" name="company_email" value="{{ old('company_email') }}" placeholder="e.g. contact@acme.com" required />
                            @error('company_email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 border border-gray-100 rounded-lg mb-2">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-gray-700 mb-3">Administrator Account</h5>
                    <div class="space-y-3">
                        <div>
                            <label for="name"
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Admin Full Name</label>
                            <input type="text"
                                class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @else border-gray-200 @enderror bg-white"
                                id="name" name="name" value="{{ old('name') }}" placeholder="Enter admin name" required />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email"
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1">Admin Email</label>
                            <input type="email"
                                class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @else border-gray-200 @enderror bg-white"
                                id="email" name="email" value="{{ old('email') }}" placeholder="Enter admin email" required />
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1"
                        for="password">Password</label>
                    <input type="password" id="password"
                        class="w-full px-3 py-2 border rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @else border-gray-200 @enderror"
                        name="password" placeholder="············" />
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-600 mb-1"
                        for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation"
                        class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                        name="password_confirmation" placeholder="············" required />
                </div>
            </div>
        </div>

                <div class="flex items-center pl-1">
                    <input class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" type="checkbox"
                        id="terms-conditions" name="terms" required />
                    <label class="ml-2 text-xs text-gray-600" for="terms-conditions">
                        I agree to <a href="javascript:void(0);" class="text-blue-500 hover:underline">privacy policy &
                            terms</a>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg text-sm transition duration-150 shadow-sm mt-2">
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
