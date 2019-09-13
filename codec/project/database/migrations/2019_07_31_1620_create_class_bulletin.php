<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateClassBulletin extends Migration {
    public function up() {
        if (Schema::hasTable('class_bulletin')){
            Schema::table('class_bulletin', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('class_bulletin');
                $newColumn = [
                    'id',
                    'class_id',
                    'bulletin',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('class_bulletin', 'class_id')){
                    $table->bigInteger('class_id');
                } else {
                    try {
                        // $table->bigInteger('class_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_bulletin, column: class_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('class_bulletin', 'bulletin')){
                    $table->string('bulletin', 500)->nullable();
                } else {
                    try {
                        // $table->string('bulletin', 500)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: class_bulletin, column: bulletin, type: string');
                    }
                }
                                if (!Schema::hasColumn('class_bulletin', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('class_bulletin', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('class_id');
                    $table->string('bulletin', 500)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('class_bulletin');
    }
}