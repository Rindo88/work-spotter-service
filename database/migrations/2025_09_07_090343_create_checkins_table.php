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
        Schema::create('checkins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('vendors')->onDelete('cascade');
            $table->foreignId('rfid_tag_id')->nullable()->constrained('rfid_tags')->nullOnDelete();
            $table->timestamp('checkin_time')->useCurrent();
            $table->timestamp('checkout_time')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('location_name')->nullable();
            $table->enum('status', ['checked_in', 'checked_out', 'auto_checked_out'])->default('checked_in');
            $table->tinyInteger('warning_stage')->default(0);
            $table->timestamps();

            $table->index(['status', 'checkin_time']);
            $table->index(['latitude', 'longitude']);
            $table->index('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkins');
    }
};
