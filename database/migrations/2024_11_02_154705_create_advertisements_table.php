<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['إيجار', 'شراء']);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('neighbourhood_id')->constrained('neighbourhoods')->cascadeOnDelete()->cascadeOnUpdate();
            $table->bigInteger('price');
            $table->text('location');
            $table->decimal('from_area');
            $table->decimal('to_area');
            $table->enum('real_estate_age', ['جديد', 'مستعمل']);
            $table->integer('real_estate_age_number')->nullable();
            $table->enum('real_estate_property', ['دوبلكس', 'مودرن']);
            $table->text('description');
            $table->integer('bedrooms_number')->nullable();
            $table->integer('bathrooms_number')->nullable();
            $table->integer('reception_and_sitting_rooms_number')->nullable();
            $table->decimal('street_width')->nullable();
            $table->integer('surrounding_streets_number')->nullable();
            $table->enum('real_estate_front', ['شمال', 'جنوب', 'شرق', 'غرب', 'شمال شرق', 'شمال غرب', 'جنوب شرق', 'جنوب غرب'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};
