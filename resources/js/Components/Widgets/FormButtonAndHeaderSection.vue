<script setup>
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";

let props = defineProps({
  isSaving: {
    type: Boolean,
    default: false,
  },
  buttonText: {
    type: String,
    default: "Save",
  },
  title: {
    type: String,
    required: true,
  },
});

let emit = defineEmits(["save"]);

function back() {
  window.history.back();
  Inertia.reload()
}

function save() {
  emit("save")
}
</script>

<template>
  <div class="flex items-center justify-between mt-4">
    <h4 class="text-slate-900 text-lg font-medium">{{ title }}</h4>

    <div class="flex ml-auto">
      <Button
          class="mr-3"
          btn-color="dark"
          :is-rounded="true"
          @click="back()"
      >
        Back
      </Button>

      <Button
          :disabled="isSaving"
          :is-rounded="true"
          @click="save"
      >
        {{ buttonText }}
      </Button>
    </div>
  </div>
</template>

<style scoped>

</style>