<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProjectTasks extends Migration {
    public function up() {
        if (Schema::hasTable('project_tasks')){
            Schema::table('project_tasks', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('project_tasks');
                $newColumn = [
                    'id',
                    'project_id',
                    'task_id',
                    'sort',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('project_tasks', 'project_id')){
                    $table->bigInteger('project_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('project_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: project_tasks, column: project_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('project_tasks', 'task_id')){
                    $table->bigInteger('task_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('task_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: project_tasks, column: task_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('project_tasks', 'sort')){
                    $table->bigInteger('sort')->nullable();
                } else {
                    try {
                        // $table->bigInteger('sort')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: project_tasks, column: sort, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('project_tasks', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('project_id')->nullable();
                    $table->bigInteger('task_id')->nullable();
                    $table->bigInteger('sort')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('project_tasks');
    }
}