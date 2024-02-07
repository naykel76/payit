<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPlatformsTable extends Migration
{
    public function up()
    {
        Schema::create('payment_platforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('platform_name', 50)->unique(); // used to resolve service, must be the same as
            $table->string('method')->unique();
            $table->boolean('active')->default(true);
            // $table->boolean('subscriptions_enabled')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_platforms');
    }
}
