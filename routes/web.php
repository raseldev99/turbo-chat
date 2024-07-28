<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');
Route::get('/get-video',function (\App\Services\InstagramScraperService $instagramScraperService){
    try {
        $videoUrl = $instagramScraperService->getVideoUrl('https://www.instagram.com/reel/C9VO2i4yzE7');
        return response()->json(['video_url' => $videoUrl]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
