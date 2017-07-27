<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppendQuestionsCountUserExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('user_extras', 'questions_count')) {
            Schema::table('user_extras', function (Blueprint $table) {
                $table->integer('questions_count')->unsigned()->nullable()->default(0)->comment('用户提问数统计');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('user_extras', 'questions_count')) {
            Schema::table('user_extras', function (Blueprint $table) {
                $table->dropColumn('questions_count');
            });
        }
    }
}
