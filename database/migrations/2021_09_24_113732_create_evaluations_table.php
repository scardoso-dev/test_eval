<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->integer('creator_id');
            $table->string('type');
            $table->string('title');
            $table->string('desc');
            $table->string('text');
            $table->json('evaluation_responses');
            $table->json('students_concerned');
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
        Schema::dropIfExists('evaluations');
    }
}
