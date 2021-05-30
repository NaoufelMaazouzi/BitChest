<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // On crée le schéma pour la base de données
        Schema::create('product_size', function (Blueprint $table) {
            $table->unsignedInteger('size_id'); // le type doit correspondre au type de la clé primaire de la table size
            $table->unsignedInteger('product_id');
            // On met une cascade si on supprime une taille on supprime les informations dans la table de liaison 
            // En effet ces informations sont inutiles ils ne servent qu'à relier les deux tables si une taille disparaît de 
            // la base de données l'information dans la table de liaison n'a plus de sens.
            $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Si la table product_size on la supprime de la base de données
        Schema::dropIfExists('product_size');
    }
}
