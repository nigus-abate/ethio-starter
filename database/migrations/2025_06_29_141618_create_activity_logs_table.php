<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // e.g., 'user.created', 'order.updated'
            // Polymorphic subject
            $table->nullableMorphs('subject'); // subject_type, subject_id
            // Causer of the action (usually a User)
            $table->nullableMorphs('causer'); // causer_type, causer_id
            $table->json('properties')->nullable(); // Optional metadata
            $table->text('description')->nullable(); // Human-readable message
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
