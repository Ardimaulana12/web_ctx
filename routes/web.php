<?php

use App\Http\Controllers\ContactController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/product-and-services', 'services')->name('services');
Route::view('/case-study', 'cases')->name('cases');
Route::view('/blog', 'blog')->name('blog');
Route::view('/contact', 'contact')->name('contact');
Route::view('/about', 'about')->name('about');
Route::post('/language', function (Request $request) {
    $data = $request->validate(['locale' => ['required', 'in:id,en'], 'return_to' => ['nullable', 'string']]);
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
