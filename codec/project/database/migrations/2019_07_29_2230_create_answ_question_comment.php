<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateAnswQuestionComment extends Migration {
    public function up() {
        if (Schema::hasTable('answ_question_comment')){
            Schema::table('answ_question_comment', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('answ_question_comment');
                $newColumn = [
                    'id',
                    'answ_question_id',
                    'user_id',
                    'class_id',
                    'content',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('answ_question_comment', 'answ_question_id')){
                    $table->bigInteger('answ_question_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('answ_question_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question_comment, column: answ_question_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('answ_question_comment', 'user_id')){
                    $table->bigInteger('user_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('user_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question_comment, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('answ_question_comment', 'class_id')){
                    $table->bigInteger('class_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('class_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question_comment, column: class_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('answ_question_comment', 'content')){
                    $table->text('content')->nullable();
                } else {
                    try {
                        // $table->text('content')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: answ_question_comment, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('answ_question_comment', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('answ_question_comment', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('answ_question_id')->nullable();
                    $table->bigInteger('user_id')->nullable();
                    $table->bigInteger('class_id')->nullable();
                    $table->text('content')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('answ_question_comment');
    }
}