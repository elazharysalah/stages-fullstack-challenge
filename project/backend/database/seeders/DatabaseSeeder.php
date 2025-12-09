<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@blog.com',
                'password' => Hash::make('Admin123!'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@blog.com',
                'password' => Hash::make('Password123'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@blog.com',
                'password' => Hash::make('MySecret456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Create articles
        $articles = [
            [
                'title' => 'Le café du matin',
                'content' => 'Un article sur le café et ses bienfaits. Le café est une boisson délicieuse.',
                'author_id' => 1,
                'published_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(5),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'title' => 'Les vacances d\'été',
                'content' => 'Réflexions sur l\'été et les vacances en famille.',
                'author_id' => 1,
                'published_at' => Carbon::now()->subDays(4),
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'title' => 'L\'école et l\'éducation',
                'content' => 'Discussion sur le système éducatif et les élèves.',
                'author_id' => 2,
                'published_at' => Carbon::now()->subDays(3),
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => 'La technologie moderne',
                'content' => 'Comment la technologie change nos vies au quotidien.',
                'author_id' => 2,
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'Cuisine française',
                'content' => 'Recettes traditionnelles de la cuisine française.',
                'author_id' => 3,
                'published_at' => Carbon::now()->subDays(1),
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        for ($i = 6; $i <= 50; $i++) {
            $articles[] = [
                'title' => "Article numéro {$i}",
                'content' => "Contenu de l'article {$i}. Lorem ipsum dolor sit amet.",
                'author_id' => rand(1, 3),
                'published_at' => Carbon::now()->subDays(rand(1, 30)),
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30)),
            ];
        }

        DB::table('articles')->insert($articles);

        // Create comments
        $comments = [
            [
                'article_id' => 1,
                'user_id' => 2,
                'content' => 'Super article !',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'article_id' => 1,
                'user_id' => 3,
                'content' => 'Très intéressant, merci !',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'article_id' => 2,
                'user_id' => 1,
                'content' => 'Belle réflexion',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'article_id' => 3,
                'user_id' => 1,
                'content' => 'Je suis d\'accord',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'article_id' => 4,
                'user_id' => 3,
                'content' => 'Excellente analyse',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
        ];

        for ($i = 6; $i <= 50; $i++) {
            if (rand(1, 3) > 1) {
                $comments[] = [
                    'article_id' => $i,
                    'user_id' => rand(1, 3),
                    'content' => 'Commentaire sur l\'article ' . $i,
                    'created_at' => Carbon::now()->subDays(rand(1, 20)),
                    'updated_at' => Carbon::now()->subDays(rand(1, 20)),
                ];
            }
        }

        DB::table('comments')->insert($comments);
    }
}
