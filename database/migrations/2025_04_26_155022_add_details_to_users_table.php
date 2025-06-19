<?php

use App\Models\User;
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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->before('created_at');
            $table->string('address')->before('created_at');
            $table->longText('profile_photo_base64')->nullable()->before('created_at');
            $table->string('identification_type')->before('created_at'); //National ID, Passport, Commercial Registeration
            $table->string('identification_number')->before('created_at');
            $table->string('user_type')->default(User::TYPE_VOLUNTEER)->before('created_at'); //SuperAdmin, Admin, Volunteer, Organization
            $table->boolean('is_active')->default(1)->before('created_at');
            $table->date('active_until')->default('9999-12-31')->before('created_at');
            $table->boolean('is_approved')->default(0)->before('created_at');
            $table->timestamp('approved_at')->nullable()->before('created_at');
            $table->float('points')->default(0)->before('created_at');
            $table->text('skills')->nullable()->before('created_at');
            $table->longText('details')->nullable()->before('created_at');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
