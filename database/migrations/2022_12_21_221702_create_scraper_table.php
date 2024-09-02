<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScraperTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liturgies', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->text('liturgy1');
            $table->text('liturgy2')->nullable();
            $table->text('liturgypsalms');
            $table->text('liturgygospel');
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
        Schema::dropIfExists('liturgies');
    }
}
