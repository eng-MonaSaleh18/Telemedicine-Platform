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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->onDelete('cascade');

            $table->enum('complaint_type', [
                'unprofessional_behavior',
                'delayed_response',
                'wrong_diagnosis',
                'billing_issue',
                'technical_issue',
                'other',
            ]);

            $table->longText('description');

            $table->string('contact_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
