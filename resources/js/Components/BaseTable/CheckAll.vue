<script setup>
import { defineEmits, ref, watch } from "vue";
import Checkbox from "@/Components/Checkbox.vue";

const emit = defineEmits(["update-all-selection"]);

let props = defineProps({
  records: {
    type: Array,
    required: true,
  },
});

const selectAll = ref(false);
const selected = ref([]);

function checkAll() {
  selected.value = [];
  if (selectAll.value) {
    selected.value = props.records.map((record) => record.data.id); // Select all IDs
  }
  emit("update-all-selection", selected.value); // Emit the full list of selected IDs
}

watch(selectAll, checkAll); // Watch selectAll for changes
</script>

<template>
  <div class="flex items-center">
    <label class="inline-flex">
      <span class="sr-only">Select all</span>
      <Checkbox v-model="selectAll" />
    </label>
  </div>
</template>
