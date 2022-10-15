<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post =[
            [
                'title'=>'Post One',
                'excerpt'=>'summary of post one',
                'body'=>'Body of post one',
                'image_path'=>'Empty',
                'min_to_read'=>2,
                'is_published'=>false
            ],
            [
                'title'=>'Post Two',
                'excerpt'=>'summary of post Two',
                'body'=>'Body of post Two',
                'image_path'=>'Empty',
                'min_to_read'=>2,
                'is_published'=>false
            ]

        ];
        foreach($post as $key=>$value){
            Post::create($value);


        }
    }
}
