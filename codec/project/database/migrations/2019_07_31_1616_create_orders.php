<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateOrders extends Migration {
    public function up() {
        if (Schema::hasTable('orders')){
            Schema::table('orders', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('orders');
                $newColumn = [
                    'id',
                    'owner_id',
                    'pay_type',
                    'state',
                    'total',
                    'invoice_title',
                    'tax_no',
                    'invoice_type',
                    'invoice_email',
                    'invoice_address',
                    'out_trade_no',
                    'code',
                    'pay_date',
                    'cancel_date',
                    'shop_id',
                    'invoice_state',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('orders', 'owner_id')){
                    $table->bigInteger('owner_id');
                } else {
                    try {
                        // $table->bigInteger('owner_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: owner_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('orders', 'pay_type')){
                    $table->integer('pay_type');
                } else {
                    try {
                        // $table->integer('pay_type')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: pay_type, type: integer');
                    }
                }
                                if (!Schema::hasColumn('orders', 'state')){
                    $table->integer('state')->default('0');
                } else {
                    try {
                        // $table->integer('state')->default('0')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: state, type: integer');
                    }
                }
                                if (!Schema::hasColumn('orders', 'total')){
                    $table->decimal('total', 13, 2)->default('0')->nullable();
                } else {
                    try {
                        // $table->decimal('total', 13, 2)->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: total, type: decimal');
                    }
                }
                                if (!Schema::hasColumn('orders', 'invoice_title')){
                    $table->string('invoice_title', 100)->nullable();
                } else {
                    try {
                        // $table->string('invoice_title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: invoice_title, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'tax_no')){
                    $table->string('tax_no', 100)->nullable();
                } else {
                    try {
                        // $table->string('tax_no', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: tax_no, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'invoice_type')){
                    $table->string('invoice_type', 100)->nullable();
                } else {
                    try {
                        // $table->string('invoice_type', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: invoice_type, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'invoice_email')){
                    $table->string('invoice_email', 200)->nullable();
                } else {
                    try {
                        // $table->string('invoice_email', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: invoice_email, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'invoice_address')){
                    $table->string('invoice_address', 200)->nullable();
                } else {
                    try {
                        // $table->string('invoice_address', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: invoice_address, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'out_trade_no')){
                    $table->string('out_trade_no', 200)->nullable();
                } else {
                    try {
                        // $table->string('out_trade_no', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: out_trade_no, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'code')){
                    $table->string('code', 200)->nullable();
                } else {
                    try {
                        // $table->string('code', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: code, type: string');
                    }
                }
                                if (!Schema::hasColumn('orders', 'pay_date')){
                    $table->dateTime('pay_date')->nullable();
                } else {
                    try {
                        // $table->dateTime('pay_date')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: pay_date, type: dateTime');
                    }
                }
                                if (!Schema::hasColumn('orders', 'cancel_date')){
                    $table->dateTime('cancel_date')->nullable();
                } else {
                    try {
                        // $table->dateTime('cancel_date')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: cancel_date, type: dateTime');
                    }
                }
                                if (!Schema::hasColumn('orders', 'shop_id')){
                    $table->bigInteger('shop_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: shop_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('orders', 'invoice_state')){
                    $table->integer('invoice_state')->default('0')->nullable();
                } else {
                    try {
                        // $table->integer('invoice_state')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: orders, column: invoice_state, type: integer');
                    }
                }
                                if (!Schema::hasColumn('orders', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('orders', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->bigInteger('owner_id');
                    $table->integer('pay_type');
                    $table->integer('state')->default('0');
                    $table->decimal('total', 13, 2)->default('0')->nullable();
                    $table->string('invoice_title', 100)->nullable();
                    $table->string('tax_no', 100)->nullable();
                    $table->string('invoice_type', 100)->nullable();
                    $table->string('invoice_email', 200)->nullable();
                    $table->string('invoice_address', 200)->nullable();
                    $table->string('out_trade_no', 200)->nullable();
                    $table->string('code', 200)->nullable();
                    $table->dateTime('pay_date')->nullable();
                    $table->dateTime('cancel_date')->nullable();
                    $table->bigInteger('shop_id')->nullable();
                    $table->integer('invoice_state')->default('0')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('orders');
    }
}