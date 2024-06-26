<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stu_id')->nullable(); // Make stu_id nullable
            $table->foreign('stu_id')
                  ->references('id')
                  ->on('students')
                  ->onUpdate('cascade')
                  ->onDelete('set null'); // Set null on delete
            $table->string('book');
            $table->date('due_date')->nullable();
            $table->boolean('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('libraries');
    }
};
