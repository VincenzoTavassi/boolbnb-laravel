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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->string('image', 200)->nullable();
            $table->decimal('price');
            $table->integer('single_beds');
            $table->integer('double_beds');
            $table->integer('bathrooms');
            $table->integer('square_meters');
            $table->integer('rooms');
            $table->string('address', 100);
            $table->double('latitude', 10, 8);
            $table->double('longitude', 11, 8);
            $table->boolean('visible');
            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartments');
    }
};
