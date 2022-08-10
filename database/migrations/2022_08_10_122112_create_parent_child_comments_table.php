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
        Schema::create('parent_child_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_comment_id');
            $table->unsignedBigInteger('child_comment_id');
            $table->foreign('parent_comment_id')->references('id')->on('comments');
            $table->foreign('child_comment_id')->references('id')->on('comments');
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
        Schema::dropIfExists('parent_child_comments');
    }
};
