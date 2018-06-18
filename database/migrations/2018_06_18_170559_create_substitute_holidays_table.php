<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreateSubstituteHolidaysTable extends Migration
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('substitute_holidays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->date('workday')->nullable();
            $table->date('holiday')->nullable();
            $table->text('memo')->nullable();
            $table->softDeletes();
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
        Schema::drop('substitute_holidays');
    }
}
