<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;

class CreateDropTable extends Migration {
    public function up() {
        $oldTable = array();
        $tables = DB::select('show tables');
        foreach ($tables as $table) {
            foreach ($table as $key => $value)
                array_push($oldTable, $value);
        }
          $newTable = [ 
              'codec_users', 
          ]; 
        $dropTable = array_diff($oldTable, $newTable);
        foreach ($dropTable as $item) {
            if ($item != 'migrations')
            Schema::dropIfExists($item);
        }
    }
    public function down() {

    }
}