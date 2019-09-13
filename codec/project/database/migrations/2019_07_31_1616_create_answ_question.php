<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateAnswQuestion extends Migration {
    public function up() {
        if (Schema::hasTable('answ_question')){
            Schema::table('answ_question', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('answ_question');
                $newColumn = [
                    'id',
                    'title',
                    'content',
                    'sort',
                    'user_id',
                    'product_id',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('answ_question', 'title')){
                    $table->string('title', 100)->nullable();
                } else {
                    try {
                        // $table->string('title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question, column: title, type: string');
                    }
                }
                                if (!Schema::hasColumn('answ_question', 'content')){
                    $table->text('content')->nullable();
                } else {
                    try {
                        // $table->text('content')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('answ_question', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question, column: sort, type: integer');
                    }
                }
                                if (!Schema::hasColumn('answ_question', 'user_id')){
                    $table->bigInteger('user_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('answ_question', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('answ_question', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('answ_question', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('title', 100)->nullable();
                    $table->text('content')->nullable();
                    $table->integer('sort')->nullable();
                    $table->bigInteger('user_id')->nullable();
                    $table->bigInteger('product_id')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('answ_question');
    }
}