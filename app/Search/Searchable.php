<?php

namespace App\Search;

use Elasticsearch\Client;

trait Searchable
{
    public static function bootSearchable()
    {
        if (config('services.search.enable')) {
            static::observe(ElasticsearchObserver::class);
        }
    }

    public function elasticsearchIndex(Client $client)
    {
        $client->index([
            'index' => $this->getSearchIndex(),
            'type'  => $this->getSearchType(),
            'id'    => $this->getKey(),
            'body'  => $this->toElasticsearchDocumentArray(),
        ]);
    }

    abstract public function toElasticsearchDocumentArray(): array;

    public function elasticsearchDelete(Client $client)
    {
        $client->delete([
            'index' => $this->getSearchIndex(),
            'type'  => $this->getSearchType(),
            'id'    => $this->getKey(),
        ]);
    }
}
