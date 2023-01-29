<?php

use App\Connection;
use App\Post;
use App\User;
use App\UserData;
use Illuminate\Database\Seeder;
use App\Comments;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->truncate();
        UserData::query()->truncate();
        Post::query()->truncate();
        Comments::query()->truncate();
        Connection::query()->truncate();

        $admin = factory(App\User::class)->create([
            'first_name' => 'Test',
            'last_name' => 'Test',
            'email' => 'test@example.com',
            'password' => bcrypt('test123'),
            'validated' => 0,
            'active' => 0,
            'reported' => 0,
            'type' => 1,
        ]);
        factory(App\UserData::class)->create([
            'user_id' => $admin->id
        ]);

        $users = factory(App\User::class, 10)->create()->each(function ($u) {
            factory(App\UserData::class)->create([
                'user_id' => $u->id
            ]);
            factory(App\Post::class, rand(0, 10))->create(['user_id' => $u->id])->each(function ($p){
                factory(App\Comments::class, rand(0, 5))->create([
                    'post_id' => $p->id
                ]);
            });
        });

        $users = $users->push($admin);
        foreach ($users as $user) {
            factory(App\Connection::class, rand(1, 10))->create(['user_id' => $user->id]);
        }
    }
}