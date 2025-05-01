<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-amber-50 to-amber-100">
        <div class="max-w-md w-full bg-white shadow-lg rounded-xl border border-amber-100 overflow-hidden">
            <!-- Header Section -->
            <div class="px-6 py-8 bg-amber-50 border-b border-amber-100">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-amber-800 mb-2">
                        Tunza Challenge
                    </h1>
                    <h2 class="text-xl font-semibold text-amber-700">
                        Welcome Back
                    </h2>
                    <p class="text-amber-600 mt-2">
                        Please sign in to your account
                    </p>
                </div>
            </div>

            <!-- Form Section -->
            <div class="px-6 py-8">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-amber-700">
                            Email Address
                        </label>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               required 
                               autofocus 
                               class="block w-full rounded-lg border-amber-300 shadow-sm 
                                      focus:border-amber-500 focus:ring focus:ring-amber-200 
                                      focus:ring-opacity-50"
                               value="{{ old('email') }}" />
                        <x-input-error :messages="$errors->get('email')" class="mt-1" />
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-amber-700">
                            Password
                        </label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required
                               class="block w-full rounded-lg border-amber-300 shadow-sm 
                                      focus:border-amber-500 focus:ring focus:ring-amber-200 
                                      focus:ring-opacity-50" />
                        <x-input-error :messages="$errors->get('password')" class="mt-1" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" 
                                   type="checkbox" 
                                   name="remember" 
                                   class="rounded border-amber-300 text-amber-600 shadow-sm 
                                          focus:border-amber-500 focus:ring focus:ring-amber-200 
                                          focus:ring-opacity-50">
                            <span class="ml-2 text-sm text-amber-700">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" 
                               class="text-sm text-amber-600 hover:text-amber-800 
                                      transition-colors duration-200">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full py-3 px-4 border border-transparent rounded-lg 
                                   text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 
                                   focus:outline-none focus:ring-2 focus:ring-offset-2 
                                   focus:ring-amber-500 transition-all duration-200 
                                   shadow-sm hover:shadow-md">
                        Sign in
                    </button>

                    <!-- Register Link -->
                    @if (Route::has('register'))
                        <div class="text-center pt-4 border-t border-amber-100">
                            <span class="text-amber-700">Don't have an account?</span>
                            <a href="{{ route('register') }}" 
                               class="ml-1 text-amber-600 hover:text-amber-800 
                                      transition-colors duration-200 font-medium">
                                Register now
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
