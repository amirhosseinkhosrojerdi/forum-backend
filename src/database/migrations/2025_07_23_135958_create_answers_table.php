<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('thread_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Check if the database driver is SQLite
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            // For SQLite, we can drop the table directly
            Schema::dropIfExists('answers');
        } else {
            Schema::table('answers', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['thread_id']);
            });
            Schema::dropIfExists('answers');
        }
    }
}
