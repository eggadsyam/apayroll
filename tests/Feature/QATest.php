<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class QATest extends TestCase
{
    use RefreshDatabase;

    public function test_all_get_routes()
    {
        $this->seed(DatabaseSeeder::class);
        $user = User::first();
        if (! $user) {
            $user = User::factory()->create();
        }

        $routes = collect(Route::getRoutes())->filter(function ($route) {
            $uri = $route->uri();

            return in_array('GET', $route->methods())
                && ! str_contains($uri, '{')
                && ! str_starts_with($uri, '_')
                && ! str_starts_with($uri, 'api')
                && $uri !== 'logout'
                && $uri !== 'up';
        });

        $errors = [];

        foreach ($routes as $route) {
            $uri = $route->uri();
            if ($uri === '/') {
                continue;
            }

            try {
                $response = $this->actingAs($user)->get('/'.$uri);
                if ($response->getStatusCode() >= 500) {
                    $errors[] = "Route /{$uri} returned 500: ".$response->exception?->getMessage();
                } elseif ($response->getStatusCode() == 404) {
                    $errors[] = "Route /{$uri} returned 404";
                }
            } catch (\Exception $e) {
                $errors[] = "Route /{$uri} threw exception: ".$e->getMessage();
            }
        }

        $this->assertEmpty($errors, "Found errors in routes:\n".implode("\n", $errors));
    }
}
