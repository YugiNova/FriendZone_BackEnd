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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name',255);
            $table->string('last_name',255);
            $table->string('display_name',255);
            $table->string('nickname',255)->nullable();
            $table->string('email',255)->unique();
            $table->string('password');
            $table->string('role',10)->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('avatar_url',512)->nullable();
            $table->string('status',20);
            $table->string('slug',255)->unique();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
