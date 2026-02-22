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
        Schema::create('mail_accounts', function (Blueprint $table) {
            $table->id();

            // Basic account information
            $table->string('name'); // Account name/label
            $table->string('email')->unique();
            $table->string('server');
            $table->string('password'); // Encrypted in model

            // IMAP Configuration
            $table->integer('imap_port')->default(993);
            $table->enum('imap_security', ['none', 'ssl', 'tls'])->default('ssl');

            // SMTP Configuration
            $table->integer('smtp_port')->default(587);
            $table->enum('smtp_security', ['none', 'ssl', 'tls'])->default('tls');

            // Status
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_synced_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mail_accounts');
    }
};
