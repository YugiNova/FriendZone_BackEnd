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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('send_id');
            $table->uuid('recieve_id');
            $table->set('type',['request','post','comment','like','share']);
            $table->uuid('source_id')->nullable();
            $table->string('content',512);
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('send_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('recieve_id')->references('id')->on('users')->onDelete('cascade');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
