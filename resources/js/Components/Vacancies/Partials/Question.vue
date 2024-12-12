<script setup>
import {computed, ref, watch} from 'vue';
import Input from "@/Components/Input.vue";
import Label from "@/Components/Label.vue";
import TagInput from "@/Components/TagInput.vue";

const emit = defineEmits(["update", "delete"]);

const props = defineProps({
  additionalQuestion: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
});

const experienceLabel = computed(() => {
  return props.additionalQuestion.question && props.additionalQuestion.question.trim() !== ''
      ? (props.additionalQuestion.question)
      : `Question ${mainIndex.value + 1}`;
});

const activeIndex = ref(0);
const mainIndex = computed(() => props.additionalQuestion.index);

function clickDeleteAction() {
  return emit("delete", props.additionalQuestion);
}

let selectedQuestion = computed({
  get: () => props.additionalQuestion.question,
  set: (question) => emit("update", {...props.additionalQuestion, question}),
});

let selectedAnswers = computed({
  get: () => props.additionalQuestion.answers,
  set: (answers) => emit("update", {...props.additionalQuestion, answers}),
});

const toggleAccordion = () => {
  activeIndex.value = (activeIndex.value === mainIndex.value) ? null : mainIndex.value;
};

</script>

<template>
  <div id="accordion-flush" class="my-2">
    <h2 :id="`candidate-experience-heading-${mainIndex}`">
      <button
          type="button"
          :aria-expanded="activeIndex === mainIndex"
          @click="toggleAccordion"
          class="flex items-center justify-between w-full py-2 font-bold text-lg text-gray-500 border-b border-gray-400 dark:border-gray-700 dark:text-gray-400 gap-3"
          :aria-controls="`candidate-experience-accordion-${mainIndex}`"
      >
        <span class="capitalize">{{ experienceLabel }}</span>
        <svg
            class="w-3 h-3 transition-transform"
            :class="{ 'rotate-180': activeIndex === mainIndex }"
            fill="none"
            viewBox="0 0 10 6"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
        </svg>
      </button>
    </h2>

    <div
        :id="`candidate-experience-accordion-${mainIndex}`"
        :class="{'block': activeIndex === mainIndex, 'hidden': activeIndex !== mainIndex}"
        class="transition-all duration-300 ease-in-out"
        :aria-labelledby="`candidate-experience-heading-${mainIndex}`"
    >
      <div class="pt-5 pb-2 border-b border-gray-200 dark:border-gray-700">
        <div class="grid lg:grid-cols-1 gap-3">
          <div class="col-span-1">
            <Label :required="true">Question</Label>
            <Input
                id="selectedQuestion"
                v-model="selectedQuestion"
                autocomplete="selectedQuestion"
                name="selectedQuestion"
                type="text"
            />
          </div>
        </div>
        <div class="grid lg:grid-cols-1 gap-3">
          <div class="col-span-1">
            <Label :required="true">Expected Answers</Label>
            <TagInput
                v-model="selectedAnswers"
                placeholder="Your answers"
                class="relative w-full"
                required
            >
            </TagInput>
          </div>
        </div>

        <div
            class="flex flex-row mt-3"
            v-if="props.additionalQuestion.index !== 0"
        >
          <span
              @click="clickDeleteAction"
              class="cursor-pointer text-gray-900 bg-[#F7BE38] hover:bg-[#F7BE38]/90 focus:ring-4 focus:outline-none focus:ring-[#F7BE38]/50 font-medium rounded-lg px-3 py-2 text-xs text-center inline-flex items-center dark:focus:ring-[#F7BE38]/50 me-2 mb-2">
            Remove
            <TrashIcon
                class="h-5 w-5 text-red-400 hover:text-red-600 cursor-pointer align-items-end"
            />
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.transition-all {
  transition: max-height 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
</style>
