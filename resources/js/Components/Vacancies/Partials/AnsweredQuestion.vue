<script setup>
import { ref } from 'vue';

const emit = defineEmits(["update", "delete"]);

const props = defineProps({
  answeredQuestion: {
    type: Object,
    required: true,
  },
  mainIndex: {
    type: Number,
    default: 1,
  }
});

const activeIndex = ref(null);

const toggleAccordion = (index) => {
  activeIndex.value = (activeIndex.value === index) ? null : index;
};

// Function to return dynamic color based on matchingState
const getHeaderStyles = (state) => {
  switch (state) {
    case 'high':
      return 'text-green-600 bg-green-100 dark:text-green-400 dark:bg-green-900';
    case 'medium':
      return 'text-yellow-600 bg-yellow-100 dark:text-yellow-400 dark:bg-yellow-900';
    case 'low':
      return 'text-red-600 bg-red-100 dark:text-red-400 dark:bg-red-900';
    default:
      return 'text-gray-600 bg-gray-100 dark:text-gray-300 dark:bg-gray-700';
  }
};
</script>

<template>
  <div id="accordion-flush" class="my-4">
    <h2
        :id="`candidate-experience-heading-${mainIndex}`"
        :class="[
            getHeaderStyles(answeredQuestion.matchingState),
            'rounded px-3'
        ]"
    >
      <button
          type="button"
          :aria-expanded="activeIndex === mainIndex"
          @click="toggleAccordion(mainIndex)"
          class="flex justify-between w-full py-3 font-medium text-base border-b border-gray-300 dark:border-gray-700 gap-3"
          :aria-controls="`candidate-experience-accordion-${mainIndex}`"
      >
        <span class="capitalize">{{ answeredQuestion.question }}</span>
        <svg
            class="w-4 h-4 transition-transform"
            :class="{ 'rotate-180': activeIndex === mainIndex }"
            fill="none"
            viewBox="0 0 10 6"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
          <path
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5 5 1 1 5"
          />
        </svg>
      </button>
    </h2>

    <div
        :id="`candidate-experience-accordion-${mainIndex}`"
        :class="{'block': activeIndex === mainIndex, 'hidden': activeIndex !== mainIndex}"
        class="transition-all duration-300 ease-in-out mt-2"
        :aria-labelledby="`candidate-experience-heading-${mainIndex}`"
    >
      <div class="mb-1">
        <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">Given: </span>
        <span class="text-sm text-gray-700 dark:text-gray-200">{{ answeredQuestion.givenAnswer || 'No answer provided' }}</span>
      </div>

      <div class="mb-1">
        <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">Expected: </span>
        <span class="text-sm text-gray-700 dark:text-gray-200">
          <span v-for="(answer, index) in answeredQuestion.expectedAnswers" :key="index">
            {{ answer }}<span v-if="index !== answeredQuestion.expectedAnswers.length - 1">, </span>
          </span>
        </span>
      </div>

      <div class="flex items-center space-x-2">
        <span class="font-semibold text-sm text-gray-700 dark:text-gray-300">Matching State: </span>
        <span
            :class="{
            'text-green-600 bg-green-100 dark:bg-green-900 dark:text-green-400': answeredQuestion.matchingState === 'high',
            'text-yellow-600 bg-yellow-100 dark:bg-yellow-900 dark:text-yellow-400': answeredQuestion.matchingState === 'medium',
            'text-red-600 bg-red-100 dark:bg-red-900 dark:text-red-400': answeredQuestion.matchingState === 'low'
          }"
            class="px-2 py-1 rounded-lg font-semibold text-sm"
        >
          {{ answeredQuestion.matchingStateLabel }}
        </span>
      </div>
    </div>
  </div>
</template>

<style scoped>
.transition-all {
  transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
</style>
