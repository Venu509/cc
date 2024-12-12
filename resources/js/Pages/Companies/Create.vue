<template>
  <AppLayout
      title="Companies"
  >
    <CompanyForm
        :model-value="company"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import CompanyForm from "@/Components/Companies/CompanyForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  company: {
    type: Object,
    required: true,
  },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.companies.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Company' : "Add Company";
});

const title = computed(() => {
  if (isEditing.value) return "Edit Company";
  if (!isEditing.value) return "Add Company";
});

const pages = [
  {name: "Companies", route: "admin.companies.index", current: false},
  {name: title.value, route: "admin.companies.create", current: true},
];

</script>

<style scoped>

</style>
