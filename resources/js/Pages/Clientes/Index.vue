<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DangerButton from '@/Components/DangerButton.vue';
import WarningButton from '@/Components/WarningButton.vue';
import DarkButton from '@/Components/DarkButton.vue';
import InputGroup from '@/Components/InputGroup.vue';
import SelectInput from '@/Components/SelectInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

const props = defineProps({
    clientes: { type: Object }
});

const searchQuery = ref(''); // Término de búsqueda
const form = useForm({id:'', nombre:''});
const showModalDel = ref(false);
const msj = ref((props.flash?.success) ? props.flash.success : '');
const classMsj = ref((props.flash?.success) ? '' : 'hidden');
const clienteActual = ref({ id: '', nombre: '', email: '', telefono: '', direccion: '' });


const openEditModal = (cliente) => {
    clienteActual.value = { ...cliente }; // Copia los datos del cliente seleccionado
    showModalDel.value = true; // Usa la variable correcta
};

const closeModalForm = () => {
    showModalDel.value = false; // Cierra el modal
};

const filteredClientes = computed(() => {
    if (!searchQuery.value) return props.clientes.data;
    return props.clientes.data.filter(cliente =>
        cliente.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        cliente.email.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        cliente.telefono.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        cliente.direccion.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const deleteCliente = (clienteId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este equipo?')) {
        Inertia.delete(route('cliente.destroy', clienteId), {
            onSuccess: () => alert('Equipo eliminado exitosamente'),
        });
    }
};
const submitForm = (clienteId) => {
    Inertia.put(route('cliente.update', clienteId), clienteActual.value, {
        onSuccess: () => {
            alert('Cliente actualizado exitosamente');
            showModalForm.value = false; // Cierra el modal
        },
        onError: (errors) => {
            alert('Error al actualizar el cliente: ' + JSON.stringify(errors));
        },
    });
};


</script>

<template>
    <head title="clientes" />
    <AuthenticatedLayout>
        <template #header>
            Clientes
            <a href="http://localhost:8080/proyecto_innovacion1/public/crearcliente">
            <DarkButton>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
            </DarkButton>
        </a>
        </template>

        <!-- Campo de búsqueda -->
        <div class="my-4">
            <InputGroup label="Buscar" v-model="searchQuery" placeholder="Buscar por nombre, modelo, reparación..." />
        </div>

        <div class="w-full overflow-hidden rounded-lg border shadow-md">
            <div class="w-full overflow-x-auto bg-white">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b bg-gray-50 dark:text-gray-400">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">nombre</th>
                            <th class="px-4 py-3">email</th>
                            <th class="px-4 py-3">telefono</th>
                            <th class="px-4 py-3">direccion</th>
                            
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold tracking-wide text-gray-500 bg-gray-50 dark:text-gray-400">
                        <tr v-for="(cliente, i) in filteredClientes" :key="cliente.id" class="text-gray-700">
                            <td class="px-4 py-3 text-sm">{{ i + 1 }}</td>
                            <td class="px-4 py-3 text-sm">{{ cliente.nombre }}</td>
                            <td class="px-4 py-3 text-sm">{{ cliente.email }}</td>
                            <td class="px-4 py-3 text-sm">{{ cliente.telefono }}</td>
                            <td class="px-4 py-3 text-sm">{{ cliente.direccion }}</td>
                            <td>
                                <WarningButton @click="openEditModal(cliente)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </WarningButton>
                            </td>
                            <td>
                                <td>
                                <DangerButton @click="deleteCliente(cliente.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </DangerButton>
                            </td>

                            </td>

                        
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para editar equipo -->
        <Modal :show="showModalDel" @close="closeModalForm">
    <div class="p-6">
        <h2>Editar Cliente</h2>
        <div class="mt-6">
            <InputGroup label="Nombre" v-model="clienteActual.nombre" />
            <InputGroup label="Email" type="email" v-model="clienteActual.email" />
            <InputGroup label="Teléfono" v-model="clienteActual.telefono" />
            <InputGroup label="Dirección" v-model="clienteActual.direccion" />
        </div>
    </div>
    <div class="m-6 flex justify-end">
        <SecondaryButton @click="closeModalForm">Cancelar</SecondaryButton>
        <SecondaryButton @click="submitForm(clienteActual.id)" class="ml-2">Guardar</SecondaryButton>
    </div>
</Modal>
    
    </AuthenticatedLayout>
</template>

