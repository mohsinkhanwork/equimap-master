<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function( Blueprint $table ){
            $table->id();
            $table->morphs('packageable');
            $table->boolean('active')->default(true);
            $table->boolean('approved')->default(false);
            $table->integer('sort')->default(0);
            $table->string('name', 100);
            $table->integer('price');
            $table->integer('quantity');
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('packages');
    }
};
