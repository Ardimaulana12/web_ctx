<?php

namespace App\Http\Controllers;

use App\Models\HalamanWebsite;
use App\Models\MenuNavigasi;
use App\Services\WebsiteContent;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function show(Request $request, WebsiteContent $content)
    {
        $name = $request->route()->getName();
        $page = HalamanWebsite::aktif()->where('nama_halaman', $name)->firstOrFail();
        $language = $request->attributes->get('website.language');
        $pageContent = $page->konten()->aktif()->where('id_bahasa', $language->id_bahasa)->where('judul', 'page')->firstOrFail();
        $shared = $content->shared($language->kode_bahasa);
        $menus = MenuNavigasi::aktif()->whereHas('halaman', fn ($query) => $query->aktif())
            ->with('halaman')->orderBy('urutan')->orderBy('id_menu')->get();
        $contact = $language->kontak()->aktif()->orderBy('id_kontak')->firstOrFail();

        return view($name, [
            'page' => $page, 'pageContent' => $pageContent,
            'sections' => $pageContent->isi['sections'], 'shared' => $shared,
            'menus' => $menus, 'contact' => $contact,
            'languages' => $request->attributes->get('website.languages'),
            'whatsappUrl' => 'https://wa.me/'.preg_replace('/\D/', '', $contact->telepon).'?text='.rawurlencode($shared['whatsapp_message']),
        ]);
    }
}
