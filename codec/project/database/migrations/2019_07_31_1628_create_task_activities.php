<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateTaskActivities extends Migration {
    public function up() {
        if (Schema::hasTable('task_activities')){
            Schema::table('task_activities', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('task_activities');
                $newColumn = [
                    'id',
                    'task_id',
                    'activity_id',
                    'sort',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('task_activities', 'task_id')){
                    $table->bigInteger('task_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('task_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: task_activities, column: task_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('task_activities', 'activity_id')){
                    $table->bigInteger('activity_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('activity_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: task_activities, column: activity_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('task_activities', 'sort')){
                    $table->integer('sort')->nullable();
                } else {
                    try {
                        // $table->integer('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: task_activities, column: sort, type: integer');
                    }
                }
                                });
            } else {
                Schema::create('task_activities', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('task_id')->nullable();
                    $table->bigInteger('activity_id')->nullable();
                    $table->integer('sort')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('task_activities');
    }
}