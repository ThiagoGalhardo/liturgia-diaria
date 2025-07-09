<template>
  <q-input v-model="formattedDate" :label="label" :rules="rules" :disable="disable" :dark="dark" :dense="dense" outlined
    @click="openDatePicker">
    <template v-slot:append>
      <q-icon name="event" class="cursor-pointer" @click="openDatePicker">
        <q-popup-proxy ref="qDateProxy" cover transition-show="scale" transition-hide="scale">
          <q-date v-model="dateValue" mask="YYYY-MM-DD" @update:model-value="onDateSelect">
            <div class="row items-center justify-end">
              <q-btn v-close-popup label="Fechar" color="primary" flat />
            </div>
          </q-date>
        </q-popup-proxy>
      </q-icon>
    </template>
  </q-input>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import moment from 'moment'

const props = defineProps({
  modelValue: String,
  label: {
    type: String,
    default: 'Data'
  },
  dark: {
    type: Boolean,
    default: false
  },
  disable: {
    type: Boolean,
    default: false
  },
  dense: {
    type: Boolean,
    default: false
  },
  rules: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue'])

const dateValue = ref(props.modelValue)
const qDateProxy = ref(null)

const formattedDate = computed({
  get: () => {
    if (!dateValue.value) return ''
    return moment(dateValue.value, 'YYYY-MM-DD').format('DD-MM-YYYY')
  },
  set: (val) => {
    if (!val) {
      dateValue.value = null
      emit('update:modelValue', null)
    } else {
      const parsed = moment(val, 'DD-MM-YYYY', true)
      if (parsed.isValid()) {
        dateValue.value = parsed.format('YYYY-MM-DD')
        emit('update:modelValue', dateValue.value)
      }
    }
  }
})

watch(() => props.modelValue, (newVal) => {
  dateValue.value = newVal
})

const openDatePicker = () => {
  qDateProxy.value.show()
}

const onDateSelect = (value) => {
  // Usa a data exatamente como selecionada, sem ajustes de fuso horário
  dateValue.value = value
  emit('update:modelValue', value)
  qDateProxy.value.hide()
}
</script>