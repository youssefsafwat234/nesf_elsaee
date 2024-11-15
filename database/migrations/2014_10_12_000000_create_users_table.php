<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            // for any users
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->enum('accountType', ['مقدم خدمة', "شركة عقارية", "مسوق عقاري", "مكتب عقاري", 'حساب مستخدم']);
            $table->string('phone')->unique();

            // not for end users
            $table->enum('subscriptionType', ["شهري", "سنوي"])->nullable();
            $table->string('whatsapp_phone')->unique()->nullable();
            $table->text('logo')->nullable();
            $table->string('city')->nullable();
            $table->text('location')->nullable();
            $table->text('val_certification')->nullable();
            $table->text('other_certifications')->nullable();
            $table->text('website_url')->nullable();

            // only for company and office users
            $table->text('commercial_register')->nullable();
            $table->text('manager_name')->nullable();
            $table->text('social_media_url')->nullable();
            $table->text('twitter_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->text('snapchat_url')->nullable();
            $table->text('branches')->nullable();


            // for  freelancers
            $table->string('neighborhood')->nullable();

            // for services accounts
            $table->string('service_type')->nullable();

            // for social register and login
            $table->string('provider_name')->nullable();
            $table->string('provider_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
