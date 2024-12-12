<template>
  <AppLayout
      title="Candidates"
  >
    <CandidateForm
        :model-value="candidate"
        :noticePeriods="noticePeriods"
        :noOfExperiences="noOfExperiences"
        :qualifications="qualifications"
        :key-skills="keySkills"
        :industries="industries"
        :countries="countries"
        :user-preferred-locations="userPreferredLocations"
        :job-types="jobTypes"
        :employment-status="employmentStatus"
        :isEditing="isEditing"
    />

  </AppLayout>
</template>

<script setup>

import AppLayout from "@/Layouts/AppLayout.vue";
import CandidateForm from "@/Components/Candidates/CandidateForm.vue";
import {computed, ref} from "vue";

let props = defineProps({
  candidate: {
    type: Object,
    required: true,
  },
  noticePeriods: {
    type: Object,
    required: true,
  },
  noOfExperiences: {
    type: Object,
    required: true,
  },
  qualifications: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
  industries: {
    type: Object,
    required: true,
  },
  jobTypes: {
    type: Object,
    required: true,
  },
  employmentStatus: {
    type: Object,
    required: true,
  },
  countries: {
    type: Object,
    required: true,
  },
  userPreferredLocations: {
    type: Object,
    required: true,
  },
});

let isLoading = ref(true)

const isEditing = computed(() => {
  return route().current("admin.candidates.show");
});

const pageTitle = computed(() => {
  return isEditing.value ? 'Update Candidate' : "Add Candidate";
});

const title = computed(() => {
  if (isEditing.value) return "Edit Candidate";
  if (!isEditing.value) return "Add Candidate";
});

const pages = [
  {name: "Candidates", route: "admin.candidates.index", current: false},
  {name: title.value, route: "admin.candidates.create", current: true},
];

</script>

<style scoped>

</style>
