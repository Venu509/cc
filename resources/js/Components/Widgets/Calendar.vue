<template>
  <VueDatePicker
      v-model="internalDate"
      :id="name"
      :name="name"
      :min-date="minimumDate"
      :max-date="maximumDate"
      :highlighted="highlightedDates"
      :month-change-on-scroll="true"
      :esc-close="true"
      :space-confirm="true"
      :month-change-on-arrows="true"
      :arrow-navigation="true"
      :enableTimePicker="false"
      :prevent-min-max-navigation="true"
      class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-xl shadow-sm"
      @update:model-value="handleInput"
      @cleared="clear"
  />
</template>

<script setup>
import { ref, defineProps, defineEmits, computed } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import {validateField} from "@/Components/Services/Validation";

const emit = defineEmits(["update:model"]);

const props = defineProps({
  model: {
    type: String,
    required: true,
  },
  name: {
    type: String,
    default: 'date-picker',
  },
  minDate: {
    type: [String, null],
    default: null,
  },
  maxDate: {
    type: [String, null],
    default: null,
  },
  fieldName: {
    type: String,
    required: true,
  },
});

// Highlight today's date
const today = new Date().toISOString().split('T')[0];
const highlightedDates = ref([{ date: today, class: 'today-highlight' }]);

let internalDate = ref(props.model ?? null);

function handleInput(date) {
  const formattedDate = date ? date.toISOString().split('T')[0] : null;
  internalDate.value = formattedDate;
  emit('update:model', formattedDate);

  validateField(props.fieldName, { [props.fieldName]: formattedDate });
}

// Clear the date
function clear() {
  emit('update:model', null);
  validateField(props.fieldName, { [props.fieldName]: null });
}

// Compute minimum and maximum dates
const minimumDate = computed(() => {
  return props.minDate ?? null;
});

const maximumDate = computed(() => {
  return props.maxDate ?? null;
});

</script>

<style>
.today-highlight {
  background-color: #ffeb3b; /* Use your preferred highlight color */
  border-radius: 50%;
}
</style>
