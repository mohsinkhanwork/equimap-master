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
        Schema::create('users_profiles', function (Blueprint $table) {
            $table->id();
            $table->string( 'name', 100 );
            $table->string( 'email' )->nullable();
            $table->enum('level', [
                'beginner',
                'intermediate',
                'advanced'
            ])->nullable();
            $table->enum( 'gender', [ 'male', 'female'] )->nullable();
            $table->date( 'birthday' )->nullable();
            $table->enum( 'language', [ 'en', 'ar' ] )->default('en');
            $table->integer( 'weight' )->nullable();
            $table->foreignId('user_id')->constrained('users')->unique();
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
        Schema::dropIfExists('users_profiles');
    }
};
