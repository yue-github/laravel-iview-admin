<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateShops extends Migration {
    public function up() {
        if (Schema::hasTable('shops')){
            Schema::table('shops', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('shops');
                $newColumn = [
                    'id',
                    'name',
                    'owner_id',
                    'state',
                    'is_project',
                    'url',
                    'platform_name',
                    'mechanism_name',
                    'region',
                    'is_invoice',
                    'agreement',
                    'is_cer',
                    'logo_header_file_name',
                    'logo_footer_file_name',
                    'customer',
                    'customer2',
                    'footer',
                    'alipay',
                    'wxpay',
                    'unionpay',
                    'is_auth',
                    'marquee',
                    'title',
                    'sidebar_type',
                    'is_order',
                    'is_shoppingcart',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('shops', 'name')){
                    $table->string('name', 100);
                } else {
                    try {
                        // $table->string('name', 100)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'owner_id')){
                    $table->bigInteger('owner_id');
                } else {
                    try {
                        // $table->bigInteger('owner_id')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: owner_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('shops', 'state')){
                    $table->integer('state')->default('0');
                } else {
                    try {
                        // $table->integer('state')->default('0')->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: state, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'is_project')){
                    $table->integer('is_project')->nullable();
                } else {
                    try {
                        // $table->integer('is_project')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: is_project, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'url')){
                    $table->text('url')->nullable();
                } else {
                    try {
                        // $table->text('url')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: url, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'platform_name')){
                    $table->string('platform_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('platform_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: platform_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'mechanism_name')){
                    $table->string('mechanism_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('mechanism_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: mechanism_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'region')){
                    $table->string('region', 100)->nullable();
                } else {
                    try {
                        // $table->string('region', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: region, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'is_invoice')){
                    $table->integer('is_invoice')->nullable();
                } else {
                    try {
                        // $table->integer('is_invoice')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: is_invoice, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'agreement')){
                    $table->text('agreement')->nullable();
                } else {
                    try {
                        // $table->text('agreement')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: agreement, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'is_cer')){
                    $table->integer('is_cer')->nullable();
                } else {
                    try {
                        // $table->integer('is_cer')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: is_cer, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'logo_header_file_name')){
                    $table->string('logo_header_file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('logo_header_file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: logo_header_file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'logo_footer_file_name')){
                    $table->string('logo_footer_file_name', 100)->nullable();
                } else {
                    try {
                        // $table->string('logo_footer_file_name', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: logo_footer_file_name, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'customer')){
                    $table->text('customer')->nullable();
                } else {
                    try {
                        // $table->text('customer')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: customer, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'customer2')){
                    $table->text('customer2')->nullable();
                } else {
                    try {
                        // $table->text('customer2')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: customer2, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'footer')){
                    $table->text('footer')->nullable();
                } else {
                    try {
                        // $table->text('footer')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: footer, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'alipay')){
                    $table->text('alipay')->nullable();
                } else {
                    try {
                        // $table->text('alipay')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: alipay, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'wxpay')){
                    $table->text('wxpay')->nullable();
                } else {
                    try {
                        // $table->text('wxpay')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: wxpay, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'unionpay')){
                    $table->text('unionpay')->nullable();
                } else {
                    try {
                        // $table->text('unionpay')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: unionpay, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'is_auth')){
                    $table->integer('is_auth')->nullable();
                } else {
                    try {
                        // $table->integer('is_auth')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: is_auth, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'marquee')){
                    $table->text('marquee')->nullable();
                } else {
                    try {
                        // $table->text('marquee')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: marquee, type: text');
                    }
                }
                                if (!Schema::hasColumn('shops', 'title')){
                    $table->string('title', 100)->nullable();
                } else {
                    try {
                        // $table->string('title', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: title, type: string');
                    }
                }
                                if (!Schema::hasColumn('shops', 'sidebar_type')){
                    $table->integer('sidebar_type')->nullable();
                } else {
                    try {
                        // $table->integer('sidebar_type')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: sidebar_type, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'is_order')){
                    $table->integer('is_order')->nullable();
                } else {
                    try {
                        // $table->integer('is_order')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: is_order, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'is_shoppingcart')){
                    $table->integer('is_shoppingcart')->nullable();
                } else {
                    try {
                        // $table->integer('is_shoppingcart')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: shops, column: is_shoppingcart, type: integer');
                    }
                }
                                if (!Schema::hasColumn('shops', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('shops', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('name', 100);
                    $table->bigInteger('owner_id');
                    $table->integer('state')->default('0');
                    $table->integer('is_project')->nullable();
                    $table->text('url')->nullable();
                    $table->string('platform_name', 100)->nullable();
                    $table->string('mechanism_name', 100)->nullable();
                    $table->string('region', 100)->nullable();
                    $table->integer('is_invoice')->nullable();
                    $table->text('agreement')->nullable();
                    $table->integer('is_cer')->nullable();
                    $table->string('logo_header_file_name', 100)->nullable();
                    $table->string('logo_footer_file_name', 100)->nullable();
                    $table->text('customer')->nullable();
                    $table->text('customer2')->nullable();
                    $table->text('footer')->nullable();
                    $table->text('alipay')->nullable();
                    $table->text('wxpay')->nullable();
                    $table->text('unionpay')->nullable();
                    $table->integer('is_auth')->nullable();
                    $table->text('marquee')->nullable();
                    $table->string('title', 100)->nullable();
                    $table->integer('sidebar_type')->nullable();
                    $table->integer('is_order')->nullable();
                    $table->integer('is_shoppingcart')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('shops');
    }
}