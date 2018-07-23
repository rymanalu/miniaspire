<?php

use App\Loan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('duration');
            $table->unsignedDecimal('rate', 5);
            $table->unsignedDecimal('amount', 15);
            $table->unsignedDecimal('fee', 15);
            $table->unsignedDecimal('total', 15);
            $table->unsignedTinyInteger('status')->default(Loan::STATUS_UNPAID);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
