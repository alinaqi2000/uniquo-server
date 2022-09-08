<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competitions', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->foreignId("organizer_id")->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("winner_id")->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId("category_id")->nullable()->constrained()->onUpdate('cascade')->nullOnDelete();

            $table->string("title", 255);
            $table->string("slug", 500);
            $table->double("cost");
            $table->double("entry_fee");
            $table->double("prize_money");
            $table->integer("participants_allowed");
            $table->timestamp("announcement_date");

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
        Schema::dropIfExists('competitions');
    }
}