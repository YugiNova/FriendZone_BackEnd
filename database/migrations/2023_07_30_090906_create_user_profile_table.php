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
        Schema::create('user_profile', function (Blueprint $table) {
            $table->uuid("id");
            $table->uuid("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->string("gender",10);
            $table->timestamp("dob");
            $table->string("cover_image_url",512)->nullable();
            $table->text("introduce")->nullable();
            $table->unsignedInteger("friends_count")->default(0);
            $table->unsignedInteger("followers_count")->default(0);
            $table->string("theme",10)->default("light");
            $table->string("color",10)->default("#CE4410");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile');
    }
};
