<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateTasks extends Migration {
    public function up() {
        if (Schema::hasTable('tasks')){
            Schema::table('tasks', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('tasks');
                $newColumn = [
                    'id',
                    'shop_id',
                    'name',
                    'desc',
                    'start_date_time',
                    'end_date_time',
                    'id_delete',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('tasks', 'shop_id')){
                    $table->bigInteger('shop_id');
                } else {
                    try {
                        // $table->bigInteger('shop_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: tasks, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('tasks', 'name')){
                    $table->string('name', 100);
                } else {
                    try {
                        // $table->string('name', 100)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: tasks, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('tasks', 'desc')){
                    $table->text('desc')->nullable();
                } else {
                    try {
                        // $table->text('desc')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: tasks, column: desc, type: text');
                    }
                }
                                if (!Schema::hasColumn('tasks', 'start_date_time')){
                    $table->date('start_date_time')->nullable();
                } else {
                    try {
                        // $table->date('start_date_time')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: tasks, column: start_date_time, type: date');
                    }
                }
                                if (!Schema::hasColumn('tasks', 'end_date_time')){
                    $table->date('end_date_time')->nullable();
                } else {
                    try {
                        // $table->date('end_date_time')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: tasks, column: end_date_time, type: date');
                    }
                }
                                if (!Schema::hasColumn('tasks', 'id_delete')){
                    $table->integer('id_delete')->nullable();
                } else {
                    try {
                        // $table->integer('id_delete')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: tasks, column: id_delete, type: integer');
                    }
                }
                                if (!Schema::hasColumn('tasks', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('tasks', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('shop_id');
                    $table->string('name', 100);
                    $table->text('desc')->nullable();
                    $table->date('start_date_time')->nullable();
                    $table->date('end_date_time')->nullable();
                    $table->integer('id_delete')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('tasks');
    }
}