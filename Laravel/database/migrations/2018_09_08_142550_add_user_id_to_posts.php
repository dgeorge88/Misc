<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //adds users id to a table
        Schema::table('posts', function($table){
            $table->integer('user_id');
        }); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //removes user id from table
        Schema::table('posts', function($table){
            $table->dropColumn('user_id');
        }); 
    }
}
