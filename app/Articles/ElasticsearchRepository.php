<?php

namespace App\Articles;

use App\Models\Article;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class ElasticsearchRepository implements ArticlesRepository
{
    public function __construct(private Client $client)
    {
    }

    public function search(string $query = ''): Collection
    {
        $items = $this->searchWithElastic($query);

        return $this->buildCollection($items);
    }

    private function searchWithElastic(string $query = ''): array
    {
        $model = new Article();

        return $this->client->search([
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'body'  => [
                'query' => [
                    'multi_match' => [
                        'fields' => ['title^5', 'body', 'tags'],
                        'query'  => $query,
                    ],
                ],
            ],
        ]);
    }

    private function buildCollection(array $items): Collection
    {
        $ids = Arr::pluck($items['hits']['hits'], '_id');

        return Article::findMany($ids)->sortBy(
            fn($article) => array_search($article->getKey(), $ids)
        );
    }
}
