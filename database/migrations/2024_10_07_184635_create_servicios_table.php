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
        Schema::create('servicios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('equipo_id')->constrained()->onDelete('cascade');
        $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
        $table->enum('estado', ['recibido', 'revision', 'diagnostico', 'reparacion', 'pausado', 'cancelado', 'terminado', 'entregado'])->default('recibido');
        $table->text('detalles')->nullable();
        $table->date('fecha_actualizacion');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios');
    }
};
