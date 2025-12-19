<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // From ERD
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->nullOnDelete(); // From ERD
            $table->foreignId('from_account_id')->nullable()->constrained('accounts')->nullOnDelete(); // From ERD
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['transfer', 'deposit', 'withdrawal']); // From ERD
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending'); // From ERD
            $table->string('location')->nullable(); // From ERD
            $table->string('ip_address')->nullable(); // From ERD
            $table->text('device_info')->nullable(); // From ERD
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
