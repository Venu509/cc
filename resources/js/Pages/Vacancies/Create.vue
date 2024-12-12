<template>
  <AppLayout
      title="Vacancies"
  >
    <VacancyForm
        :model-value="vacancy"
        :work-modes="workModes"
        :qualifications="qualifications"
        :benefits="benefits"
        :locations="locations"
        :key-skills="keySkills"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import VacancyForm from "@/Components/Vacancies/VacancyForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  vacancy: {
    type: Object,
    required: true,
  },
  workModes: {
    type: Object,
    required: true,
  },
  qualifications: {
    type: Object,
    required: true,
  },
  benefits: {
    type: Object,
    required: true,
  },
  locations: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.vacancies.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Vacancy' : "Add Vacancy";
});

const title = computed(() => {
  if (isEditing.value) return "Edit Vacancy";
  if (!isEditing.value) return "Add Vacancy";
});

const pages = [
  {name: "Vacancies", route: "admin.vacancies.index", current: false},
  {name: title.value, route: "admin.vacancies.create", current: true},
];

</script>

<style scoped>

</style>
