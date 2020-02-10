<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAlimentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('alimentations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');   
            $table->integer('montant');
            $table->date('date_alim');        
            $table->integer('method_id');             
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
        Schema::drop('alimentations');
    }
}
