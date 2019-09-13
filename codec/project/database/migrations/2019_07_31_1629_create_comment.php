<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateComment extends Migration {
    public function up() {
        if (Schema::hasTable('comment')){
            Schema::table('comment', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('comment');
                $newColumn = [
                    'id',
                    'discuss_theme_id',
                    'user_id',
                    'content',
                    'class_id',
                    'product_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('comment', 'discuss_theme_id')){
                    $table->bigInteger('discuss_theme_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('discuss_theme_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: comment, column: discuss_theme_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('comment', 'user_id')){
                    $table->bigInteger('user_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: comment, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('comment', 'content')){
                    $table->text('content')->nullable();
                } else {
                    try {
                        // $table->text('content')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: comment, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('comment', 'class_id')){
                    $table->bigInteger('class_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('class_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: comment, column: class_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('comment', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: comment, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('comment', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('comment', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('discuss_theme_id')->nullable();
                    $table->bigInteger('user_id')->nullable();
                    $table->text('content')->nullable();
                    $table->bigInteger('class_id')->nullable();
                    $table->bigInteger('product_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('comment');
    }
}