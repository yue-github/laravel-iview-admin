<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateClassUsers extends Migration {
    public function up() {
        if (Schema::hasTable('class_users')){
            Schema::table('class_users', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('class_users');
                $newColumn = [
                    'id',
                    'class_id',
                    'user_id',
                    'purchased_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('class_users', 'class_id')){
                    $table->bigInteger('class_id');
                } else {
                    try {
                        // $table->bigInteger('class_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_users, column: class_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class_users', 'user_id')){
                    $table->bigInteger('user_id');
                } else {
                    try {
                        // $table->bigInteger('user_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_users, column: user_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class_users', 'purchased_id')){
                    $table->bigInteger('purchased_id');
                } else {
                    try {
                        // $table->bigInteger('purchased_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_users, column: purchased_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('class_users', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('class_id');
                    $table->bigInteger('user_id');
                    $table->bigInteger('purchased_id');
                });
            }
        }
    public function down() {
        Schema::dropIfExists('class_users');
    }
}