<?php

use Learn\Database\DB;
use Learn\Database\Migrations\Migration;

return new class () implements Migration {
    public function up()
    {
        DB::statement('$UP');
    }

    public function down()
    {
        DB::statement('$DOWN');
    }
};
