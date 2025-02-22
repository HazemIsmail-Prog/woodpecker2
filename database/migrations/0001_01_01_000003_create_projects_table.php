<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('contract_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('quotation_number')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('installation_date')->nullable();
            $table->string('type_of_work')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
}; 