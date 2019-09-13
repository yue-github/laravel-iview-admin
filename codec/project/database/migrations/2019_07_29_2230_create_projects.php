<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProjects extends Migration {
    public function up() {
        if (Schema::hasTable('projects')){
            Schema::table('projects', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('projects');
                $newColumn = [
                    'id',
                    'shop_id',
                    'name',
                    'desc',
                    'start_time',
                    'end_time',
                    'label_id',
                    'onsale',
                    'sponsor',
                    'organizer',
                    'period',
                    'start_stydy_time',
                    'end_study_time',
                    'prportion',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('projects', 'shop_id')){
                    $table->bigInteger('shop_id');
                } else {
                    try {
                        // $table->bigInteger('shop_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('projects', 'name')){
                    $table->string('name', 100);
                } else {
                    try {
                        // $table->string('name', 100)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('projects', 'desc')){
                    $table->text('desc');
                } else {
                    try {
                        // $table->text('desc')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: desc, type: text');
                    }
                }
                                if (!Schema::hasColumn('projects', 'start_time')){
                    $table->date('start_time')->nullable();
                } else {
                    try {
                        // $table->date('start_time')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: start_time, type: date');
                    }
                }
                                if (!Schema::hasColumn('projects', 'end_time')){
                    $table->date('end_time')->nullable();
                } else {
                    try {
                        // $table->date('end_time')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: end_time, type: date');
                    }
                }
                                if (!Schema::hasColumn('projects', 'label_id')){
                    $table->bigInteger('label_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('label_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: label_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('projects', 'onsale')){
                    $table->integer('onsale')->nullable();
                } else {
                    try {
                        // $table->integer('onsale')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: onsale, type: integer');
                    }
                }
                                if (!Schema::hasColumn('projects', 'sponsor')){
                    $table->string('sponsor', 100)->nullable();
                } else {
                    try {
                        // $table->string('sponsor', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: sponsor, type: string');
                    }
                }
                                if (!Schema::hasColumn('projects', 'organizer')){
                    $table->string('organizer', 100)->nullable();
                } else {
                    try {
                        // $table->string('organizer', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: organizer, type: string');
                    }
                }
                                if (!Schema::hasColumn('projects', 'period')){
                    $table->integer('period')->nullable();
                } else {
                    try {
                        // $table->integer('period')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: period, type: integer');
                    }
                }
                                if (!Schema::hasColumn('projects', 'start_stydy_time')){
                    $table->date('start_stydy_time')->nullable();
                } else {
                    try {
                        // $table->date('start_stydy_time')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: start_stydy_time, type: date');
                    }
                }
                                if (!Schema::hasColumn('projects', 'end_study_time')){
                    $table->date('end_study_time')->nullable();
                } else {
                    try {
                        // $table->date('end_study_time')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: end_study_time, type: date');
                    }
                }
                                if (!Schema::hasColumn('projects', 'prportion')){
                    $table->integer('prportion')->nullable();
                } else {
                    try {
                        // $table->integer('prportion')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: projects, column: prportion, type: integer');
                    }
                }
                                if (!Schema::hasColumn('projects', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('projects', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('shop_id');
                    $table->string('name', 100);
                    $table->text('desc');
                    $table->date('start_time')->nullable();
                    $table->date('end_time')->nullable();
                    $table->bigInteger('label_id')->nullable();
                    $table->integer('onsale')->nullable();
                    $table->string('sponsor', 100)->nullable();
                    $table->string('organizer', 100)->nullable();
                    $table->integer('period')->nullable();
                    $table->date('start_stydy_time')->nullable();
                    $table->date('end_study_time')->nullable();
                    $table->integer('prportion')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('projects');
    }
}