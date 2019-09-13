<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateClassBulletinClassFile extends Migration {
    public function up() {
        if (Schema::hasTable('class_bulletin_class_file')){
            Schema::table('class_bulletin_class_file', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('class_bulletin_class_file');
                $newColumn = [
                    'id',
                    'class_bulletin_id',
                    'class_file_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('class_bulletin_class_file', 'class_bulletin_id')){
                    $table->bigInteger('class_bulletin_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('class_bulletin_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_bulletin_class_file, column: class_bulletin_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class_bulletin_class_file', 'class_file_id')){
                    $table->bigInteger('class_file_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('class_file_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_bulletin_class_file, column: class_file_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('class_bulletin_class_file', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('class_bulletin_id')->nullable();
                    $table->bigInteger('class_file_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('class_bulletin_class_file');
    }
}