<?php

namespace Database\Seeders;

use App\Models\Bahasa;
use App\Models\HalamanWebsite;
use App\Models\KontenWebsite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteSeeder extends Seeder
{
    public function run(): void
    {
        $source = json_decode(file_get_contents(__DIR__.'/data/website.json'), true, 512, JSON_THROW_ON_ERROR);
        $pages = [
            'home' => ['/', 'Home', 'CTX Corp — Social Intelligence Analytics'],
            'services' => ['/product-and-services', 'Services', 'Products & Services — CTX Corp'],
            'cases' => ['/case-study', 'Case Study', 'Case Study — CTX Corp'],
            'blog' => ['/blog', 'Blog', 'Blog — CTX Corp'],
            'about' => ['/about', 'About', 'Company Profile'],
            'contact' => ['/contact', 'Contact us', 'Contact Us — CTX Corp'],
        ];
        DB::transaction(function () use ($source, $pages) {
            foreach (['en' => ['English', 'en_US'], 'id' => ['Indonesia', 'id_ID']] as $code => [$name, $locale]) {
                $language = Bahasa::firstOrCreate(['kode_bahasa' => $code], [
                    'nama_bahasa' => $name, 'lokal_bahasa' => $locale, 'status' => 'aktif',
                    'is_default' => $code === 'en' && ! Bahasa::where('is_default', true)->exists(),
                ]);
                // One existing office per language: do not recreate it if its email was edited.
                if (! $language->kontak()->exists()) {
                    $language->kontak()->create($source['contact'] + ['status' => 'aktif']);
                }
                foreach ($pages as $route => [$slug, $label, $title]) {
                    $page = HalamanWebsite::firstOrCreate(['nama_halaman' => $route], [
                        'slug' => $slug, 'deskripsi' => $label,
                        'urutan' => array_search($route, array_keys($pages)), 'status' => 'aktif',
                    ]);
                    if (! $page->menu()->exists()) {
                        $page->menu()->create(['nama_menu' => $label,
                            'ikon' => null, 'urutan' => $page->urutan, 'status' => 'aktif']);
                    }
                    $translations = $source['translations'][$code];
                    KontenWebsite::firstOrCreate(['id_halaman' => $page->id_halaman, 'id_bahasa' => $language->id_bahasa, 'judul' => 'page'], [
                        'isi' => ['sections' => $source['pages'][$route] ?? []],
                        'meta_title' => ($translations[$title] ?? $title).($route === 'about' ? ' — CTX Corp' : ''),
                        'meta_description' => $translations['Cortex Data Indonesia — social intelligence, market research, and data insight solutions.'],
                        'status' => 'aktif',
                    ]);
                    if ($route === 'home') {
                        KontenWebsite::firstOrCreate(['id_halaman' => $page->id_halaman, 'id_bahasa' => $language->id_bahasa, 'judul' => 'shared'], [
                            'isi' => $source['shared'] + ['translations' => $translations], 'status' => 'aktif',
                        ]);
                    }
                }
            }
        });
    }
}
