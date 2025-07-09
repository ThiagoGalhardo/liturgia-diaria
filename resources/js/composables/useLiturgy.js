import { ref, watch, onMounted, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useQuasar } from 'quasar';
import moment from 'moment';

export function useLiturgy(props) {
  const $q = useQuasar();

  const darkMode = ref(false);
  const fontSize = ref(18);
  const dateSelected = ref(props.date || moment().format("YYYY-MM-DD"));
  const loading = ref(false);
  const errorMessage = ref(props.error || '');
  const continueAnotherDate = ref(false);

  const goToDate = (date) => {
    loading.value = true;
    errorMessage.value = '';
    Inertia.visit(`/${date}`, {
      method: 'get',
      preserveState: true,
      preserveScroll: true,
      only: ['liturgies', 'error'],
      onFinish: () => { loading.value = false; }
    });
  };

  watch(dateSelected, (newValue) => {
    if (newValue) goToDate(newValue);
  });

  watch(darkMode, (isDark) => {
    $q.dark.set(isDark);
    $q.localStorage.set("darkMode", isDark);
  });

  watch(() => props.error, (newError) => {
    errorMessage.value = newError || '';
  });

  const increaseFontSize = () => {
    fontSize.value++;
    $q.localStorage.set("fontSize", fontSize.value);
  };
  const decreaseFontSize = () => {
    if (fontSize.value > 1) {
      fontSize.value--;
      $q.localStorage.set("fontSize", fontSize.value);
    }
  };

  const hasReadings = computed(() => {
    return props.liturgies && Object.keys(props.liturgies).length > 0 && props.liturgies.liturgy1;
  });

  onMounted(() => {
    darkMode.value = $q.localStorage.getItem("darkMode") || false;
    fontSize.value = $q.localStorage.getItem("fontSize") || 18;
    $q.dark.set(darkMode.value);
  });

  return {
    darkMode,
    fontSize,
    dateSelected,
    loading,
    errorMessage,
    continueAnotherDate,
    increaseFontSize,
    decreaseFontSize,
    hasReadings
  };
}
