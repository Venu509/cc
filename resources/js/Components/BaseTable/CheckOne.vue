<script setup>
import { computed } from "vue";
import Checkbox from "@/Components/Checkbox.vue";

let props = defineProps({
  record: {
    type: Object,
    required: true,
  },
  value: {
    type: String,
    required: true,
  },
  selected: {
    type: Array,
    default: () => [],
  },
});

let emit = defineEmits(["update-selected"]);

function check(checked) {
  emit("update-selected", { id: props.record.id, checked });
}

const checked = computed(() => props.selected.includes(props.record.id));
</script>

<template>
  <div class="flex items-center">
    <label class="inline-flex">
      <span class="sr-only">Select</span>
      <Checkbox
          :id="record.id"
          :checked="checked"
          @update:checked="check"
      />
    </label>
  </div>
</template>
