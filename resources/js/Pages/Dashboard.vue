<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";

const props = defineProps({
  clientes: { type: Object },
  equipos: { type: Number },
  users: { type: Object },
  equiposPorEstado: { type: Array },
  equiposPorFecha: { type: Array },
});

const chartInstanceEstado = ref(null);
const chartInstanceFecha = ref(null);

onMounted(() => {
  // Gráfico de Equipos por Estado
  const ctxEstado = document.getElementById("equiposPorEstadoChart").getContext("2d");
  const estados = props.equiposPorEstado.map(item => item.estado);
  const totalesEstado = props.equiposPorEstado.map(item => item.total);

  chartInstanceEstado.value = new Chart(ctxEstado, {
    type: "bar",
    data: {
      labels: estados,
      datasets: [
        {
          label: "Cantidad de Dispositivos",
          data: totalesEstado,
          backgroundColor: "rgba(75, 192, 192, 0.4)", // Color más suave
          borderColor: "rgba(75, 192, 192, 1)",
          borderWidth: 2,
          barThickness: 30, // Hacer las barras más gruesas
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: { ticks: { font: { size: 14, weight: "bold" }, color: "#4B5563" } },
        y: { ticks: { font: { size: 14, weight: "bold" }, color: "#4B5563" } },
      },
      plugins: {
        legend: {
          position: "top",
          labels: {
            font: { size: 16, weight: "bold" },
            color: "#4B5563",
          },
        },
      },
    },
  });

  // Gráfico de Equipos por Fecha
  const ctxFecha = document.getElementById("equiposPorFechaChart").getContext("2d");
  const fechas = props.equiposPorFecha.map(item => item.fecha);
  const totalesFecha = props.equiposPorFecha.map(item => item.total);

  chartInstanceFecha.value = new Chart(ctxFecha, {
    type: "line",
    data: {
      labels: fechas,
      datasets: [
        {
          label: "Equipos Reparados por Fecha",
          data: totalesFecha,
          fill: true,
          borderColor: "rgba(54, 162, 235, 1)",
          backgroundColor: "rgba(54, 162, 235, 0.2)", // Fondo suave para el área
          borderWidth: 2,
          tension: 0.3,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: { ticks: { font: { size: 14, weight: "bold" }, color: "#4B5563" } },
        y: { ticks: { font: { size: 14, weight: "bold" }, color: "#4B5563" } },
      },
      plugins: {
        legend: {
          position: "top",
          labels: {
            font: { size: 16, weight: "bold" },
            color: "#4B5563",
          },
        },
      },
    },
  });
});
</script>

<template>
  <Head title="Dashboard" />
  
  <AuthenticatedLayout>
    <template #header>
      Dashboard
    </template>
    
    <div class="p-4 bg-white rounded-lg shadow-lg">
      <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card Total de Clientes -->
        <div class="flex border items-center p-6 bg-white rounded-lg shadow-lg">
          <div class="p-3 mr-4 text-orange-500 bg-orange-200 rounded-full">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
              <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
            </svg>
          </div>
          <div>
            <p class="mb-2 text-sm font-medium text-gray-600">Total de Clientes</p>
            <p class="text-lg font-semibold text-gray-700">{{ clientes.length }}</p>
          </div>
        </div>
        
        <!-- Card Total de Equipos -->
        <div class="flex border items-center p-6 bg-white rounded-lg shadow-lg">
          <div class="p-3 mr-4 text-green-500 bg-green-200 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
            </svg>
          </div>
          <div>
            <p class="mb-2 text-sm font-medium text-gray-600">Total de Equipos</p>
            <p class="text-lg font-semibold text-gray-700">{{ equipos.length }}</p>
          </div>
        </div>
        
        <!-- Card Total de Técnicos -->
        <div class="flex border items-center p-6 bg-white rounded-lg shadow-lg">
          <div class="p-3 mr-4 text-blue-500 bg-blue-200 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 0 0 4.486-6.336l-3.276 3.277a3.004 3.004 0 0 1-2.25-2.25l3.276-3.276a4.5 4.5 0 0 0-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437 1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008Z" />
            </svg>
          </div>
          <div>
            <p class="mb-2 text-sm font-medium text-gray-600">Técnicos</p>
            <p class="text-lg font-semibold text-gray-700">{{ users.length }}</p>
          </div>
        </div>
      </div>

      <!-- Gráfico Equipos por Estado -->
      <div class="mb-8">
        <canvas id="equiposPorEstadoChart" height="400"></canvas>
      </div>

      <!-- Gráfico Equipos por Fecha -->
      <div>
        <canvas id="equiposPorFechaChart" height="400"></canvas>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos personalizados */
.card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
}
</style>
