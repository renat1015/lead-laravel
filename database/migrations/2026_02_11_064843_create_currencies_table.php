<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3)->unique(); // USD, EUR, RUB
            $table->decimal('rate', 12, 6); // Курс к базовой валюте
            $table->boolean('base')->default(false); // Базовая валюта системы
        });

        // Создаем частичный уникальный индекс для ограничения только одной базовой валюты
        DB::statement('CREATE UNIQUE INDEX currencies_base_unique ON currencies(base) WHERE base = true');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS currencies_base_unique');
        Schema::dropIfExists('currencies');
    }
};
