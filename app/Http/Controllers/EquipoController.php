<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClienteEditado;

class EquipoController extends Controller
{
    public function index()
    {
        $equipos = Equipo::with(['cliente', 'user'])->get(); // Suponiendo que tienes un modelo Equipo
    return Inertia::render('Equipos/Index', [
        'equipos' => $equipos,
    ]);
    }
    public function destroy($id)
    {
        $equipo = Equipo::findOrFail($id);
        $equipo->delete();

        return redirect()->route('equipos.index')->with('success', 'Equipo eliminado con éxito');
    }
    public function edit($id)
    {
        $equipo = Equipo::findOrFail($id);
        return inertia('Equipos/Edit', [
            'equipo' => $equipo,
        ]);
    }
    public function update( Request $request, Equipo $equipo)
    {
        $request->validate([
            'modelo_equipo' => 'required|string|max:255',
            'tipo_reparacion' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string',
            'fecha_ingreso' => 'required|date',
            'fecha_entrega_estimada' => 'required|date',
            'diagnostico' => 'nullable|string',
            'detalles' => 'nullable|string',
        ]);

        // Actualizar datos del equipo
        $data = $request->except(['id', 'created_at', 'updated_at']);
        $equipo->update($data);

        // Obtener el cliente y su correo
        $cliente = $equipo->cliente; // Suponiendo que el modelo `Equipo` tiene la relación con `Cliente`
        
        // Enviar el correo al cliente
        $link = url('http://localhost:8080/estados/public/clientes/' . $cliente->id); // Aquí generamos el enlace dinámico con el cliente_id
        Mail::to($cliente->email)->send(new ClienteEditado($equipo, $link));

        return redirect()->route('equipos.index')->with('success', 'Equipo actualizado y correo enviado exitosamente.');
    }
    public function create()
    {
        $clientes = Cliente::all(); // Obtenemos todos los clientes
        return view('equipos.create', compact('clientes')); // Pasamos los clientes a la vista Blade
    }
    public function store(Request $request)
{
    // Validar los datos del formulario
    $validated = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'modelo_equipo' => 'required|string|max:255',
        'tipo_reparacion' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'diagnostico' => 'nullable|string',
        'fecha_ingreso' => 'required|date',
        'fecha_entrega_estimada' => 'required|date|after_or_equal:fecha_ingreso',
        'estado' => 'required|string|in:recibido,revision,diagnostico,reparacion,pausado,cancelado,terminado,entregado',
        'detalles' => 'nullable|string',
        
    ]);

    // Crear un nuevo registro en la tabla de equipos
    $validated['tecnico_id'] = Auth::id();
    Equipo::create($validated);

    // Redirigir al listado de equipos con un mensaje de éxito
    return redirect()->route('equipos.index')->with('success', 'Equipo creado con éxito.');
}


}
