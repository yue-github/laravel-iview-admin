<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateIconType extends Migration {
    public function up() {
        if (Schema::hasTable('icon_type')){
            Schema::table('icon_type', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('icon_type');
                $newColumn = [
                    'id',
                    'type',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('icon_type', 'type')){
                    $table->string('type', 100)->nullable();
                } else {
                    try {
                        // $table->string('type', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: icon_type, column: type, type: string');
                    }
                }
                                });
            } else {
                Schema::create('icon_type', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('type', 100)->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('icon_type');
    }
}