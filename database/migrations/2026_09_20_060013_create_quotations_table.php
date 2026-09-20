<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('subject'); // e.g. "Custom Aluminium 3-Track Sliding Doors for Villa"
            $table->text('requirements'); // Custom measurements, specifications, quantities
            $table->string('attachment')->nullable(); // Uploaded CAD/PDF/Image spec
            $table->string('status')->default('pending'); // pending, reviewed, quoted, accepted, rejected
            $table->integer('estimated_days')->nullable();
            $table->date('valid_until')->nullable();
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('admin_notes')->nullable(); // Terms, warranty, delivery remarks
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
