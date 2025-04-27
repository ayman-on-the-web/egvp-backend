<?php

use App\Models\EventCategory;
use App\Models\Organization;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target_hours');
            $table->integer('points');
            $table->string('address');
            $table->string('city');
            $table->string('country');
            $table->boolean('is_approved')->nullable()->default(false);
            $table->foreignIdFor(EventCategory::class);
            $table->foreignIdFor(Organization::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
