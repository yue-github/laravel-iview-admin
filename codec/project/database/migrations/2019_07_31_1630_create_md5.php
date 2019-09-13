<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateMd5 extends Migration {
    public function up() {
        if (Schema::hasTable('md5')){
            Schema::table('md5', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('md5');
                $newColumn = [
                    'id',
                    'id',
                ];
                $dropColumn = array_diff($oldColumn, $newColumn);
                foreach ($dropColumn as $column) {
                    $table->dropColumn($column);
                }
                                });
            } else {
                Schema::create('md5', function (Blueprint $table) {
                    $table->bigIncrements('id');
                });
            }
        }
    public function down() {
        Schema::dropIfExists('md5');
    }
}