<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWallpaperCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallpaper_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
        
        Schema::table('generated_images', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('resolution')->nullable();
            $table->string('color')->nullable();
            $table->text('tags')->nullable();
            
            $table->foreign('category_id')
                  ->references('id')
                  ->on('wallpaper_categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('generated_images', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->dropColumn('resolution');
            $table->dropColumn('color');
            $table->dropColumn('tags');
        });
        
        Schema::dropIfExists('wallpaper_categories');
    }
}