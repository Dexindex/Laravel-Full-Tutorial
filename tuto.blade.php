<?php
//?-----------Laravel --------------------------------------
//!--Database Connexion (.env file):
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=parcours_site
DB_USERNAME=root
DB_PASSWORD=


//!--Create Tables (Standard):
-1:Create a Migration (Respect Naming) :
        In Terminal :   php artisan make:migration create_users_table
-2:Structure (Inside Database/Migrations) :
        ````````````````````````````````````````````````````````````````````````````````
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class CreateUsersTable extends Migration
        {
            public function up()
            {
                Schema::create('users', function (Blueprint $table) {
                    $table->id();
                    $table->string('name');
                    $table->string('email')->unique();
                    $table->timestamp('email_verified_at')->nullable();
                    $table->string('password');
                    $table->rememberToken();
                    $table->timestamps();
                });
            }

            public function down()
            {
                Schema::dropIfExists('users');
            }
        }
        ````````````````````````````````````````````````````````````````````````````````````
-3:Creating Table In Database :
        In Terminal :   php artisan migrate


//!Create Tables (Relational):
1-Create a Migration (Respect Naming):
        In Terminal : php artisan make:migration create_posts_table
-2:Structure (Inside Database/Migrations) :
        ````````````````````````````````````````````````````````````````````````````````
        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        class CreatePostsTable extends Migration
        {
            public function up()
            {
                Schema::create('posts', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('user_id');
                    $table->string('title');
                    $table->text('content');
                    $table->timestamps();

                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); //Foreign Key From users Table
                });
            }

            public function down()
            {
                Schema::dropIfExists('posts');
            }
        }

        ````````````````````````````````````````````````````````````````````````````````````
-3:Creating Tables In Database :
        In Terminal :   php artisan migrate













?>
