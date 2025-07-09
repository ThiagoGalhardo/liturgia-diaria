<template>
  <q-header>
    <q-toolbar class="bg-grey-1 text-white shadow-2 q-py-md q-ma-none" :class="{ 'bg-grey-10 shadow-0': darkMode }">
      <q-img src="img/nossa-senhora-aparecida.webp" width="42px" class="q-ml-md"></q-img>

      <div class="q-ml-md text-subtitle1 text-weight-bold" :class="{ 'text-black': !darkMode }">Liturgia Diária</div>
      <q-space />

      <FormattedDatePicker 
        :model-value="dateSelected" 
        @update:model-value="$emit('update:dateSelected', $event)"
        class="q-mr-md" 
        :dark="darkMode" 
        dense
        label="Selecione a data" />

      <q-toggle 
        :model-value="darkMode" 
        @update:model-value="$emit('update:darkMode', $event)"
        class="q-pr-md q-py-xs" 
        color="primary" 
        dense 
        keep-color
        checked-icon="mdi-weather-night" 
        unchecked-icon="mdi-white-balance-sunny" 
        size="lg" />
    </q-toolbar>
  </q-header>
</template>

<script setup>
import FormattedDatePicker from "./FormattedDatePicker.vue";

defineProps({
  dateSelected: { type: String, required: true },
  darkMode: { type: Boolean, required: true },
});

defineEmits(['update:dateSelected', 'update:darkMode']);
</script>
