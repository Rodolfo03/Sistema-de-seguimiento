<template>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md">
      <div class="relative text-gray-500 dark:focus-within:text-purple-400">
        <select
          class="block w-full pl-10 mt-1 text-sm text-black focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input"
          v-model="localValue"   
          @change="handleChange"
          ref="input"
          :required="required"
        >
          <option value="" disabled selected>{{ text }}</option>
          <option v-for="op in options" :key="op.id" :value="op.id">
            {{ op.nombre }}
          </option>
        </select>
        <div class="absolute inset-y-0 flex items-center ml-3 pointer-events-none">
          <slot></slot>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, watch } from 'vue';
  
  const props = defineProps({
    modelValue: {
      type: String,
      required: true
    },
    text: {
      type: String,
      default: 'Selecciona una opción'
    },
    options: {
      type: Array,
      required: true
    },
    required: {
      type: Boolean,
      default: false
    }
  });
  
  const emit = defineEmits(['update:modelValue']);
  
  const localValue = ref(props.modelValue);
  
  // Observa los cambios en el valor local y emite los cambios al padre
  watch(localValue, (newValue) => {
    emit('update:modelValue', newValue);
  });
  
  const handleChange = (event) => {
    // Emite el valor cuando hay un cambio (es redundante con v-model, pero útil si deseas hacer algo adicional)
    emit('update:modelValue', event.target.value);
  };
  </script>
  