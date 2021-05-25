<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id'); // clé primaire
            $table->string('name', 100); // VARCHAR 100
            $table->text('description')->nullable(); // TEXT NULL
            $table->float('price', 8, 2); // FLOAT 
            $table->char('size', 2);
            $table->enum('status', ['published', 'unpublished'])->default('unpublished');
            $table->enum('state', ['solde', 'standard'])->default('standard');
            $table->string('reference', 16);
            $table->enum('category', ['homme', 'femme'])->default('homme');
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
        Schema::dropIfExists('products');
    }
}
