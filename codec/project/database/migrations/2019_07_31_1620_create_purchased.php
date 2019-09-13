<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreatePurchased extends Migration {
    public function up() {
        if (Schema::hasTable('purchased')){
            Schema::table('purchased', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('purchased');
                $newColumn = [
                    'id',
                    'order_id',
                    'owner_id',
                    'product_id',
                    'product_name',
                    'price',
                    'score',
                    'progress',
                    'rate',
                    'private',
                    'cer_year',
                    'is_first',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('purchased', 'order_id')){
                    $table->bigInteger('order_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('order_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: order_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'owner_id')){
                    $table->bigInteger('owner_id');
                } else {
                    try {
                        // $table->bigInteger('owner_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: owner_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'product_id')){
                    $table->bigInteger('product_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('product_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: product_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'product_name')){
                    $table->string('product_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('product_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: product_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'price')){
                    $table->decimal('price', 13, 2);
                } else {
                    try {
                        // $table->decimal('price', 13, 2)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: price, type: decimal');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'score')){
                    $table->integer('score')->nullable();
                } else {
                    try {
                        // $table->integer('score')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: score, type: integer');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'progress')){
                    $table->float('progress')->nullable();
                } else {
                    try {
                        // $table->float('progress')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: progress, type: float');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'rate')){
                    $table->text('rate')->nullable();
                } else {
                    try {
                        // $table->text('rate')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: rate, type: text');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'private')){
                    $table->text('private')->nullable();
                } else {
                    try {
                        // $table->text('private')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: private, type: text');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'cer_year')){
                    $table->string('cer_year', 100)->default('0')->nullable();
                } else {
                    try {
                        // $table->string('cer_year', 100)->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: cer_year, type: string');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'is_first')){
                    $table->string('is_first', 100)->nullable();
                } else {
                    try {
                        // $table->string('is_first', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: purchased, column: is_first, type: string');
                    }
                }
                                if (!Schema::hasColumn('purchased', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('purchased', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('order_id')->nullable();
                    $table->bigInteger('owner_id');
                    $table->bigInteger('product_id')->nullable();
                    $table->string('product_name', 100)->nullable();
                    $table->decimal('price', 13, 2);
                    $table->integer('score')->nullable();
                    $table->float('progress')->nullable();
                    $table->text('rate')->nullable();
                    $table->text('private')->nullable();
                    $table->string('cer_year', 100)->default('0')->nullable();
                    $table->string('is_first', 100)->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('purchased');
    }
}