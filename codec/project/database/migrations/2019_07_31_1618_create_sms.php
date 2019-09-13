<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateSms extends Migration {
    public function up() {
        if (Schema::hasTable('sms')){
            Schema::table('sms', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('sms');
                $newColumn = [
                    'id',
                    'guid',
                    'code',
                    'create_time',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('sms', 'guid')){
                    $table->string('guid', 100)->nullable();
                } else {
                    try {
                        // $table->string('guid', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sms, column: guid, type: string');
                    }
                }
                                if (!Schema::hasColumn('sms', 'code')){
                    $table->string('code', 100)->nullable();
                } else {
                    try {
                        // $table->string('code', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sms, column: code, type: string');
                    }
                }
                                if (!Schema::hasColumn('sms', 'create_time')){
                    $table->string('create_time', 100)->nullable();
                } else {
                    try {
                        // $table->string('create_time', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: sms, column: create_time, type: string');
                    }
                }
                                });
            } else {
                Schema::create('sms', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('guid', 100)->nullable();
                    $table->string('code', 100)->nullable();
                    $table->string('create_time', 100)->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('sms');
    }
}