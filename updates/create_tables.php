<?php namespace RabLab\Email\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTables extends Migration
{

    public function up()
    {
        Schema::create('rablab_email_templates', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->unique();
            $table->string('slug')->index()->unique();
            $table->string('subject')->nullable();
            $table->string('filename')->index()->unique();
            $table->string('lang')->nullable();            
            $table->text('content')->nullable();
            $table->text('content_html')->nullable();
            $table->timestamps();
        });        
    }

    public function down()
    {
        Schema::drop('rablab_email_templates');
    }

}
