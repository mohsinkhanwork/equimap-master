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
        Schema::create('horses', function (Blueprint $table) {
            $table->id();
            $table->boolean( 'active' );
            $table->string( 'name', 100 );
            $table->foreignId( 'provider_id')->constrained('providers','id');
            $table->enum('gender', ['stallion','mare','gelding'])->nullable();
            $table->enum('level', [
                'beginner',
                'intermediate',
                'advanced'
            ])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horses');
    }
};
