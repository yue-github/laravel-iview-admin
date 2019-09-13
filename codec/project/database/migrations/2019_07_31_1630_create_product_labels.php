<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProductLabels extends Migration {
    public function up() {
        if (Schema::hasTable('product_labels')){
            Schema::table('product_labels', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('product_labels');
                $newColumn = [
                    'id',
                    'product_id',
                    'label_id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('product_labels', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_labels, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('product_labels', 'label_id')){
                    $table->bigInteger('label_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('label_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: product_labels, column: label_id, type: bigInteger');
                    }
                }
                                });
            } else {
                Schema::create('product_labels', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('product_id')->nullable();
                    $table->bigInteger('label_id')->nullable();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('product_labels');
    }
}