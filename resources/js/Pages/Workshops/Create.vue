<template>
  <AppLayout
      title="Workshops"
  >
    <WorkshopForm
        :model-value="workshop"
        :branches="branches"
        :workshopsNames="workshopsNames"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import WorkshopForm from "@/Components/Workshops/WorkshopForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  workshop: {
    type: Object,
    required: true,
  },
  branches: {
    type: Object,
    required: true,
  },
  workshopsNames: {
    type: Object,
    required: true,
  }
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.workshops.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Workshop' : "Add Workshop";
});

const title = computed(() => {
  if (isEditing.value) return "Edit workshop Order";
  if (!isEditing.value) return "Add workshop Order";
});

const pages = [
  {name: "Workshops", route: "admin.workshops.index", current: false},
  {name: title.value, route: "admin.workshops.create", current: true},
];

</script>

<style scoped>

</style>
