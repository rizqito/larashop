<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PenyesuaianTableUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->string("username")->unique();
            $table->string("roles");
            $table->text("address");
            $table->string("phone");
            $table->string("avatar");
            $table->enum("status",["ACTIVE","INACTIVE"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropCollumn("username");
            $table->dropCollumn("roles");
            $table->dropCollumn("address");
            $table->dropCollumn("phone");
            $table->dropCollumn("avatar");
            $table->dropCollumn("status");
        });
    }
}
