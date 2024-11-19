<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('sessions', function (Blueprint $table) {
        $table->string('ip_address', 45)->nullable();  // for IPv6 compatibility
        $table->string('user_agent')->nullable();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('sessions', function (Blueprint $table) {
        $table->dropColumn(['ip_address', 'user_agent']);
    });
}
};
