<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_platforms', function (Blueprint $table) {
            $table->id();
            $table->string('platform_name', 50)->unique();
            $table->string('method')->unique();
            $table->boolean('active')->default(true);
            // $table->boolean('subscriptions_enabled')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_platforms');
    }
};
