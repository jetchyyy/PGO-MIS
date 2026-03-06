<?php

namespace App\Http\Controllers;

use App\Models\BrandingSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WhiteLabelController extends Controller
{
    public function edit(): View
    {
        $branding = BrandingSetting::query()->firstOrCreate([]);

        return view('white-label.edit', compact('branding'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['nullable', 'string', 'max:120'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'nav_title' => ['nullable', 'string', 'max:120'],
            'nav_subtitle' => ['nullable', 'string', 'max:120'],
            'welcome_badge' => ['nullable', 'string', 'max:120'],
            'welcome_title' => ['nullable', 'string', 'max:150'],
            'welcome_subtitle' => ['nullable', 'string', 'max:150'],
            'welcome_description' => ['nullable', 'string', 'max:500'],
            'login_heading' => ['nullable', 'string', 'max:120'],
            'login_subheading' => ['nullable', 'string', 'max:180'],
            'footer_text' => ['nullable', 'string', 'max:180'],
            'footer_subtext' => ['nullable', 'string', 'max:180'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp,svg', 'max:4096'],
            'welcome_bg' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:8192'],
            'login_bg' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:8192'],
            'og_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:8192'],
            'favicon' => ['nullable', 'image', 'mimes:png,ico', 'max:1024'],
        ]);

        $branding = BrandingSetting::query()->firstOrCreate([]);
        $branding->fill($validated);

        $this->replaceImage($request, $branding, 'logo', 'logo_path');
        $this->replaceImage($request, $branding, 'welcome_bg', 'welcome_bg_path');
        $this->replaceImage($request, $branding, 'login_bg', 'login_bg_path');
        $this->replaceImage($request, $branding, 'og_image', 'og_image_path');
        $this->replaceImage($request, $branding, 'favicon', 'favicon_path');

        $branding->save();

        return redirect()
            ->route('white-label.edit')
            ->with('status', 'White label settings updated.');
    }

    private function replaceImage(Request $request, BrandingSetting $branding, string $inputKey, string $column): void
    {
        if (!$request->hasFile($inputKey)) {
            return;
        }

        $oldPath = $branding->{$column};

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        $branding->{$column} = $request->file($inputKey)->store('branding', 'public');
    }
}
