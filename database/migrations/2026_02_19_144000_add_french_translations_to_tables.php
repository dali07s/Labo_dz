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
        Schema::table('analyses', function (Blueprint $table) {
            $table->string('name_fr')->nullable()->after('name');
            $table->text('description_fr')->nullable()->after('description');
            $table->text('preparation_instructions_fr')->nullable()->after('preparation_instructions');
            $table->string('duration_fr')->nullable()->after('duration');
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->text('question_fr')->nullable()->after('question');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->text('text_fr')->nullable()->after('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analyses', function (Blueprint $table) {
            $table->dropColumn(['name_fr', 'description_fr', 'preparation_instructions_fr', 'duration_fr']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('question_fr');
        });

        Schema::table('options', function (Blueprint $table) {
            $table->dropColumn('text_fr');
        });
    }
};
