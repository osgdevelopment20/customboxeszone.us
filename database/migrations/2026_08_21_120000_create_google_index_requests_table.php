<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_index_requests', function (Blueprint $table) {
            $table->id();
            $table->text('url');
            $table->char('url_hash', 40);
            $table->string('type', 20)->default('URL_UPDATED');
            $table->string('source', 40)->nullable();
            $table->string('status', 20)->default('pending');
            $table->unsignedSmallInteger('http_code')->nullable();
            $table->string('batch_id', 40)->nullable();
            $table->timestamp('notify_time')->nullable();
            $table->text('message')->nullable();
            $table->longText('response')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->index('url_hash');
            $table->index('batch_id');
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('google_index_requests');
    }
};
