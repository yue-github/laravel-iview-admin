<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateAttributeValueType extends Migration {
    public function up() {
        if (Schema::hasTable('attribute_value_type')){
            Schema::table('attribute_value_type', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('attribute_value_type');
                $newColumn = [
                    'id',
                    'attribute_id',
                    'attribute_value_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('attribute_value_type', 'attribute_id')){
                    $table->bigInteger('attribute_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('attribute_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute_value_type, column: attribute_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('attribute_value_type', 'attribute_value_id')){
                    $table->bigInteger('attribute_value_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('attribute_value_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute_value_type, column: attribute_value_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('attribute_value_type', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('attribute_id')->nullable();
                    $table->bigInteger('attribute_value_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('attribute_value_type');
    }
}