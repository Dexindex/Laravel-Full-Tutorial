<?php
//?-----------Laravel --------------------------------------------------------------------------------------------------------------------------------------



//!--Database Connexion (.env file):----------------------------------------------------------------------------------------------------------------------
[
    DB_CONNECTION=mysql
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=parcours_site
    DB_USERNAME=root
    DB_PASSWORD=
]





//!--Create Tables (Standard):----------------------------------------------------------------------------------------------------------------------
[
    ```
    -In Laravel, to create a table, you need to create a migration representing all table columns, constraints, and foreign keys.
    -In addition to the migration, we can create a model to interact with the table data.
    The model serves as an intermediary between the application and the database(Defining Relationships [one-to-one, one-to-many, many-to-many] ),
    allowing you to perform CRUD operations (Create, Read, Update, Delete) on the database table. 
    ```
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
            
]




//!Create Tables (Relational):----------------------------------------------------------------------------------------------------------------------
[
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

    -4:Crating Models(Defining Relations):
            In Terminal : php artisan make:model User
                        php artisan make:model Post


    -5:Models Structures(In app/Models) :
            ````````````````````````````````````````````````````````````````````````````````
            //User.php
            namespace App\Models;

            use Illuminate\Database\Eloquent\Model;

            class User extends Model
            {
                public function posts()
                {
                    return $this->hasMany(Post::class); //Defining Relation Cardinalite
                }
            }

            //?//?//?//?////////////////////////////////////////////////////////////////

            //Post.php
            namespace App\Models;

            use Illuminate\Database\Eloquent\Model;

            class Post extends Model
            {
                public function user()
                {
                    return $this->belongsTo(User::class);
                }
            }


            ````````````````````````````````````````````````````````````````````````````````````

]

//!CRUD:
```
-In Laravel We Have Controllers(Controllers) & Views(Views) ,The Controllers are responsible for handling user requests(CRUD,Search,Other Functions)
, and the Views are responsible for displaying the data.
```
1-Read(Standard):----------------------------------------------------------------------------------------------------------------------
[
    -- Create A Controller :
            In Terminal : php artisan make:controller PostController
    --Structure(app/Http/Controllers)
        ````````````````````````````````````````````````````````````````````````````````````
        namespace App\Http\Controllers;

        use App\Models\Post;
        use Illuminate\Http\Request;

        class PostController extends Controller
        {
            public function index()
            {
                $posts = Post::all(); // Fetch all posts from the database (Like Fetch query)
                return view('posts.index', ['posts' => $posts]); //index method fetches all posts from the posts table(Like Excute query)
            }
        }

        ````````````````````````````````````````````````````````````````````````````````````
    --Define a Route(routes/web.php)
        ````````````````````````````````````````````````````````````````````````````````````
        use App\Http\Controllers\PostController; //importing controller

        Route::get('/posts', [PostController::class, 'index']); //Retrieve all posts With A Get Request

        ````````````````````````````````````````````````````````````````````````````````````

    --Create a Blade Template(resources/views/posts/index.blade.php)
        ````````````````````````````````````````````````````````````````````````````````````
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Posts</title>
            </head>
            <body>
                <h1>Posts</h1>
                <ul>
                    @foreach($posts as $post)
                        <li>{{ $post->title }}</li>
                        <p>{{ $post->content }}</p>
                    @endforeach
                </ul>
            </body>
            </html>

        ````````````````````````````````````````````````````````````````````````````````````
]

1-2Read(Related):----------------------------------------------------------------------------------------------------------------------
[
    -- //! Make Sure You Decalare Your Model First With Proper Relationships :
    -- Create A Controller :
            In Terminal : php artisan make:controller PostController
    --Structure(app/Http/Controllers)
        ````````````````````````````````````````````````````````````````````````````````````
        namespace App\Http\Controllers;

        use App\Models\Post;

        class PostController extends Controller
        {
            public function index()
            {
                $posts = Post::with('user')->get(); // Eager load the user relationship
                return view('posts.index', ['posts' => $posts]);
            }
        }

        ````````````````````````````````````````````````````````````````````````````````````
    --Define a Route(routes/web.php)
        ````````````````````````````````````````````````````````````````````````````````````
        use App\Http\Controllers\PostController; //importing controller

        Route::get('/posts', [PostController::class, 'index']); //Retrieve all posts With A Get Request

        ````````````````````````````````````````````````````````````````````````````````````

    --Create a Blade Template(resources/views/posts/index.blade.php)
        ````````````````````````````````````````````````````````````````````````````````````
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Posts</title>
        </head>
        <body>
            <h1>Posts</h1>
            <ul>
                @foreach($posts as $post)
                    <li>{{ $post->title }}</li>
                    <p>{{ $post->content }}</p>
                    <p>Posted by: {{ $post->user->name }}</p> 
                @endforeach
            </ul>
        </body>
        </html>


        ````````````````````````````````````````````````````````````````````````````````````
]










?>
