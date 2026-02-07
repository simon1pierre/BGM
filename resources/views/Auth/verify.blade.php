@extends('layouts.audiences.app')
@section('contents')
<main class="min-h-[70vh] flex items-center justify-center bg-slate-50 py-12">
    <div class="w-full max-w-md bg-white shadow-md rounded-xl p-8">
        <h2 class="text-2xl font-serif text-blue-900 mb-2">Verify Your Email</h2>
        <p class="text-slate-600 mb-6">Enter the verification code sent to your email.</p>

        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('verify.check') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700">Email</label>
                <input type="email" readonly name="email" value="{{ old('email', $email) }}" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2">
                @error('email')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Verification Code</label>
                <input type="text" name="code" class="mt-1 w-full rounded-lg border border-slate-300 px-4 py-2" required>
                @error('code')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
            </div>
            <button class="w-full bg-blue-900 text-white py-2 rounded-lg">Verify</button>
        </form>

        <form method="POST" action="{{ route('verify.resend') }}" class="mt-4">
            @csrf
            <input type="hidden" name="email" value="{{ old('email', $email) }}">
            <button class="w-full bg-slate-100 text-slate-700 py-2 rounded-lg">Resend Code</button>
        </form>
    </div>
</main>
@endsection
