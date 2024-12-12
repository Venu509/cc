<script setup>
import { ref } from "vue";

const props = defineProps({
  workExperiences: {
    type: Array,
    required: true
  }
});

const activeAccordion = ref(0);

function toggleAccordion(index) {
  activeAccordion.value = activeAccordion.value === index ? null : index;
}

function formatDate(date) {
  const options = { year: 'numeric', month: 'short' };
  return new Date(date).toLocaleDateString(undefined, options);
}
</script>

<template>
  <div id="accordion-flush">
    <div v-if="workExperiences.length > 0" v-for="(experience, index) in workExperiences" :key="index" class="rounded-lg shadow-lg mb-4 overflow-hidden">
      <h2 :id="`accordion-flush-heading-${index}`">
        <button type="button"
                class="flex items-center justify-between w-full py-5 px-6 text-lg font-semibold transition-colors duration-300 border-b border-gray-200 dark:border-gray-700"
                @click="toggleAccordion(index)"
                :aria-expanded="activeAccordion === index ? 'true' : 'false'"
                :aria-controls="`accordion-flush-body-${index}`"
                :class="{
                  'bg-gradient-to-r from-secondary-100 via-secondary-200 to-secondary-300 text-gray-900': activeAccordion === index,
                  'bg-gray-100 text-gray-500': activeAccordion !== index
                }">
          <span>{{ experience.jobTitle }} at {{ experience.company }} ({{ formatDate(experience.startDate) }} - {{ experience.isStillWorking ? 'Present' : formatDate(experience.endDate) }})</span>
          <svg class="w-5 h-5 transition-transform duration-300 shrink-0" :class="{ 'rotate-180': activeAccordion === index }" aria-hidden="true"
               xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5 5 1 1 5"/>
          </svg>
        </button>
      </h2>

      <div :id="`accordion-flush-body-${index}`"
           :class="{ 'hidden': activeAccordion !== index }"
           :aria-labelledby="`accordion-flush-heading-${index}`">
        <div class="py-5 px-6 bg-gray-50 dark:bg-gray-800 transition-all duration-500 ease-in-out border-b border-gray-200 dark:border-gray-700">
          <div v-if="experience.responsibilities" class="mb-4">
            <h3 class="font-semibold text-secondary-700 dark:text-secondary-300">Responsibilities:</h3>
            <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ experience.responsibilities }}</p>
          </div>

          <div v-if="experience.achievements" class="mb-4">
            <h3 class="font-semibold text-secondary-700 dark:text-secondary-300">Achievements:</h3>
            <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ experience.achievements }}</p>
          </div>

          <div v-if="experience.otherExperiences" class="mb-4">
            <h3 class="font-semibold text-secondary-700 dark:text-secondary-300">Other Experiences:</h3>
            <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">{{ experience.otherExperiences }}</p>
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <p>No Related Work Experiences added.</p>
    </div>
  </div>
</template>


