<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $migrate = false;
        while (!$migrate) {
            if (Schema::hasTable('users')) {
                Schema::create('tickets', function (Blueprint $table) {
                    $table->id();
                    $table->string('title');
                    $table->enum('type', config('enums.ticket_types'));
                    $table->uuid('creator')->nullable();
                    $table->foreign('creator')->references('id')->on('users')->onDelete('cascade');
                    $table->json('content');
                    $table->enum('status', config('enums.item_status'))->default(config('enums.item_status.PENDING'));
                    $table->timestamps();
                });
                $migrate = true;
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
