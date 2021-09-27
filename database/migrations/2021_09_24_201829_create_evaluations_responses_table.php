<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationsResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('student_concerned_id');
            $table->integer('evaluation_id');
            $table->json('responses');
            $table->string('rating')->nullable();
            $table->string('state')->default('waiting');
            
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
        Schema::dropIfExists('evaluations_responses');
        
    }
}
