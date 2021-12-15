<?php

namespace App\Providers;

use App\Articles\ArticlesRepository;
use App\Articles\ElasticsearchRepository;
use App\Articles\EloquentArticlesRepository;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            ArticlesRepository::class,
            EloquentArticlesRepository::class
        );
        $this->app->bind(ArticlesRepository::class, function ($app) {
            if (!config('services.search.enabled')) {
                return new EloquentArticlesRepository();
            }

            return new ElasticsearchRepository(
                $app->make(Client::class)
            );
        });

        $this->bindElasticsearchClient();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindElasticsearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
