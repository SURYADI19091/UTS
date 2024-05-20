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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description'); // Corrected the typo in 'description'
            $table->integer('price');
            $table->string('image', 255)->nullable();
            $table->dateTime('expired_at')->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->string('modified_by')->nullable(false); // Assuming 'modified_by' refers to the email of the user

            // Define foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('modified_by')->references('email')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
