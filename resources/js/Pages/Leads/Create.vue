<template>
  <AppLayout
      title="Leads"
  >
    <LeadForm
        :model-value="lead"
        :types="types"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import LeadForm from "@/Components/Leads/LeadForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  lead: {
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
  return route().current("admin.leads.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Lead' : "Add Lead";
});

const title = computed(() => {
  if (isEditing.value) return "Edit Lead";
  if (!isEditing.value) return "Add Lead";
});

</script>

<style scoped>

</style>
