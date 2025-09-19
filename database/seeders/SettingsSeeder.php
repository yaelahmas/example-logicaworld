<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Branding & General
        Setting::setValue('site_name', 'Logica World');
        Setting::setValue('site_tagline', '');
        Setting::setValue('site_logo', '', 'url');
        Setting::setValue('site_favicon', '', 'url');
        Setting::setValue('site_email', '');
        Setting::setValue('site_phone', '');
        Setting::setValue('site_address', '');

        // SEO
        Setting::setValue('meta_title', 'Logica World');
        Setting::setValue('meta_keywords', 'IT Software, IT Solution, IT Consulting, Software House, ERP Software, System Integration, Financial Software, Supply Chain Management Software, .NET Technology');
        Setting::setValue('meta_description', 'Logica World is an IT Development company that helps companies to discover competitive advantage through effective IT implementation');
        Setting::setValue('meta_author', '@yaelahmas_');
        Setting::setValue('meta_canonical', 'https://www.logicaworld.com');
        Setting::setValue('meta_robots', 'index, follow');

        // Social Media
        Setting::setValue('facebook_url', 'https://facebook.com/', 'url');
        Setting::setValue('twitter_url', 'https://twitter.com/', 'url');
        Setting::setValue('instagram_url', 'https://instagram.com/', 'url');
        Setting::setValue('linkedin_url', 'https://linkedin.com/', 'url');
        Setting::setValue('twitter_username', '@yaelahmas_', 'url');

        // Open Graph / Twitter
        Setting::setValue('og_type', 'website', 'text');
        Setting::setValue('site_og_image', '', 'image'); // fallback thumbnail

        // Integrations
        Setting::setValue('google_analytics_id', '');
        Setting::setValue('chat_widget_code', '<script>/* widget */</script>');

        // Localization
        Setting::setValue('default_locale', 'id');
        Setting::setValue('currency_default', 'IDR');
        Setting::setValue('timezone_default', 'Asia/Jakarta');
    }
}
