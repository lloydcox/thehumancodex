<?php

use Illuminate\Database\Seeder;

class PostCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_categories')->insert([
            [
                'title' => 'Thoughts/Ideas',
                'color_code' => '#9999ff'
            ],
            [
                'title' => 'Work: your career and finance',
                'color_code' => '#ff9999'
            ],
            [
                'title' => 'Story',
                'color_code' => '#cc99ff'
            ],
            [
                'title' => 'Learning: your personal development',
                'color_code' => '#99ffcc'
            ],
            [
                'title' => 'Social: your relationships with others',
                'color_code' => '#99ccff'
            ],
            [
                'title' => 'Advice',
                'color_code' => '#ffff99'
            ]
        ]);
    }
}
