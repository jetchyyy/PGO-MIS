@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100">
    <div class="bg-[#1a2c5b] border-b-4 border-[#c8a84b] shadow-lg">
        <div class="w-full px-4 sm:px-6 lg:px-8 py-4">
            <p class="text-xs font-semibold uppercase tracking-widest text-[#c8a84b]">Super Administration</p>
            <p class="text-white font-bold text-lg leading-tight mt-0.5">White Label Settings</p>
            <p class="text-blue-200 text-[11px]">Customize colors, logos, images, and all major site text content.</p>
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
                        <input name="app_name" type="text" value="{{ old('app_name', $branding->app_name ?? $whiteLabel['app_name']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Meta Title</label>
                        <input name="meta_title" type="text" value="{{ old('meta_title', $branding->meta_title ?? $whiteLabel['meta_title']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Meta Description</label>
                        <textarea name="meta_description" rows="2" class="mt-1 block w-full rounded border-gray-300 text-sm">{{ old('meta_description', $branding->meta_description ?? $whiteLabel['meta_description']) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Navigation Title</label>
                        <input name="nav_title" type="text" value="{{ old('nav_title', $branding->nav_title ?? $whiteLabel['nav_title']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Navigation Subtitle</label>
                        <input name="nav_subtitle" type="text" value="{{ old('nav_subtitle', $branding->nav_subtitle ?? $whiteLabel['nav_subtitle']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Badge</label>
                        <input name="welcome_badge" type="text" value="{{ old('welcome_badge', $branding->welcome_badge ?? $whiteLabel['welcome_badge']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Title</label>
                        <input name="welcome_title" type="text" value="{{ old('welcome_title', $branding->welcome_title ?? $whiteLabel['welcome_title']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Subtitle</label>
                        <input name="welcome_subtitle" type="text" value="{{ old('welcome_subtitle', $branding->welcome_subtitle ?? $whiteLabel['welcome_subtitle']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Welcome Description</label>
                        <textarea name="welcome_description" rows="3" class="mt-1 block w-full rounded border-gray-300 text-sm">{{ old('welcome_description', $branding->welcome_description ?? $whiteLabel['welcome_description']) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Login Heading</label>
                        <input name="login_heading" type="text" value="{{ old('login_heading', $branding->login_heading ?? $whiteLabel['login_heading']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Login Subheading</label>
                        <input name="login_subheading" type="text" value="{{ old('login_subheading', $branding->login_subheading ?? $whiteLabel['login_subheading']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Footer Text</label>
                        <input name="footer_text" type="text" value="{{ old('footer_text', $branding->footer_text ?? $whiteLabel['footer_text']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Footer Subtext</label>
                        <input name="footer_subtext" type="text" value="{{ old('footer_subtext', $branding->footer_subtext ?? $whiteLabel['footer_subtext']) }}" class="mt-1 block w-full rounded border-gray-300 text-sm">
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Color Branding</h2>
                </div>
                <div class="grid grid-cols-1 gap-5 px-6 py-6 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Primary Color</label>
                        <div class="mt-1 flex items-center gap-3">
                            <input name="primary_color" type="color" value="{{ old('primary_color', $branding->primary_color ?? $whiteLabel['primary_color']) }}" class="h-10 w-14 rounded border-gray-300 p-1">
                            <input type="text" value="{{ old('primary_color', $branding->primary_color ?? $whiteLabel['primary_color']) }}" class="block w-full rounded border-gray-300 text-sm font-mono" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Secondary Color</label>
                        <div class="mt-1 flex items-center gap-3">
                            <input name="secondary_color" type="color" value="{{ old('secondary_color', $branding->secondary_color ?? $whiteLabel['secondary_color']) }}" class="h-10 w-14 rounded border-gray-300 p-1">
                            <input type="text" value="{{ old('secondary_color', $branding->secondary_color ?? $whiteLabel['secondary_color']) }}" class="block w-full rounded border-gray-300 text-sm font-mono" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Accent Color</label>
                        <div class="mt-1 flex items-center gap-3">
                            <input name="accent_color" type="color" value="{{ old('accent_color', $branding->accent_color ?? $whiteLabel['accent_color']) }}" class="h-10 w-14 rounded border-gray-300 p-1">
                            <input type="text" value="{{ old('accent_color', $branding->accent_color ?? $whiteLabel['accent_color']) }}" class="block w-full rounded border-gray-300 text-sm font-mono" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Button Color</label>
                        <div class="mt-1 flex items-center gap-3">
                            <input name="button_color" type="color" value="{{ old('button_color', $branding->button_color ?? $whiteLabel['button_color']) }}" class="h-10 w-14 rounded border-gray-300 p-1">
                            <input type="text" value="{{ old('button_color', $branding->button_color ?? $whiteLabel['button_color']) }}" class="block w-full rounded border-gray-300 text-sm font-mono" readonly>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-600">Button Text Color</label>
                        <div class="mt-1 flex items-center gap-3">
                            <input name="button_text_color" type="color" value="{{ old('button_text_color', $branding->button_text_color ?? $whiteLabel['button_text_color']) }}" class="h-10 w-14 rounded border-gray-300 p-1">
                            <input type="text" value="{{ old('button_text_color', $branding->button_text_color ?? $whiteLabel['button_text_color']) }}" class="block w-full rounded border-gray-300 text-sm font-mono" readonly>
                        </div>
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

            <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-sm font-bold uppercase tracking-widest text-gray-700">Current White Label Configuration</h2>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 gap-4 text-sm md:grid-cols-2">
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Application Name</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['app_name'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Meta Title</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['meta_title'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3 md:col-span-2"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Meta Description</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['meta_description'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Navigation Title</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['nav_title'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Navigation Subtitle</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['nav_subtitle'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Welcome Badge</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['welcome_badge'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Welcome Title</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['welcome_title'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Welcome Subtitle</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['welcome_subtitle'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3 md:col-span-2"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Welcome Description</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['welcome_description'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Login Heading</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['login_heading'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Login Subheading</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['login_subheading'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Footer Text</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['footer_text'] }}</p></div>
                        <div class="rounded border border-gray-200 p-3"><span class="text-xs font-bold uppercase tracking-widest text-gray-500">Footer Subtext</span><p class="mt-1 font-semibold text-gray-800">{{ $whiteLabel['footer_subtext'] }}</p></div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-5">
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Primary</span>
                            <div class="mt-2 h-8 w-full rounded border border-gray-200" style="background-color: {{ $whiteLabel['primary_color'] }}"></div>
                            <p class="mt-1 text-xs font-mono text-gray-700">{{ $whiteLabel['primary_color'] }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Secondary</span>
                            <div class="mt-2 h-8 w-full rounded border border-gray-200" style="background-color: {{ $whiteLabel['secondary_color'] }}"></div>
                            <p class="mt-1 text-xs font-mono text-gray-700">{{ $whiteLabel['secondary_color'] }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Accent</span>
                            <div class="mt-2 h-8 w-full rounded border border-gray-200" style="background-color: {{ $whiteLabel['accent_color'] }}"></div>
                            <p class="mt-1 text-xs font-mono text-gray-700">{{ $whiteLabel['accent_color'] }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Button</span>
                            <div class="mt-2 h-8 w-full rounded border border-gray-200" style="background-color: {{ $whiteLabel['button_color'] }}"></div>
                            <p class="mt-1 text-xs font-mono text-gray-700">{{ $whiteLabel['button_color'] }}</p>
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Button Text</span>
                            <div class="mt-2 h-8 w-full rounded border border-gray-200" style="background-color: {{ $whiteLabel['button_text_color'] }}"></div>
                            <p class="mt-1 text-xs font-mono text-gray-700">{{ $whiteLabel['button_text_color'] }}</p>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Logo URL</span>
                            <p class="mt-1 break-all text-xs font-mono text-gray-700">{{ $whiteLabel['logo_url'] }}</p>
                            <img src="{{ $whiteLabel['logo_url'] }}" alt="Current logo" class="mt-2 h-12 w-12 rounded border border-gray-200 object-contain">
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Favicon URL</span>
                            <p class="mt-1 break-all text-xs font-mono text-gray-700">{{ $whiteLabel['favicon_url'] }}</p>
                            <img src="{{ $whiteLabel['favicon_url'] }}" alt="Current favicon" class="mt-2 h-8 w-8 rounded border border-gray-200 object-contain">
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Welcome Background URL</span>
                            <p class="mt-1 break-all text-xs font-mono text-gray-700">{{ $whiteLabel['welcome_bg_url'] }}</p>
                            <img src="{{ $whiteLabel['welcome_bg_url'] }}" alt="Current welcome background" class="mt-2 h-20 w-full rounded border border-gray-200 object-cover">
                        </div>
                        <div class="rounded border border-gray-200 p-3">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Login Background URL</span>
                            <p class="mt-1 break-all text-xs font-mono text-gray-700">{{ $whiteLabel['login_bg_url'] }}</p>
                            <img src="{{ $whiteLabel['login_bg_url'] }}" alt="Current login background" class="mt-2 h-20 w-full rounded border border-gray-200 object-cover">
                        </div>
                        <div class="rounded border border-gray-200 p-3 md:col-span-2">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-500">Open Graph Image URL</span>
                            <p class="mt-1 break-all text-xs font-mono text-gray-700">{{ $whiteLabel['og_image_url'] }}</p>
                            <img src="{{ $whiteLabel['og_image_url'] }}" alt="Current Open Graph image" class="mt-2 h-24 w-full rounded border border-gray-200 object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mx-auto max-w-5xl">
                <button type="submit" class="rounded px-5 py-2 text-xs font-semibold uppercase tracking-wider" style="background-color: {{ $whiteLabel['primary_color'] }}; color: {{ $whiteLabel['button_text_color'] }};">
                    Save White Label Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
