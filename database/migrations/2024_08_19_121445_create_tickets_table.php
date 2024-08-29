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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_meta_id')->constrained('users_metas')->onDelete('cascade');
            $table->foreignId('assigned_support_id')->nullable()->constrained('users_metas')->onDelete('set null');
            $table->string('subject');
            $table->text('description');
            $table->enum('status', ['new', 'reviewed', 'closed'])->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
