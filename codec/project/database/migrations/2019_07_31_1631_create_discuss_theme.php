<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateDiscussTheme extends Migration {
    public function up() {
        if (Schema::hasTable('discuss_theme')){
            Schema::table('discuss_theme', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('discuss_theme');
                $newColumn = [
                    'id',
                    'title',
                    'content',
                    'sort',
                    'resource_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('discuss_theme', 'title')){
                    $table->string('title', 100)->nullable();
                } else {
                    try {
                        // $table->string('title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: discuss_theme, column: title, type: string');
                    }
                }
                                if (!Schema::hasColumn('discuss_theme', 'content')){
                    $table->string('content', 100)->nullable();
                } else {
                    try {
                        // $table->string('content', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: discuss_theme, column: content, type: string');
                    }
                }
                                if (!Schema::hasColumn('discuss_theme', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: discuss_theme, column: sort, type: integer');
                    }
                }
                                if (!Schema::hasColumn('discuss_theme', 'resource_id')){
                    $table->bigInteger('resource_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('resource_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: discuss_theme, column: resource_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('discuss_theme', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('discuss_theme', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('title', 100)->nullable();
                    $table->string('content', 100)->nullable();
                    $table->integer('sort')->nullable();
                    $table->bigInteger('resource_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('discuss_theme');
    }
}