<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('title');
            $table->longText('description')->nullable();
            $table->integer('images_count')->default(1);
            $table->boolean('completed')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
