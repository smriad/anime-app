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

            // If anime is not found, return 404 (Not Found)
            if (!$anime) {
                return response()->json([
                    'error' => 'Anime not found',
                    'http_status_code' => 404
                ], 404);
            }

            // Return the anime data with 200 status code if found
            return response()->json([
                'data' => $anime,
                'http_status_code' => 200
            ], 200);

        } catch (ModelNotFoundException $e) {
            // Specific error handling for model not found
            return response()->json([
                'error' => 'Anime entry does not exist in the database',
                'http_status_code' => 404
            ], 404);

        } catch (Exception $e) {
            // General error handling for unexpected issues
            return response()->json([
                'error' => 'An unexpected error occurred',
                'message' => $e->getMessage(),
                'http_status_code' => 500
            ], 500);
        }
    }
}
