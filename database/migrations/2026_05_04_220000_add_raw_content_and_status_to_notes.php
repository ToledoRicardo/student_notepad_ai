<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $needsSchemaUpdate = !Schema::hasColumn('notes', 'raw_content')
            || !Schema::hasColumn('notes', 'status');

        if ($needsSchemaUpdate) {
            Schema::table('notes', function (Blueprint $table) {
                if (!Schema::hasColumn('notes', 'raw_content')) {
                    $table->longText('raw_content')->nullable();
                }

                if (!Schema::hasColumn('notes', 'status')) {
                    $table->string('status')->default('pending');
                }
            });
        }

        if (Schema::hasColumn('notes', 'content') && Schema::hasColumn('notes', 'raw_content')) {
            DB::table('notes')
                ->whereNull('raw_content')
                ->update(['raw_content' => DB::raw('content')]);
        }

        if (Schema::hasColumn('notes', 'status')) {
            DB::table('notes')
                ->whereNull('status')
                ->update(['status' => 'pending']);
        }
    }

    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) {
            if (Schema::hasColumn('notes', 'raw_content')) {
                $table->dropColumn('raw_content');
            }

            if (Schema::hasColumn('notes', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
