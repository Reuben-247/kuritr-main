<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads_rates', function (Blueprint $table) {
            $table->id();
            $table->double('daily_amount', 10, 2);
            $table->string('estimated_views')->nullable();
            $table->string('ads_name')->nullable(); // index, political, sports_channel, user_login, other channels, 
            $table->string('ads_location')->nullable(); // home_page, channels, dashborad
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads_rates');
    }
}
