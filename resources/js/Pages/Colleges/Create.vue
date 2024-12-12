<template>
  <AppLayout
      title="Collages"
  >
    <CollegeForm
        :model-value="college"
        :types="types"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import CollegeForm from "@/Components/Colleges/CollegeForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  college: {
    type: Object,
    required: true,
  },
  types: {
    type: Object,
    required: true,
  }
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.colleges.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update College' : "Add College";
});

const title = computed(() => {
  if (isEditing.value) return "Edit College";
  if (!isEditing.value) return "Add College";
});

const pages = [
  {name: "Colleges", route: "admin.colleges.index", current: false},
  {name: title.value, route: "admin.colleges.create", current: true},
];

</script>

<style scoped>

</style>
