<template>
  <AppLayout
      title="Projects"
  >
    <ProjectForm
        :model-value="project"
        :branches="branches"
        :isEditing="isEditing"
        :projectsNames="projectsNames"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import ProjectForm from "@/Components/Projects/ProjectForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  project: {
    type: Object,
    required: true,
  },
  branches: {
    type: Object,
    required: true,
  },
  projectsNames: {
    type: Object,
    required: true,
    },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.projects.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Project' : "Add Project";
});

const title = computed(() => {
  if (isEditing.value) return "Edit project Order";
  if (!isEditing.value) return "Add project Order";
});

const pages = [
  {name: "Projects", route: "admin.projects.index", current: false},
  {name: title.value, route: "admin.projects.create", current: true},
];

</script>

<style scoped>

</style>
