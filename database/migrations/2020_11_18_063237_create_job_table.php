<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateJobTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Job', function (Blueprint $table) {
            $table->bigIncrements('ID');
            $table->string('Name');
            $table->unsignedBigInteger('CustomerID');
            $table->unsignedBigInteger('TypeID');
            $table->date('StartDate');
            $table->boolean('RealJob')->default(1);
            $table->date('Deadline')->nullable();
            $table->unsignedBigInteger('Price');
            $table->unsignedBigInteger('PriceYen');
            $table->unsignedBigInteger('MethodID');
            $table->date('Paydate')->nullable()->default('1111-11-11');
            $table->date('FinishDate')->nullable()->default('1111-11-11');
            $table->boolean('Paid')->default(0);
            $table->text('Note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Job');
    }
}
