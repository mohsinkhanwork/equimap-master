<?php

use App\Models\Trip;
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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->boolean('approved')->default(false);
            $table->integer('sort')->default(0);
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->longText('description');
            $table->enum('type', Trip::getTripTypes());
            $table->longText('included_items')->nullable();
            $table->longText('excluded_items')->nullable();
            $table->integer('price');
            $table->string( 'currency', 3 )->default( 'AED' );
            $table->integer('origin_country_id')->constrained( 'countries' )->nullOnDelete();
            $table->integer('destination_country_id')->constrained( 'countries' )->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer( 'capacity' )->default(1 );
            $table->foreignId( 'category_id' )->nullable()->constrained( 'categories' )->nullOnDelete();
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
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
        Schema::dropIfExists('trips');
    }
};
