<?php

use App\Enums\GeneralStatusEnum;
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
        Schema::create('lottery_batches', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('title');
            $table->date('batch_start');
            $table->date('batch_end');
            $table->decimal('total_ticket', 10, 2);
            $table->decimal('ticket_price', 10, 2);
            $table->decimal('credit_amount', 10, 2);
            $table->string('status')->default(GeneralStatusEnum::PENDING->value);
            $table->auditColumns();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lottery_batches');
    }
};
