<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->boolean('is_closed')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['vendor_id', 'day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_schedules');
    }
};