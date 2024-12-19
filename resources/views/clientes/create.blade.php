<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Equipo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <!-- Contenedor para centrar el formulario -->
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="container mx-auto py-6 px-4 sm:px-6 md:px-8 bg-white rounded-lg shadow-lg w-full max-w-4xl">
            <h2 class="text-2xl font-bold mb-6 text-center">Nuevo Cliente</h2>

<form action="{{ route('clientes.store') }}" method="POST" class="space-y-4">
    @csrf
    <!-- Campos de formulario -->
    <div>
        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
        <input 
            type="text" 
            id="nombre" 
            name="nombre" 
            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
            required>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input 
            type="email" 
            id="email" 
            name="email" 
            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
            required>
    </div>

    <div>
        <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input 
            type="tel" 
            id="telefono" 
            name="telefono" 
            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
            required>
    </div>

    <div>
        <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
        <textarea 
            id="direccion" 
            name="direccion" 
            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('clientes.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-400">Cancelar</a>
        <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">
            Guardar
        </button>
    </div>
</form>
