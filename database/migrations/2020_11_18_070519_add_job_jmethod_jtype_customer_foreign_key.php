<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobJmethodJtypeCustomerForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Job', function (Blueprint $table) {
            $table->foreign('MethodID')->references('ID')->on('jmethod')->onDelete('cascade');
            $table->foreign('TypeID')->references('ID')->on('jtype')->onDelete('cascade');
            $table->foreign('CustomerID')->references('ID')->on('customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Job', function (Blueprint $table) {
            $table->dropForeign('job_MethodID_foreign');
            $table->dropForeign('job_TypeID_foreign');
            $table->dropForeign('job_CustomerID_foreign');
        });
    }
}
