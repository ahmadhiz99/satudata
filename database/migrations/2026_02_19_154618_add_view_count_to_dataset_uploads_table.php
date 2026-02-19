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
        Schema::table('dataset_uploads', function (Blueprint $table) {
            $table->unsignedBigInteger('view_count')->default(0)->after('file_size');
        });
    }

    public function down(): void
    {
        Schema::table('dataset_uploads', function (Blueprint $table) {
            $table->dropColumn('view_count');
        });
    }
};
