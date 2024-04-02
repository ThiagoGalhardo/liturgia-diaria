<template>
  <q-layout view="lHh lpr lFf">
    <q-header>
      <q-toolbar elevated class="bg-grey-1 text-white shadow-2 q-py-md q-ma-none"
        :class="{ 'bg-grey-10 shadow-0': darkMode }">
        <q-img src="img/nossa-senhora-aparecida.webp" width="42px" class="q-ml-md"></q-img>

        <div class="q-ml-md text-subtitle1 text-weight-bold" :class="{ 'text-black': !darkMode }">Liturgia
          Diária</div>
        <q-space />

        <q-toggle class="q-pr-md q-py-xs" color="purple-10" dense keep-color v-model="darkMode"
          checked-icon="mdi-weather-night" unchecked-icon="mdi-white-balance-sunny" size="lg" />
      </q-toolbar>
    </q-header>

    <q-page-container class="page-container q-mx-xl q-px-xl">

      <Error v-if="!hasReadings" />
      <Skeleton v-if="!hasReadings" :class="{ 'bg-grey-10 shadow-0': darkMode }" />
      <Skeleton v-if="!hasReadings" :class="{ 'bg-grey-10 shadow-0': darkMode }" />
      <Skeleton v-if="!hasReadings" :class="{ 'bg-grey-10 shadow-0': darkMode }" />


      <div class="container q-px-xl">
        <q-card v-if="hasReadings" class="q-mt-lg q-mx-md" :class="{ 'bg-grey-10 shadow-0': darkMode }">
          <q-card-section>
            <div class="text-h6" v-html="liturgy_today.liturgy1"></div>
          </q-card-section>
        </q-card>

        <q-card v-if="hasReadings" class="q-mt-lg q-mx-md" :class="{ 'bg-grey-10 shadow-0': darkMode }">
          <q-card-section>
            <div class="text-h6" v-html="liturgy_today.liturgypsalms"></div>
          </q-card-section>
        </q-card>

        <q-card v-if="liturgy_today?.liturgy2 != '' && liturgy_today?.liturgy2 != null" class="q-mt-lg q-mx-md"
          :class="{ 'bg-grey-10 shadow-0': darkMode }">
          <q-card-section>
            <div class="text-h6" v-html="liturgy_today.liturgy2"></div>
          </q-card-section>
        </q-card>

        <q-card v-if="hasReadings" class="q-my-lg q-mx-md" :class="{ 'bg-grey-10 shadow-0': darkMode }">
          <q-card-section>
            <div class="text-h6" v-html="liturgy_today.liturgygospel"></div>
          </q-card-section>
        </q-card>
      </div>
    </q-page-container>

    <q-toolbar class="bg-grey-1 text-white shadow-4 q-py-md q-ma-none row" :class="{ 'bg-grey-10 shadow-0': darkMode }">
      <div class="col text-subtitle1 text-center" :class="{ 'text-black': !darkMode }">
        Desenvolvido com ❤️ por
        <a href="https://galhardo.dev/" target="_blank">Thiago Galhardo</a>
      </div>
      <q-space />
    </q-toolbar>
  </q-layout>
</template>


<script setup>
import { useQuasar, Screen } from "quasar";
import { ref, watch, onMounted, computed } from "vue";
import Skeleton from "../Components/Skeleton.vue"
import Error from "../Components/Error.vue"

const props = defineProps({
  liturgy_today: {
    type: Object,
    default: () => ({}),
  },
})

const $q = useQuasar()
const darkMode = ref(false)

const hasReadings = () => {
  if (props.liturgy_today) return true
}

watch(darkMode, (darkMode) => {
  $q.dark.set(darkMode)
  $q.localStorage.set("darkMode", darkMode)
})

onMounted(() => {
  const darkModeIsActive = $q.localStorage.getItem("darkMode")
  if (darkModeIsActive) darkMode.value = true
})

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
}
</style>
