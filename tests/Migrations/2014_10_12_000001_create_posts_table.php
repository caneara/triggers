<?php declare(strict_types = 1);

use Triggers\Trigger;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up() : void
    {
        Schema::create('posts', function(Blueprint $table) {
            $table->unsignedTinyInteger('id');
            $table->unsignedTinyInteger('user_id');
            $table->string('title');
        });

        Trigger::table('posts')->key('test')->afterDelete(function() {
            return "UPDATE `users` SET `posts` = 0 WHERE `id` = OLD.user_id;";
        });

        Trigger::table('posts')->afterInsert(function() {
            return "UPDATE `users` SET `posts` = 1 WHERE `id` = NEW.user_id;";
        });
    }

    /**
     * Reverse the migrations.
     *
     */
    public function down() : void
    {
        Schema::dropIfExists('posts');
    }
}
