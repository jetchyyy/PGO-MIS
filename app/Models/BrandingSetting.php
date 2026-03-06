<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandingSetting extends Model
{
    protected $fillable = [
        'app_name',
        'meta_title',
        'meta_description',
        'nav_title',
        'nav_subtitle',
        'welcome_badge',
        'welcome_title',
        'welcome_subtitle',
        'welcome_description',
        'login_heading',
        'login_subheading',
        'footer_text',
        'footer_subtext',
        'logo_path',
        'welcome_bg_path',
        'login_bg_path',
        'og_image_path',
        'favicon_path',
        'primary_color',
        'secondary_color',
        'accent_color',
        'button_color',
        'button_text_color',
    ];
}
