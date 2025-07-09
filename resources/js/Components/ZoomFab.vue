<template>
  <q-page-sticky position="bottom-right" class="z-20" :offset="[18, 18]">
    <q-fab 
      class="q-mr-sm q-mb-lg" 
      v-model="fabOpen" 
      vertical-actions-align="right" 
      color="primary" 
      icon="zoom_in"
      direction="up">
      <q-fab-action 
        label-position="left" 
        color="blue-grey-7" 
        @click="handleDecrease" 
        icon="remove"
        label="Diminuir fonte" />
      <q-fab-action 
        label-position="left" 
        color="primary" 
        @click="handleIncrease" 
        icon="add"
        label="Aumentar fonte" />
    </q-fab>
  </q-page-sticky>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['increase-font', 'decrease-font']);
const fabOpen = ref(false);

function handleIncrease() {
  emit('increase-font');
  fabOpen.value = true;
}

function handleDecrease() {
  emit('decrease-font');
  fabOpen.value = true;
}
</script>
