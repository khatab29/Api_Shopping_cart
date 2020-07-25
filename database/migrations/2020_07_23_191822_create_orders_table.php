<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('unique_id');
            $table->foreignId('user_id')
            ->nullable()
            ->constrained()
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->string('name');
            $table->text('address');
            $table->integer('shipment_type')->default(0);
            $table->integer('total_price');
            $table->string('status')->default('pending');
            $table->float('shipment_fees')->nullable();
            $table->string('discount_code')->nullable();
            $table->integer('discount_amount')->nullable();
            $table->float('final_price')->nullable();
            $table->text('confirmation_code')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
