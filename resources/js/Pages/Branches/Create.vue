<template>
  <AppLayout
      title="Branches"
  >
    <BranchForm
        :model-value="branch"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import BranchForm from "@/Components/Branches/BranchForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  branch: {
    type: Object,
    required: true,
  }
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.branches.show");
});

const title = computed(() => {
  if (isEditing.value) return "Edit Branch";
  if (!isEditing.value) return "Add Branch";
});

const pages = [
  {name: "Branches", route: "admin.branches.index", current: false},
  {name: title.value, route: "admin.branches.create", current: true},
];

</script>

<style scoped>

</style>
