<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('tickets')) {
            return;
        }
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->date('date_created')->nullable();
            $table->longText('module');
            $table->text('issue');
            $table->string('screenshot_path')->nullable();
            $table->string('logged_by_user');
            $table->string('priority'); // low|medium|high|critical
            $table->string('status')->default('open'); // open|in_progress|resolved|closed
            $table->text('remarks_qd')->nullable();
            $table->text('further_remarks')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');

            $table->index('priority');
            $table->index('status');
            $table->index('created_by');
            $table->index('assigned_to');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
