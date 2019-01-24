<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemsToDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->integer('price')->after('bought_at')->nullable();
            $table->date('rented_at')->after('user_id')->nullable();
            $table->integer('condition')->after('rented_at')->nullable();
            $table->string('rental_number')->after('condition')->nullable();
            $table->text('memo')->after('rental_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('rented_at');
            $table->dropColumn('condition');
            $table->dropColumn('rental_number');
            $table->dropColumn('memo');
        });
    }
}
