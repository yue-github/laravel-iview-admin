<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateClassFile extends Migration {
    public function up() {
        if (Schema::hasTable('class_file')){
            Schema::table('class_file', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('class_file');
                $newColumn = [
                    'id',
                    'id',
                    'class_id',
                    'file_name',
                    'file_as_name',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                                if (!Schema::hasColumn('class_file', 'class_id')){
                    $table->bigInteger('class_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('class_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_file, column: class_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class_file', 'file_name')){
                    $table->string('file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_file, column: file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('class_file', 'file_as_name')){
                    $table->string('file_as_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('file_as_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_file, column: file_as_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('class_file', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('class_file', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('class_id')->nullable();
                    $table->string('file_name', 100)->nullable();
                    $table->string('file_as_name', 100)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('class_file');
    }
}