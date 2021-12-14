<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str as Str;

class ProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $migrate = false;
        while (!$migrate) {
            if (Schema::hasTable('users')){
                Schema::create('products', function (Blueprint $table) {
                    $table->id();
                    $table->string('slug')->unique();
                    $table->string('name');
                    $table->string('description');
                    $table->uuid('creator');
                    $table->foreign('creator')->references('id')->on('users');
                    $table->string('image')->unique();
                    $table->timestamps();
                });
                $migrate = true;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

