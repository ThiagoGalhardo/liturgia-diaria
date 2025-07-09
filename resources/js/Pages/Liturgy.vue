<template>
  <q-layout view="hHh lpR fFf">
    <AppHeader v-model:date-selected="dateSelected" v-model:dark-mode="darkMode" />

    <q-page-container class="page-container q-mx-xl q-px-xl">

      <template v-if="!hasReadings || errorMessage">
        <Error :message="errorMessage || 'Não foi possível carregar as leituras para esta data.'" :showDate="true" />
      </template>
      <template v-else-if="loading">
        <Skeleton :class="{ 'bg-grey-10 shadow-0': darkMode }" />
        <Skeleton :class="{ 'bg-grey-10 shadow-0': darkMode }" />
        <Skeleton :class="{ 'bg-grey-10 shadow-0': darkMode }" />
      </template>

      <div v-else class="container q-px-xl">
        <q-banner v-if="dateSelected != moment().format('YYYY-MM-DD') && !continueAnotherDate"
          class="q-mt-lg  q-mx-md shadow-1" :class="darkMode ? ' text-white' : 'bg-blue-grey-1 text-dark'" inline-actions>Gostaria de ler a liturgia de hoje {{
            moment().format('DD/MM/YYYY') }}?
          <template v-slot:action>
            <q-btn :color="darkMode ? 'blue-grey-9' : 'blue-grey-6'" label="Não" class="q-mr-sm" @click="continueAnotherDate = true" />
            <q-btn color="primary" label="Sim" @click="dateSelected = moment().format('YYYY-MM-DD')" />
          </template>
        </q-banner>

        <LiturgyCard :content="sanitizedLiturgy1" :font-size="fontSize" :dark-mode="darkMode" />
        <LiturgyCard v-if="hasReadings" :content="sanitizedLiturgyPsalms" :font-size="fontSize" :dark-mode="darkMode" />
        <LiturgyCard v-if="sanitizedLiturgy2" :content="sanitizedLiturgy2" :font-size="fontSize" :dark-mode="darkMode" />
        <LiturgyCard v-if="hasReadings" :content="sanitizedLiturgyGospel" :font-size="fontSize" :dark-mode="darkMode" class="q-my-lg" />
      </div>

      <ZoomFab @increase-font="increaseFontSize" @decrease-font="decreaseFontSize" />
    
    </q-page-container>

    <AppFooter :dark-mode="darkMode" />
  </q-layout>
</template>


<script setup>
import { computed } from "vue";
import moment from 'moment'
import DOMPurify from 'dompurify';

import { useLiturgy } from '@/composables/useLiturgy';

import Skeleton from "../Components/Skeleton.vue"
import Error from "../Components/Error.vue"
import LiturgyCard from "../Components/LiturgyCard.vue";
import AppHeader from "../Components/AppHeader.vue";
import AppFooter from "../Components/AppFooter.vue";
import ZoomFab from "../Components/ZoomFab.vue";

const props = defineProps({
  liturgies: {
    type: Object,
    default: () => ({}),
  },
  error: {
    type: String,
    default: '',
  },
  date: {
    type: String,
    default: () => moment().format('YYYY-MM-DD')
  }
})

const { 
  darkMode, 
  fontSize, 
  dateSelected, 
  loading, 
  errorMessage, 
  continueAnotherDate, 
  increaseFontSize, 
  decreaseFontSize, 
  hasReadings 
} = useLiturgy(props);

// Sanitized Computed Properties
const sanitizedLiturgy1 = computed(() => DOMPurify.sanitize(props.liturgies.liturgy1));
const sanitizedLiturgyPsalms = computed(() => DOMPurify.sanitize(props.liturgies.liturgypsalms));
const sanitizedLiturgy2 = computed(() => props.liturgies.liturgy2 ? DOMPurify.sanitize(props.liturgies.liturgy2) : null);
const sanitizedLiturgyGospel = computed(() => DOMPurify.sanitize(props.liturgies.liturgygospel));

</script>

<style scoped lang="scss">
@font-face {
  font-family: "Gilroy";
  src: url("/resources/src/fonts/Gilroy-Light.ttf");

  font-weight: 300;
  font-style: normal;
}

@font-face {
  font-family: "Gilroy";
  src: url("/resources/src/fonts/Gilroy-Regular.ttf");

  font-weight: 400;
  font-style: normal;
}

@font-face {
  font-family: "Gilroy";
  src: url("/resources/src/fonts/Gilroy-Semibold.ttf");

  font-weight: 700;
  font-style: normal;
}

@media (max-width: $breakpoint-xs-max) {

  .container,
  .page-container {
    padding: 0;
    margin: 0;
  }

  .liturgy-content :global(p) {
    text-align: start !important;
  }
}
</style>
