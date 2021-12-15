<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tags = collect([
            'my',
            'your',
            'some',
            'more',
            'data',
            'export',
            'anything',
            'owner',
        ]);

        return [
            'title' => $this->faker->sentence(),
            'body'  => $this->faker->text(),
            'tags'  => $tags->random(2)->values()->all(),
        ];
    }
}
