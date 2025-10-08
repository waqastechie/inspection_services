<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoadTestsTable extends Migration
{
    public function up()
    {
        Schema::create('load_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inspection_id');
            $table->string('duration_held');
            $table->string('two_points_diagonal');
            $table->string('four_points');
            $table->string('deflection')->nullable();
            $table->string('deformation')->nullable();
            $table->string('distance_from_ground')->nullable();
            $table->string('result');
            $table->timestamps();
            $table->foreign('inspection_id')->references('id')->on('inspections')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('load_tests');
    }
}
