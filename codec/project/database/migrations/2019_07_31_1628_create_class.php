<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateClass extends Migration {
    public function up() {
        if (Schema::hasTable('class')){
            Schema::table('class', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('class');
                $newColumn = [
                    'id',
                    'name',
                    'shop_id',
                    'product_id',
                    'task_id',
                    'activitie_id',
                    'state',
                    'start_time',
                    'instructor_id',
                    'evaluates',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('class', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('class', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class', 'task_id')){
                    $table->bigInteger('task_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('task_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: task_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class', 'activitie_id')){
                    $table->bigInteger('activitie_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('activitie_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: activitie_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class', 'state')){
                    $table->integer('state')->default('0')->nullable();
                } else {
                    try {
                        // $table->integer('state')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: state, type: integer');
                    }
                }
                                if (!Schema::hasColumn('class', 'start_time')){
                    $table->string('start_time', 100)->nullable();
                } else {
                    try {
                        // $table->string('start_time', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: start_time, type: string');
                    }
                }
                                if (!Schema::hasColumn('class', 'instructor_id')){
                    $table->bigInteger('instructor_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('instructor_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: instructor_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class', 'evaluates')){
                    $table->text('evaluates')->nullable();
                } else {
                    try {
                        // $table->text('evaluates')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class, column: evaluates, type: text');
                    }
                }
                                if (!Schema::hasColumn('class', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('class', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->bigInteger('product_id')->nullable();
                    $table->bigInteger('task_id')->nullable();
                    $table->bigInteger('activitie_id')->nullable();
                    $table->integer('state')->default('0')->nullable();
                    $table->string('start_time', 100)->nullable();
                    $table->bigInteger('instructor_id')->nullable();
                    $table->text('evaluates')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('class');
    }
}