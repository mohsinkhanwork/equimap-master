<?php

use App\Models\Booking;
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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 12 );
            $table->morphs('bookable');
            $table->foreignId('user_id')->constrained('users', 'id' );
            $table->foreignId('horse_id')->nullable()->constrained('horses', 'id' )->nullOnDelete();
            $table->foreignId('trainer_id')->nullable()->constrained('trainers', 'id' )->nullOnDelete();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', Booking::getBookingStatuses() );
            $table->mediumText( 'notes' );
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
        Schema::dropIfExists('bookings');
    }
};
