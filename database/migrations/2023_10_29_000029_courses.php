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
        Schema::create('courses', function( Blueprint $table ){
            $table->id();
            $table->boolean('active')->default(true);
            $table->boolean('approved')->default(false);
            $table->integer('sort')->default(0);
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->longText('description')->nullable();
            $table->integer('price');
            $table->string( 'currency', 3 )->default( 'AED' );
            $table->enum('unit', [ 'hour', 'day' ] );
            $table->enum('progression_type', [ 'random', 'linear' ] );
            $table->foreignId( 'category_id' )->nullable()->constrained( 'categories' )->nullOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
            $table->mediumText('notes')->nullable()->default(null);
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
        Schema::dropIfExists('courses');
    }
};
