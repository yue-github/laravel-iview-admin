<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateFile extends Migration {
    public function up() {
        if (Schema::hasTable('file')){
            Schema::table('file', function (Blueprint $table) {
                $oldColumn = Schema::getColumnListing('file');
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
                Schema::create('file', function (Blueprint $table) {
                    $table->bigIncrements('id');
                });
            }
        }
    public function down() {
        Schema::dropIfExists('file');
    }
}