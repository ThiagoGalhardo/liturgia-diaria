<template>
  <q-card class="q-mt-lg q-mx-md" :class="{ 'bg-grey-10 shadow-0': darkMode }">
    <q-card-section>
      <div class="text-h6 liturgy-content" v-html="content" :style="{ fontSize: `${fontSize}px` }"></div>
    </q-card-section>
  </q-card>
</template>

<script setup>

defineProps({
  content: { type: String, required: true },
  fontSize: { type: Number, default: 18 },
  darkMode: { type: Boolean, default: false }
});
</script>
