<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'user_id')) {
                $table->string('user_id')->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('users', 'telegram')) {
                $table->string('telegram')->nullable()->after('phone');
            }

            if (! Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('telegram');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['user_id', 'telegram', 'address'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
