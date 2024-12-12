<template>
  <AppLayout
      title="Project Names"
  >
    <ProjectNameForm
        :model-value="projectName"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import ProjectNameForm from "@/Components/ProjectsNames/ProjectNameForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  projectName: {
    type: Object,
    required: true,
  },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.projects-names.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Project' : "Add Project";
});

const title = computed(() => {
  if (isEditing.value) return "Edit project Order";
  if (!isEditing.value) return "Add project Order";
});

const pages = [
  {name: "Projects", route: "admin.projects-names.index", current: false},
  {name: title.value, route: "admin.projects-names.create", current: true},
];

</script>

<style scoped>

</style>
