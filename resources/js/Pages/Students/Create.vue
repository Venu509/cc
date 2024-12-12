<template>
  <AppLayout
      title="Students"
  >
    <StudentForm
        :model-value="student"
        :branches="branches"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import StudentForm from "@/Components/Students/StudentForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  student: {
    type: Object,
    required: true,
  },
  branches: {
    type: Object,
    required: true,
  }
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.students.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Student' : "Add Student";
});

const title = computed(() => {
  if (isEditing.value) return "Edit student Order";
  if (!isEditing.value) return "Add student Order";
});

const pages = [
  {name: "Students", route: "admin.students.index", current: false},
  {name: title.value, route: "admin.students.create", current: true},
];

</script>

<style scoped>

</style>
