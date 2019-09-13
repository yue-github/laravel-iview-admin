<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePackageAttributeValue extends Migration {
    public function up() {
        if (Schema::hasTable('package_attribute_value')){
            Schema::table('package_attribute_value', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('package_attribute_value');
                $newColumn = [
                    'id',
                    'package_id',
                    'attribute_value_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('package_attribute_value', 'package_id')){
                    $table->bigInteger('package_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('package_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package_attribute_value, column: package_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('package_attribute_value', 'attribute_value_id')){
                    $table->bigInteger('attribute_value_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('attribute_value_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: package_attribute_value, column: attribute_value_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('package_attribute_value', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('package_id')->nullable();
                    $table->bigInteger('attribute_value_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('package_attribute_value');
    }
}