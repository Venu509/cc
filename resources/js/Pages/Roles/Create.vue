<template>
  <AppLayout
      :title="pageTitle"
  >
    <RoleForm
        :model-value="role"
        :permissions="permissions"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import RoleForm from "@/Components/Roles/RoleForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  role: {
    type: Object,
    required: true,
  },
  permissions: {
    type: Object,
    required: true,
  },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.roles.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Role' : "Add Role";
});

const title = computed(() => {
  if (isEditing.value) return "Edit Role";
  if (!isEditing.value) return "Add Role";
});

const pages = [
  {name: "Roles", route: "admin.roles.index", current: false},
  {name: title.value, route: "admin.roles.create", current: true},
];

</script>

<style scoped>

</style>
