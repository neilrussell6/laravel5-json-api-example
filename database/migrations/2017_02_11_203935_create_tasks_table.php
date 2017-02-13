<?php

use App\Models\Task;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')
                ->references('id')->on('projects')
                ->onDelete('cascade');

            $table->string('name');
            $table->integer('status')->default(Task::STATUS_INCOMPLETE);
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
        Schema::drop('tasks');
    }
}
