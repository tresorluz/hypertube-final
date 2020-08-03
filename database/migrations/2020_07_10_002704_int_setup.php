<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IntSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('provider')->nullable();
            $table->string('language')->default('en');
            $table->string('path_picture')->nullable();
            $table->rememberToken();
            $table->timestamps();
          });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->unsignedInteger('user_id');
            $table->text('name');
            $table->text('content_id');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('films', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->text('name');
            $table->text('hash');
            $table->text('text')->nullable();
            $table->timestamps();
        });

        Schema::create('watched_movies', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->text('id_movie');
            $table->text('name');
            $table->text('hash');
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
        Schema::dropIfExists('watched_movies');
        Schema::dropIfExists('films');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}
