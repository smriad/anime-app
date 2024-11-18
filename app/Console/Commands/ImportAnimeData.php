<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Anime;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Carbon\Carbon;
use GuzzleHttp\Exception\RequestException;

class ImportAnimeData extends Command
{
    protected $signature = 'anime:import';
    protected $description = 'Import 100 most popular anime from Jikan API';

    public function handle()
    {
        $client = new Client();
        $url = 'https://api.jikan.moe/v4/top/anime?limit=25&page=';

        for ($page = 1; $page <= 4; $page++) {
            try {
                // Send the request to Jikan API
                $response = $client->get($url . $page);
                $rateLimitRemaining = $response->getHeader('X-RateLimit-Remaining')[0] ?? null;
                $rateLimitReset = $response->getHeader('X-RateLimit-Reset')[0] ?? null;

                // Rate limit handling
                if ($rateLimitRemaining !== null && $rateLimitRemaining <= 0) {
                    $resetTime = Carbon::createFromTimestamp($rateLimitReset);
                    $waitTime = $resetTime->diffInSeconds(Carbon::now());
                    
                    $this->warn("Rate limit exceeded. Waiting for {$waitTime} seconds...");
                    sleep($waitTime + 5); // Adding extra 5 seconds buffer
                }

                $animes = json_decode($response->getBody()->getContents(), true)['data'];

                foreach ($animes as $anime) {
                    try {
                        Anime::updateOrCreate(
                            ['mal_id' => $anime['mal_id']], // Check for mal_id uniqueness
                            [
                                'title' => $anime['title'],
                                'slug' => Str::slug($anime['title']),  // Generate slug dynamically
                                'synopsis' => $anime['synopsis'],
                                'image_url' => $anime['images']['jpg']['image_url'] ?? null,
                                'episodes' => $anime['episodes'],
                                'type' => $anime['type'],
                                'score' => $anime['score']
                            ]
                        );
                    } catch (\Illuminate\Database\QueryException $e) {
                        if ($e->getCode() === '23000') { // Duplicate entry error code
                            $this->info("Skipping duplicate entry for anime: {$anime['title']}");
                        } else {
                            throw $e; // Rethrow other exceptions
                        }
                    }
                }

                $this->info("Page {$page} imported successfully.");

            } catch (RequestException $e) {
                // Handle API request errors
                $this->error("Failed to fetch data for page {$page}. Error: " . $e->getMessage());

                // Check if it is a rate limit issue
                if ($e->hasResponse() && $e->getResponse()->getStatusCode() == 429) {
                    $rateLimitReset = $e->getResponse()->getHeader('X-RateLimit-Reset')[0] ?? null;
                    if ($rateLimitReset) {
                        $resetTime = Carbon::createFromTimestamp($rateLimitReset);
                        $waitTime = $resetTime->diffInSeconds(Carbon::now());
                        $this->warn("Rate limit exceeded. Waiting for {$waitTime} seconds...");
                        sleep($waitTime + 5); // Adding buffer time
                    }
                }
            }
        }

        $this->info('Anime data imported successfully!');
    }
}
