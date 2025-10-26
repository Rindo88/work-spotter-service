<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('favoriteable_type')->nullable(); // Untuk fleksibilitas future
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi
            $table->unique(['user_id', 'vendor_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorites');
    }
};