<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::with('equipos')->paginate(50); // Obtener clientes con sus equipos

    return Inertia::render('Clientes/Index', [
        'clientes' => $clientes
    ]);
    }

    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string|max:255',
    ]);

    $cliente = Cliente::findOrFail($id);
    $cliente->update($request->all());

    return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
}
    public function create()
    {
        return view('clientes.create');
    }

public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
        ]);

        // Crear un nuevo cliente en la base de datos
        Cliente::create($request->all());

        // Redirigir con mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

}
