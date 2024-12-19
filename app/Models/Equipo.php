<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id', 'id');
    }

    public function servicios()
    {
        return $this->hasMany(Servicio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'tecnico_id');
    }
    protected static function booted()
    {
        static::deleting(function ($equipo) {
            // Si tiene relaciones dependientes, elimínalas.
            $equipo->cliente()->delete(); // O ajusta según tu modelo.
        });
    }
    protected $fillable = [
        'id',               // Incluye este campo
        'cliente_id',
        'modelo_equipo',
        'tipo_reparacion',
        'descripcion',
        'fecha_ingreso',
        'fecha_entrega_estimada',
        'diagnostico',
        'estado',
        'tecnico_id',
        'detalles',
    ];
}
