<script setup>
import AnsweredQuestion from "@/Components/Vacancies/Partials/AnsweredQuestion.vue";

const emit = defineEmits(["update", "delete"]);

let props = defineProps({
  answeredQuestions: {
    type: Array,
    required: true,
  },
});

</script>

<template>
  <AnsweredQuestion
      class="items-center p-4 border border-gray-400 rounded-lg"
      v-for="(answeredQuestion, mainIndex) in answeredQuestions"
      :key="mainIndex"
      :mainIndex="mainIndex"
      :answered-question="answeredQuestion"/>
</template>

<style scoped>
.transition-all {
  transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
</style>
