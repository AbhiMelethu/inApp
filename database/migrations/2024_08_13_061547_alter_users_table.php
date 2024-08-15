<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name', '255')->nullable()->after('name');
            $table->string('phone','20')->nullable()->after('email_verified_at');
            $table->string('designation', '255')->nullable()->after('phone');
            $table->date('doj')->nullable()->after('designation');
            $table->tinyInteger('new')->length(1)->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
