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
        Schema::create('user_work_education', function (Blueprint $table) {
            $table->uuid("id");
            $table->uuid("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");;
            $table->string("name",512);
            $table->string("type",50);
            $table->string("year_start",10);
            $table->string("year_end",10)->default("now");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_work_education');
    }
};
