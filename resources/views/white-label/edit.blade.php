@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Super Administration</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">White Label Settings</p>
            <p class="text-blue-200 text-[11px]">Customize logos, images, and all major site text content.</p>
        </div>
    </div>

    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
        <form method="POST" action="{{ route('white-label.update') }}" enctype="multipart/form-data"
              class="mx-auto max-w-5xl space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Text Branding</h2>
                </div>
                <div class="grid grid-cols-1 gap-5 px-6 py-6 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Application Name</label>
                        <input name="app_name" type="text" value="{{ old('app_name', $branding->app_name) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Meta Title</label>
                        <input name="meta_title" type="text" value="{{ old('meta_title', $branding->meta_title) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="mt-1 block w-full rounded border-gray-300 text-sm">{{ old('meta_description', $branding->meta_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Navigation Title</label>
                        <input name="nav_title" type="text" value="{{ old('nav_title', $branding->nav_title) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Navigation Subtitle</label>
                        <input name="nav_subtitle" type="text" value="{{ old('nav_subtitle', $branding->nav_subtitle) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Badge</label>
                        <input name="welcome_badge" type="text" value="{{ old('welcome_badge', $branding->welcome_badge) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Title</label>
                        <input name="welcome_title" type="text" value="{{ old('welcome_title', $branding->welcome_title) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Subtitle</label>
                        <input name="welcome_subtitle" type="text" value="{{ old('welcome_subtitle', $branding->welcome_subtitle) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Description</label>
                        <textarea name="welcome_description" rows="3" class="mt-1 block w-full rounded border-gray-300 text-sm">{{ old('welcome_description', $branding->welcome_description) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Login Heading</label>
                        <input name="login_heading" type="text" value="{{ old('login_heading', $branding->login_heading) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Login Subheading</label>
                        <input name="login_subheading" type="text" value="{{ old('login_subheading', $branding->login_subheading) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Footer Text</label>
                        <input name="footer_text" type="text" value="{{ old('footer_text', $branding->footer_text) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Footer Subtext</label>
                        <input name="footer_subtext" type="text" value="{{ old('footer_subtext', $branding->footer_subtext) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Image Branding</h2>
                </div>
                <div class="grid grid-cols-1 gap-5 px-6 py-6 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Logo</label>
                        <input name="logo" type="file" accept=".png,.jpg,.jpeg,.webp,.svg" class="mt-1 block w-full text-sm">
                        <img src="{{ $whiteLabel['logo_url'] }}" alt="Logo preview" class="mt-2 h-14 w-14 rounded border border-gray-200 object-contain">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Favicon</label>
                        <input name="favicon" type="file" accept=".png,.ico" class="mt-1 block w-full text-sm">
                        <img src="{{ $whiteLabel['favicon_url'] }}" alt="Favicon preview" class="mt-2 h-10 w-10 rounded border border-gray-200 object-contain">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Background</label>
                        <input name="welcome_bg" type="file" accept=".png,.jpg,.jpeg,.webp" class="mt-1 block w-full text-sm">
                        <img src="{{ $whiteLabel['welcome_bg_url'] }}" alt="Welcome preview" class="mt-2 h-24 w-full rounded border border-gray-200 object-cover">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Login Background</label>
                        <input name="login_bg" type="file" accept=".png,.jpg,.jpeg,.webp" class="mt-1 block w-full text-sm">
                        <img src="{{ $whiteLabel['login_bg_url'] }}" alt="Login preview" class="mt-2 h-24 w-full rounded border border-gray-200 object-cover">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Open Graph Image</label>
                        <input name="og_image" type="file" accept=".png,.jpg,.jpeg,.webp" class="mt-1 block w-full text-sm">
                        <img src="{{ $whiteLabel['og_image_url'] }}" alt="OG image preview" class="mt-2 h-24 w-full rounded border border-gray-200 object-cover">
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-5xl">
                <button type="submit" class="rounded bg-[#1a2c5b] px-5 py-2 text-xs font-semibold uppercase tracking-wider text-white hover:bg-[#16306d]">
                    Save White Label Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
