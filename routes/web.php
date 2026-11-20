<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', [WebsiteController::class, 'show'])->name('home');
Route::get('/product-and-services', [WebsiteController::class, 'show'])->name('services');
Route::get('/case-study', [WebsiteController::class, 'show'])->name('cases');
Route::get('/blog', [WebsiteController::class, 'show'])->name('blog');
Route::get('/contact', [WebsiteController::class, 'show'])->name('contact');
Route::get('/about', [WebsiteController::class, 'show'])->name('about');
Route::post('/language', function (Request $request) {
    $data = $request->validate(['locale' => ['required', Rule::exists('bahasa', 'kode_bahasa')->where('status', 'aktif')], 'return_to' => ['nullable', 'string']]);
    $request->session()->put('locale', $data['locale']);
    $destination = $data['return_to'] ?? '/';
    $path = parse_url($destination, PHP_URL_PATH);
    if (! in_array($path, ['/', '/about', '/contact', '/product-and-services', '/case-study', '/blog'], true)
        || ! str_starts_with($destination, '/') || str_starts_with($destination, '//') || str_contains($destination, '\\')) {
        $destination = '/';
    }

    return redirect($destination);
})->name('language.update');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');
