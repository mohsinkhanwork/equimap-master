<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->boolean('approved')->default(false);
            $table->integer('sort')->default(0);
            $table->string('name');
            $table->longText('description')->nullable();
            $table->integer('price');
            $table->string( 'currency', 3 )->default( 'AED' );
            $table->enum('unit', [ 'hour', 'day' ] );
            $table->integer( 'capacity' )->default(1 );
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
        Schema::dropIfExists('services');
    }
};
