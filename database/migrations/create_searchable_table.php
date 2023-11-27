<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create(config('larasearch.table'), function (Blueprint $table) {
            $table->string('searchable_type')->index();
            $table->string('searchable_id')->index();
            $table->string('column')->index();
            $table->string('value')->nullable()->fulltext();
        });
    }
};
