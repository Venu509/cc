<template>
  <AppLayout
      title="Workshop Names"
  >
    <WorkshopNameForm
        :model-value="workshopName"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import WorkshopNameForm from "@/Components/WorkshopsNames/WorkshopNameForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  workshopName: {
    type: Object,
    required: true,
  },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.workshops-names.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Workshop' : "Add Workshop";
});

const title = computed(() => {
  if (isEditing.value) return "Edit workshop Order";
  if (!isEditing.value) return "Add workshop Order";
});

const pages = [
  {name: "Workshops", route: "admin.workshops-names.index", current: false},
  {name: title.value, route: "admin.workshops-names.create", current: true},
];

</script>

<style scoped>

</style>
