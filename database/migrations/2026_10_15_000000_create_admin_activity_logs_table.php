<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('admin')->nullable();          // email / name of the admin who acted
            $table->string('action', 60);                 // e.g. settings.updated, user.banned
            $table->string('target_type', 60)->nullable(); // e.g. User, PromptTemplate
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('description');
            $table->json('meta')->nullable();
            $table->string('ip', 45)->nullable();
            $table->timestamps();

            $table->index(['action', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activity_logs');
    }
};
