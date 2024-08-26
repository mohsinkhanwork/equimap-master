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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100 )->unique();
            $table->enum('status', [ 'paid', 'refunded' ] );
            $table->foreignId('booking_id')->nullable()->constrained('bookings', 'id' )->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id' )->nullOnDelete();
            $table->enum('processor', ['stripe']);
            $table->integer('amount');
            $table->integer('tax');
            $table->integer('commission');
            $table->boolean('settled')->default(false);
            $table->string('currency', 3 );
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
