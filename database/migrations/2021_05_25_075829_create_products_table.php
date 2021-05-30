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
        // On crée le schéma pour la base de données
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id'); // clé primaire
            $table->unsignedInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->string('name', 100); // VARCHAR 100
            $table->text('description'); // TEXT 
            $table->float('price', 8, 2); // FLOAT 
            $table->enum('status', ['published', 'unpublished'])->default('unpublished');
            $table->enum('state', ['solde', 'standard'])->default('standard');
            $table->string('reference', 16);
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
        // Si la table categories on la supprime de la base de données
        Schema::dropIfExists('products');
    }
}
