<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimeController;  // Add this line to import AnimeController



// Route to show anime by slug
Route::get('/anime/{slug}', [AnimeController::class, 'show']);
