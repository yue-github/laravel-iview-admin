<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateAttribute extends Migration {
    public function up() {
        if (Schema::hasTable('attribute')){
            Schema::table('attribute', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('attribute');
                $newColumn = [
                    'id',
                    'name',
                    'shop_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('attribute', 'name')){
                    $table->string('name', 100)->nullable();
                } else {
                    try {
                        // $table->string('name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('attribute', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: attribute, column: shop_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('attribute', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100)->nullable();
                    $table->bigInteger('shop_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('attribute');
    }
}