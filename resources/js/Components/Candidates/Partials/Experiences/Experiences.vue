<script setup>
import Experience from "@/Components/Candidates/Partials/Experiences/Experience.vue";
import {computed} from "vue";


const emit = defineEmits(["update:candidateExperiences"]);

let props = defineProps({
  candidateExperiences: {
    type: Array,
    required: true,
  },
  form: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
  maxDate: {
    type: Function,
    required: true,
  },
});

const indexedCandidateExperiences = computed(() => {
  return [
    ...props.candidateExperiences.map((candidateExperience, index) => {
      return {
        ...candidateExperience,
        index,
      };
    }),
  ];
});

function updateCandidateExperiences(modifiedCandidateExperience) {
  const updatedCandidateExperiences = [
    ...indexedCandidateExperiences.value.map((originalCandidateExperience) => {
      if (originalCandidateExperience.index === modifiedCandidateExperience.index) {
        return modifiedCandidateExperience;
      }

      return originalCandidateExperience;
    }),
  ];

  emit("update:candidateExperiences", updatedCandidateExperiences);
}

function deleteCandidateExperiences(deletedInvoiceItem) {
  const remainingCandidateExperiences = [
    ...indexedCandidateExperiences.value.filter((originalCandidateExperience) => {
      return originalCandidateExperience.index !== deletedInvoiceItem.index;
    }),
  ];

  emit("update:candidateExperiences", remainingCandidateExperiences);
}
</script>

<template>
  <Experience
      class="items-center p-4 border border-secondary-400 rounded-xl"
      v-for="candidateExperience in indexedCandidateExperiences"
      :key="candidateExperience.index"
      :is-editing="isEditing"
      @delete="deleteCandidateExperiences(candidateExperience)"
      @update="updateCandidateExperiences"
      :candidate-experience="candidateExperience"
      :max-date="maxDate"
      :loop="candidateExperience.index"
      :form="form"
      :errors="errors"
  />
</template>