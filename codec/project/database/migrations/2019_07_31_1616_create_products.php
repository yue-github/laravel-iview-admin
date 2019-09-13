<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateProducts extends Migration {
    public function up() {
        if (Schema::hasTable('products')){
            Schema::table('products', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('products');
                $newColumn = [
                    'id',
                    'shop_id',
                    'project_id',
                    'name',
                    'desc',
                    'period',
                    'teacher',
                    'teacher_intro',
                    'image',
                    'price',
                    'onsale',
                    'is_auth',
                    'attr',
                    'cer_year',
                    'cer_industry',
                    'is_project',
                    'year',
                    'created_at',
                    'created_at',
                    'updated_at'
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                if (!Schema::hasColumn('products', 'shop_id')){
                    $table->integer('shop_id')->nullable();
                } else {
                    try {
                        // $table->integer('shop_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: shop_id, type: integer');
                    }
                }
                                if (!Schema::hasColumn('products', 'project_id')){
                    $table->bigInteger('project_id')->nullable();
                } else {
                    try {
                        // $table->bigInteger('project_id')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: project_id, type: bigInteger');
                    }
                }
                                if (!Schema::hasColumn('products', 'name')){
                    $table->string('name', 100);
                } else {
                    try {
                        // $table->string('name', 100)->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: name, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'desc')){
                    $table->text('desc')->nullable();
                } else {
                    try {
                        // $table->text('desc')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: desc, type: text');
                    }
                }
                                if (!Schema::hasColumn('products', 'period')){
                    $table->string('period', 100)->nullable();
                } else {
                    try {
                        // $table->string('period', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: period, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'teacher')){
                    $table->string('teacher', 100)->nullable();
                } else {
                    try {
                        // $table->string('teacher', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: teacher, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'teacher_intro')){
                    $table->text('teacher_intro')->nullable();
                } else {
                    try {
                        // $table->text('teacher_intro')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: teacher_intro, type: text');
                    }
                }
                                if (!Schema::hasColumn('products', 'image')){
                    $table->string('image', 200)->nullable();
                } else {
                    try {
                        // $table->string('image', 200)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: image, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'price')){
                    $table->decimal('price', 13, 2)->default('0')->nullable();
                } else {
                    try {
                        // $table->decimal('price', 13, 2)->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: price, type: decimal');
                    }
                }
                                if (!Schema::hasColumn('products', 'onsale')){
                    $table->boolean('onsale')->default('0')->nullable();
                } else {
                    try {
                        // $table->boolean('onsale')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: onsale, type: boolean');
                    }
                }
                                if (!Schema::hasColumn('products', 'is_auth')){
                    $table->boolean('is_auth')->default('0')->nullable();
                } else {
                    try {
                        // $table->boolean('is_auth')->default('0')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: is_auth, type: boolean');
                    }
                }
                                if (!Schema::hasColumn('products', 'attr')){
                    $table->string('attr', 100)->nullable();
                } else {
                    try {
                        // $table->string('attr', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: attr, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'cer_year')){
                    $table->string('cer_year', 100)->nullable();
                } else {
                    try {
                        // $table->string('cer_year', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: cer_year, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'cer_industry')){
                    $table->string('cer_industry', 100)->nullable();
                } else {
                    try {
                        // $table->string('cer_industry', 100)->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: cer_industry, type: string');
                    }
                }
                                if (!Schema::hasColumn('products', 'is_project')){
                    $table->integer('is_project')->nullable();
                } else {
                    try {
                        // $table->integer('is_project')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: is_project, type: integer');
                    }
                }
                                if (!Schema::hasColumn('products', 'year')){
                    $table->integer('year')->nullable();
                } else {
                    try {
                        // $table->integer('year')->nullable()->change();
                    } catch (\Exception $e) {
                        Log::info('更新字段出错,table: products, column: year, type: integer');
                    }
                }
                                                if (!Schema::hasColumn('products', 'created_at'))
                    $table->timestamps();
                });
            } else {
                Schema::create('products', function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->integer('shop_id')->nullable();
                    $table->bigInteger('project_id')->nullable();
                    $table->string('name', 100);
                    $table->text('desc')->nullable();
                    $table->string('period', 100)->nullable();
                    $table->string('teacher', 100)->nullable();
                    $table->text('teacher_intro')->nullable();
                    $table->string('image', 200)->nullable();
                    $table->decimal('price', 13, 2)->default('0')->nullable();
                    $table->boolean('onsale')->default('0')->nullable();
                    $table->boolean('is_auth')->default('0')->nullable();
                    $table->string('attr', 100)->nullable();
                    $table->string('cer_year', 100)->nullable();
                    $table->string('cer_industry', 100)->nullable();
                    $table->integer('is_project')->nullable();
                    $table->integer('year')->nullable();
                    $table->timestamps();
                });
            }
        }
    public function down() {
        Schema::dropIfExists('products');
    }
}