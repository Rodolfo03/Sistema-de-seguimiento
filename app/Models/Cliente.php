<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['modelo_equipo', 'cliente_id','nombre', 'email', 'telefono', 'direccion']; // Ajusta según tus campos.

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'cliente_id', 'id');
    }
}
