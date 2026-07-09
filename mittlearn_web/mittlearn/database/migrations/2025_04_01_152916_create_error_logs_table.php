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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->string('error_message');
            $table->string('error_code');
            $table->string('error_file');
            $table->string('error_line');
            $table->text('error_trace')->nullable();
            $table->string('url')->nullable();
            $table->string('method')->nullable();
            $table->text('request_data')->nullable();
            $table->string('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
