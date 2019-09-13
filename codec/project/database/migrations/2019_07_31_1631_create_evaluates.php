<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateEvaluates extends Migration {
    public function up() {
        if (Schema::hasTable('evaluates')){
            Schema::table('evaluates', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('evaluates');
                $newColumn = [
                    'id',
                    'user_id',
                    'grade',
                    'class_id',
                    'content',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('evaluates', 'user_id')){
                    $table->bigInteger('user_id');
                } else {
                    try {
                        // $table->bigInteger('user_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: evaluates, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('evaluates', 'grade')){
                    $table->integer('grade');
                } else {
                    try {
                        // $table->integer('grade')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: evaluates, column: grade, type: integer');
                    }
                }
                                if (!Schema::hasColumn('evaluates', 'class_id')){
                    $table->bigInteger('class_id');
                } else {
                    try {
                        // $table->bigInteger('class_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: evaluates, column: class_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('evaluates', 'content')){
                    $table->text('content');
                } else {
                    try {
                        // $table->text('content')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: evaluates, column: content, type: text');
                    }
                }
                                if (!Schema::hasColumn('evaluates', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('evaluates', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('user_id');
                    $table->integer('grade');
                    $table->bigInteger('class_id');
                    $table->text('content');
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('evaluates');
    }
}