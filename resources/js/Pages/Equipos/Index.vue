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
import { jsPDF } from 'jspdf';



const props = defineProps({
    equipos: { type: Object }
});



const showModalForm = ref(false);
const equipoActual = ref(null);
const searchQuery = ref(''); // Término de búsqueda
const showTicketPreview = ref(false);
const ticketPreview = ref(null);

const openTicketPreview = (equipo) => {
    ticketPreview.value = equipo;
    showTicketPreview.value = true;
};

const closeTicketPreview = () => {
    showTicketPreview.value = false;
    ticketPreview.value = null;
};

const openEditModal = (equipo) => {
    equipoActual.value = { ...equipo };
    showModalForm.value = true;
};

const closeModalForm = () => {
    showModalForm.value = false;
    equipoActual.value = null;
};

const { data, setData, put, processing } = useForm({
    modelo_equipo: '',
    tipo_reparacion: '',
    descripcion: '',
    estado: '',
    fecha_ingreso: '',
    fecha_entrega_estimada: '',
    diagnostico: '',
    detalles: ''
});



// Filtrar equipos basados en el término de búsqueda
const filteredEquipos = computed(() => {
    if (!searchQuery.value) return props.equipos;
    return props.equipos.filter(equipo =>
        equipo.cliente.nombre.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        equipo.modelo_equipo.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        equipo.tipo_reparacion.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        equipo.descripcion.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
        equipo.estado.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});
const getEstadoColor = (estado) => {
    switch(estado) {
        case 'recibido':
            return 'bg-blue-200 text-blue-800'; // Azul claro
        case 'revision':
            return 'bg-yellow-200 text-yellow-800'; // Amarillo
        case 'pausado':
            return 'bg-gray-200 text-gray-800'; // Gris
        case 'cancelado':
            return 'bg-red-200 text-red-800'; // Rojo
        case 'terminado':
            return 'bg-green-200 text-green-800'; // Verde
        case 'entregado':
            return 'bg-teal-200 text-teal-800'; // Verde claro o azul
        case 'diagnostico':
            return 'bg-orange-200 text-orange-800'; // Naranja
        case 'reparacion':
            return 'bg-blue-500 text-white'; // Azul fuerte
        default:
            return 'bg-gray-100 text-gray-700'; // Color por defecto
    }
};

const submitForm = (equipoId) => {
    if (!equipoActual.value) {
        alert('No se ha seleccionado un equipo para editar.');
        return;
    }

    Inertia.put(route('equipos.update', equipoId), equipoActual.value, {
        onSuccess: () => {
            closeModalForm();
            alert('Equipo actualizado con éxito');
        },
        onError: (errors) => {
            console.error('Errores:', errors);
        },
    });
};

const deleteEquipo = (equipoId) => {
    if (confirm('¿Estás seguro de que quieres eliminar este equipo?')) {
        Inertia.delete(route('equipos.destroy', equipoId), {
            onSuccess: () => alert('Equipo eliminado exitosamente'),
        });
    }
};


const generateTicketPDF = (equipo) => {
    if (!equipo) {
        alert('No hay equipo seleccionado');
        return;
    }

    // Configurar el tamaño del ticket: 80mm de ancho (~220 px) y longitud dinámica
    const doc = new jsPDF({
        orientation: 'portrait',
        unit: 'mm',
        format: [80, 150], // 80mm de ancho x 150mm de alto
    });

    const logoBase64 = 'http://localhost:8080/proyecto_innovacion1/public/images/images.png'; // Aquí va tu imagen en formato base64

    // Agregar el logo (opcional)
    doc.addImage(logoBase64, 'PNG', 25, 10, 25, 25); // Ajustar posición y tamaño (x, y, ancho, alto)

    // Encabezado
    doc.setFontSize(10);
    doc.setFont('courier', 'bold');
    doc.text("TICKET DE REPARACIÓN", 40, 40, { align: 'center' });
    doc.setFont('courier', 'normal');
    doc.text("Servicio Técnico Fontec", 40, 45, { align: 'center' });
    doc.text("www.fontec.com.pe", 40, 50, { align: 'center' });

    // Detalles del ticket
    let yPosition = 60; // Posición inicial en Y
    const lineHeight = 6; // Espaciado entre líneas

    doc.setFontSize(8);
    doc.text(`Modelo: ${equipo.modelo_equipo}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Dueño: ${equipo.cliente.nombre}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Reparación: ${equipo.tipo_reparacion}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Descripción: ${equipo.descripcion}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Estado: ${equipo.estado}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Diagnóstico: ${equipo.diagnostico}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Detalles: ${equipo.detalles}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Fecha de Ingreso: ${equipo.fecha_ingreso}`, 5, yPosition);
    yPosition += lineHeight;
    doc.text(`Entrega Estimada: ${equipo.fecha_entrega_estimada}`, 5, yPosition);

    // Pie del ticket
    yPosition += lineHeight * 2; // Espaciado extra para el pie
    doc.text("Gracias por confiar en nosotros", 40, yPosition, { align: 'center' });
    yPosition += lineHeight;
    doc.text("Servicio Técnico Fontec", 40, yPosition, { align: 'center' });

    // Guardar el PDF
    doc.save(`ticket-${equipo.id}.pdf`);
};



</script>

<template>
    <head title="equipos" />
    <AuthenticatedLayout>
        <template #header>
            Equipos
            <a href="http://localhost:8080/proyecto_innovacion1/public/crearequipo">
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
                            <th class="px-4 py-3">Dueño</th>
                            <th class="px-4 py-3">Modelo</th>
                            <th class="px-4 py-3">Reparación</th>
                            <th class="px-4 py-3">Descripción</th>
                            <th class="px-4 py-3">Fecha Ingreso</th>
                            <th class="px-4 py-3">Fecha Entrega</th>
                            <th class="px-4 py-3">Diagnóstico</th>
                            <th class="px-4 py-3">Estado</th>
                            <th class="px-4 py-3">Técnico</th>
                            <th class="px-4 py-3">Detalles</th>
                            <th class="px-4 py-3">Editar</th>
                            <th class="px-4 py-3">Eliminar</th>
                            <th class="px-4 py-3">Generar Ticket</th>
                            <th class="px-4 py-3">vista Ticket</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs font-semibold tracking-wide text-gray-500 bg-gray-50 dark:text-gray-400">
                        <tr v-for="(a, i) in filteredEquipos" :key="a.id" class="text-gray-700">
                            <td class="px-4 py-3 text-sm">{{ i + 1 }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.cliente.nombre }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.modelo_equipo }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.tipo_reparacion }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.descripcion }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.fecha_ingreso }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.fecha_entrega_estimada }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.diagnostico }}</td>
                            <td :class="getEstadoColor(a.estado)" class="px-4 py-3 text-sm">{{ a.estado }}</td>
                            <td class="px-4 py-3 text-sm">{{ a.user.name }}</td>
                            <td class="br-4 px-4 py-3 text-sm">{{ a.detalles }}</td>
                            <td>
                                <WarningButton @click="openEditModal(a)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </WarningButton>
                            </td>
                            <td>
                                <td>
                                <DangerButton @click="deleteEquipo(a.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </DangerButton>
                            </td>

                            </td>

                            <td>
                                <button @click="generateTicketPDF(a)" class="text-sm text-white bg-blue-600 px-2 py-2 rounded">Generar PDF</button>
                            </td>
                            <td>
                                <button @click="openTicketPreview(a)" class="text-sm text-white bg-gray-600 px-2 py-2 rounded">Vista Previa</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para editar equipo -->
        <Modal :show="showModalForm" @close="closeModalForm">
        <div class="p-6">
            <h2>Editar Equipo: {{ equipoActual?.id }} <br> Dueño: {{ equipoActual.cliente.nombre }}</h2>
            <div class="mt-6">
                <label for="diagnostico" class="block text-sm font-medium text-gray-700">Modelo</label>
                <InputGroup  label="Modelo" v-model="equipoActual.modelo_equipo" />
                <label for="diagnostico" class="block text-sm font-medium text-gray-700">Reparación</label>
                <InputGroup label="Tipo de Reparación" v-model="equipoActual.tipo_reparacion" />
                <label for="diagnostico" class="block text-sm font-medium text-gray-700">Descripción</label>
                <InputGroup label="Descripción" v-model="equipoActual.descripcion" />
                <label for="diagnostico" class="block text-sm font-medium text-gray-700">Fecha de Ingreso</label>
                <InputGroup label="Fecha de Ingreso" type="date" v-model="equipoActual.fecha_ingreso" />
                
                <label for="diagnostico" class="block text-sm font-medium text-gray-700">Estado</label>
                <InputGroup label="Fecha de Entrega Estimada" type="date" v-model="equipoActual.fecha_entrega_estimada" />
                <InputGroup label="Diagnóstico" v-model="equipoActual.diagnostico" />
                <div>
                    <label for="diagnostico" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select 
                        
                        v-model="equipoActual.estado"
                        id="diagnostico"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    >
                        <option value="recibido">Recibido</option>
                        <option value="revision">Revisión</option>
                        <option value="diagnostico">Diagnóstico</option>
                        <option value="reparacion">Reparación</option>
                        <option value="pausado">Pausado</option>
                        <option value="cancelado">Cancelado</option>
                        <option value="terminado">Terminado</option>
                        <option value="entregado">Entregado</option>
                    </select>
                    <br>
                </div>
                <label for="diagnostico" class="block text-sm font-medium text-gray-700">detalles</label>
                <InputGroup label="Detalles" v-model="equipoActual.detalles" />
            </div>
        </div>
        <div class="m-6 flex justify-end">
            <SecondaryButton @click="closeModalForm">Cancelar</SecondaryButton>
            <SecondaryButton @click="submitForm(equipoActual?.id)" class="ml-2">Guardar</SecondaryButton>
        </div>
    </Modal>
    <Modal :show="showTicketPreview" @close="closeTicketPreview">
    <div class="p-4 bg-white rounded shadow-lg w-72 mx-auto text-sm font-mono">
        <div class="text-center border-b pb-2">
            <h3 class="font-bold text-lg">TICKET DE REPARACIÓN</h3>
            <p class="text-xs text-gray-600">Servicio Técnico Fontec</p>
        </div>
        <div class="mt-4">
            <p><strong>Modelo:</strong> {{ ticketPreview?.modelo_equipo }}</p>
            <p><strong>Dueño:</strong> {{ ticketPreview?.cliente.nombre }}</p>
            <p><strong>Reparación:</strong> {{ ticketPreview?.tipo_reparacion }}</p>
            <p><strong>Descripción:</strong> {{ ticketPreview?.descripcion }}</p>
            <p><strong>Estado:</strong> {{ ticketPreview?.estado }}</p>
            <p><strong>Diagnóstico:</strong> {{ ticketPreview?.diagnostico }}</p>
            <p><strong>Detalles:</strong> {{ ticketPreview?.detalles }}</p>
            <p><strong>Fecha de Ingreso:</strong> {{ ticketPreview?.fecha_ingreso }}</p>
            <p><strong>Entrega Estimada:</strong> {{ ticketPreview?.fecha_entrega_estimada }}</p>
        </div>
        <div class="mt-4 text-center">
            <p class="text-xs text-gray-500">Gracias por confiar en nosotros</p>
            <p class="text-xs text-gray-500">www.fontecperu.com</p>
        </div>
        <div class="mt-2 flex justify-end">
            <SecondaryButton @click="closeTicketPreview">Cerrar</SecondaryButton>
        </div>
    </div>
</Modal>
    </AuthenticatedLayout>
</template>

