<form action="{{ route('clientes.store') }}" method="POST">
    @csrf
    <label for="nombre">Nombre del Cliente:</label>
    <input type="text" name="nombre" required>

    <label for="email">Correo Electrónico:</label>
    <input type="email" name="email" required>

    <label for="telefono">Teléfono:</label>
    <input type="text" name="telefono">

    <label for="direccion">Dirección:</label>
    <input type="text" name="direccion">

    <button type="submit">Crear Cliente</button>
</form>
