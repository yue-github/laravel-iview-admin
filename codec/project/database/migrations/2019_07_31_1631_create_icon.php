<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateIcon extends Migration {
    public function up() {
        if (Schema::hasTable('icon')){
            Schema::table('icon', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('icon');
                $newColumn = [
                    'id',
                    'name',
                    'file_name',
                    'icon_type_id',
                    'state',
                    'type',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('icon', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: icon, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('icon', 'file_name')){
                    $table->string('file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: icon, column: file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('icon', 'icon_type_id')){
                    $table->bigInteger('icon_type_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('icon_type_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: icon, column: icon_type_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('icon', 'state')){
                    $table->string('state', 100)->nullable();
                } else {
                    try {
                        // $table->string('state', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: icon, column: state, type: string');
                    }
                }
                                if (!Schema::hasColumn('icon', 'type')){
                    $table->string('type', 100)->nullable();
                } else {
                    try {
                        // $table->string('type', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: icon, column: type, type: string');
                    }
                }
                                });
            } else {
                Schema::create('icon', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->string('file_name', 100)->nullable();
                    $table->bigInteger('icon_type_id')->nullable();
                    $table->string('state', 100)->nullable();
                    $table->string('type', 100)->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('icon');
    }
}