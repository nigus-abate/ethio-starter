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
        Schema::create('backups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('disk');
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->string('type')->default('full'); // full, incremental, differential
            $table->string('status')->default('pending'); // pending, running, completed, failed
            $table->text('metadata')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();
            //$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backups');
    }
};
