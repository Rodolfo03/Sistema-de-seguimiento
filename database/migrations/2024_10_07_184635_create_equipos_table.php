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
        Schema::create('equipos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
        $table->string('modelo_equipo');
        $table->string('tipo_reparacion');
        $table->text('descripcion')->nullable();
        $table->date('fecha_ingreso');
        $table->date('fecha_entrega_estimada')->nullable();
        $table->text('diagnostico')->nullable();
        $table->enum('estado', ['recibido', 'revision', 'diagnostico', 'reparacion', 'pausado', 'cancelado', 'terminado', 'entregado'])->default('recibido');
        $table->foreignId('tecnico_id')->constrained('users')->onDelete('cascade');
        $table->text('detalles')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
