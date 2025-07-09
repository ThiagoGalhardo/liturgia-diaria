<template>
  <div class="justify-center q-mt-md">
    <div class="flex justify-center">
      <q-icon name="warning" size="xl" color="warning" class="q-mb-md" />
    </div>
    <h3 class="text-h6 text-center">{{ message || 'Não foram encontradas as liturgias para a data selecionada!' }}</h3>
    <p v-if="showDate" class="text-subtitle1 q-mt-sm text-center">Por favor, selecione outra data.</p>
  </div>
</template>

<script setup>
defineProps({
  message: {
    type: String,
    default: ''
  },
  showDate: {
    type: Boolean,
    default: true
  }
});
</script>

<style lang="scss" scoped></style>