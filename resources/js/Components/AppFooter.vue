<template>
  <q-footer>
    <q-toolbar class="bg-grey-1 text-white shadow-4 q-py-md q-ma-none" :class="{ 'bg-grey-10 shadow-0': darkMode }">
      <div class="col text-subtitle1 text-center" :class="{ 'text-black': !darkMode }">
        Desenvolvido com ❤️ por
        <a href="https://galhardo.dev/" target="_blank">Thiago Galhardo</a>
      </div>
    </q-toolbar>
  </q-footer>
</template>

<script setup>
defineProps({
  darkMode: { type: Boolean, default: false },
});
</script>
