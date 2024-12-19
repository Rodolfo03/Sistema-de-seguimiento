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
            <h2 class="text-2xl font-bold mb-6 text-center">Crear Nuevo Equipo</h2>

            <form action="{{ route('equipos.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="cliente_id" class="block text-sm font-medium text-gray-700">Dueño</label>
                    <select 
                        id="cliente_id" 
                        name="cliente_id" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id }}">{{ $cliente->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="modelo_equipo" class="block text-sm font-medium text-gray-700">Modelo</label>
                    <input 
                        type="text" 
                        id="modelo_equipo" 
                        name="modelo_equipo" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                        required>
                </div>

                <div>
                    <label for="tipo_reparacion" class="block text-sm font-medium text-gray-700">Tipo de Reparación</label>
                    <input 
                        type="text" 
                        id="tipo_reparacion" 
                        name="tipo_reparacion" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                        required>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea 
                        id="descripcion" 
                        name="descripcion" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>

                <div>
                    <label for="fecha_ingreso" class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                    <input 
                        type="date" 
                        id="fecha_ingreso" 
                        name="fecha_ingreso" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="fecha_entrega_estimada" class="block text-sm font-medium text-gray-700">Fecha de Entrega Estimada</label>
                    <input 
                        type="date" 
                        id="fecha_entrega_estimada" 
                        name="fecha_entrega_estimada" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        id="estado" 
                        name="estado" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="recibido">Recibido</option>
                        <option value="revision">Revisión</option>
                        <option value="diagnostico">Diagnóstico</option>
                        <option value="reparacion">Reparación</option>
                        <option value="pausado">Pausado</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="terminado">Terminado</option>
                        <option value="entregado">Entregado</option>
                    </select>
                </div>

                <div>
                    <label for="diagnostico" class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                    <textarea 
                        id="diagnostico" 
                        name="diagnostico" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>

                <div>
                    <label for="detalles" class="block text-sm font-medium text-gray-700">Detalles</label>
                    <textarea 
                        id="detalles" 
                        name="detalles" 
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('equipos.index') }}" class="mr-4 inline-flex items-center px-4 py-2 bg-gray-300 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-400">Cancelar</a>
                    <button 
                        type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
