<script setup>
import Experience from "@/Components/Candidates/Partials/Experiences/Experience.vue";
import {computed} from "vue";
import Question from "@/Components/Vacancies/Partials/Question.vue";

const emit = defineEmits(["update:additionalQuestions"]);

let props = defineProps({
  additionalQuestions: {
    type: Array,
    required: true,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
});

const indexedAdditionalQuestions = computed(() => {
  return [
    ...props.additionalQuestions.map((additionalQuestion, index) => {
      return {
        ...additionalQuestion,
        index,
      };
    }),
  ];
});

function updateAdditionalQuestions(modifiedAdditionalQuestion) {
  const updatedAdditionalQuestions = [
    ...indexedAdditionalQuestions.value.map((originalAdditionalQuestion) => {
      if (originalAdditionalQuestion.index === modifiedAdditionalQuestion.index) {
        return modifiedAdditionalQuestion;
      }

      return originalAdditionalQuestion;
    }),
  ];

  emit("update:additionalQuestions", updatedAdditionalQuestions);
}

function deleteAdditionalQuestions(deletedInvoiceItem) {
  const remainingAdditionalQuestions = [
    ...indexedAdditionalQuestions.value.filter((originalAdditionalQuestion) => {
      return originalAdditionalQuestion.index !== deletedInvoiceItem.index;
    }),
  ];

  emit("update:additionalQuestions", remainingAdditionalQuestions);
}
</script>

<template>
  <Question
      class="items-center p-4 border border-gray-400 rounded-lg"
      v-for="additionalQuestion in indexedAdditionalQuestions"
      :key="additionalQuestion.index"
      :is-editing="isEditing"
      @delete="deleteAdditionalQuestions(additionalQuestion)"
      @update="updateAdditionalQuestions"
      :additional-question="additionalQuestion"/>
</template>