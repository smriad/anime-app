<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AnimeController extends Controller
{
    public function show($slug)
    {
        try {
            // Attempt to fetch the anime by slug
            $anime = Anime::where('slug', $slug)->first();

            // Check if anime was found
            if (!$anime) {
                return response()->json(['error' => 'Anime not found'], 404);
            }

            // Return the anime data
            return response()->json($anime, 200);

        } catch (ModelNotFoundException $e) {
            // Handle specific model not found exceptions
            return response()->json(['error' => 'Anime entry does not exist in the database'], 404);

        } catch (Exception $e) {
            // Handle general exceptions
            return response()->json([
                'error' => 'An unexpected error occurred while retrieving the anime.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
