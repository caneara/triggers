<?php declare(strict_types = 1);

namespace Triggers\Tests\World;

use Illuminate\Support\Facades\DB;

class Builder
{
    /**
     * Construct the world.
     *
     */
    public static function create() : void
    {
        $setup = [
            'driver'         => 'mysql',
            'url'            => env('DATABASE_URL'),
            'host'           => env('DB_HOST', '127.0.0.1'),
            'port'           => env('DB_PORT', 3306),
            'database'       => env('DB_DATABASE', 'testing'),
            'username'       => env('DB_USERNAME', 'root'),
            'password'       => env('DB_PASSWORD', ''),
            'unix_socket'    => env('DB_SOCKET', ''),
            'charset'        => 'utf8mb4',
            'collation'      => 'utf8mb4_unicode_520_ci',
            'prefix'         => '',
            'prefix_indexes' => true,
            'strict'         => true,
            'engine'         => null,
        ];

        app('config')->set('database.default', 'mysql');
        app('config')->set('database.migrations', 'migrations');
        app('config')->set('database.connections.mysql', $setup);
    }

    /**
     * Seed the database.
     *
     */
    public static function seed() : void
    {
        DB::table('posts')->truncate();

        DB::table('users')->insert(['id' => 1, 'posts' => 0]);
        DB::table('users')->insert(['id' => 2, 'posts' => 0]);

        DB::table('posts')->insert(['id' => 1, 'user_id' => 1, 'title' => 'Lorem ipsum']);
        DB::table('posts')->insert(['id' => 2, 'user_id' => 1, 'title' => 'Dolor sit']);
        DB::table('posts')->insert(['id' => 3, 'user_id' => 1, 'title' => 'Amet consectetur']);
        DB::table('posts')->insert(['id' => 4, 'user_id' => 1, 'title' => 'Adipiscing elit']);
        DB::table('posts')->insert(['id' => 5, 'user_id' => 2, 'title' => 'Sed do']);
        DB::table('posts')->insert(['id' => 6, 'user_id' => 2, 'title' => 'Eiusmod tempor']);
        DB::table('posts')->insert(['id' => 7, 'user_id' => 2, 'title' => 'Incididunt ut']);
        DB::table('posts')->insert(['id' => 8, 'user_id' => 2, 'title' => 'Labore et']);
    }
}
