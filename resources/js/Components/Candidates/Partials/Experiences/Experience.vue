<script setup>
import {computed, ref, watch} from 'vue';
import Input from "@/Components/Input.vue";
import Textarea from "@/Components/Textarea.vue";
import Label from "@/Components/Label.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import {maxDate, validateField} from "@/Components/Services/Validation";
import InputError from "@/Components/InputError.vue";
import Calendar from "@/Components/Widgets/Calendar.vue";

const emit = defineEmits(["update", "delete"]);

const props = defineProps({
  candidateExperience: {
    type: Object,
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
  loop: {
    type: Number,
    default: 0,
  },
  maxDate: {
    type: Function,
    required: true,
  },
});

const experienceLabel = computed(() => {
  return props.candidateExperience.companyName && props.candidateExperience.companyName.trim() !== ''
      ? (props.candidateExperience.jobTitle + ' At ' + props.candidateExperience.companyName)
      : `Experience ${mainIndex.value + 1}`;
});

const experienceDuration = computed(() => {
  const { startDate, endDate, isCurrentEmployer } = props.candidateExperience;

  if (!startDate) return '';

  const formattedStartDate = formatDate(startDate);

  const formattedEndDate = isCurrentEmployer === 'yes' ? 'Present' : (endDate ? formatDate(endDate) : 'Not Provided');

  return `(${formattedStartDate} - ${formattedEndDate})`;
});

const activeIndex = ref(0);
const mainIndex = computed(() => props.candidateExperience.index);

function clickDeleteAction() {
  return emit("delete", props.candidateExperience);
}

let selectedCompanyName = computed({
  get: () => props.candidateExperience.companyName,
  set: (companyName) => {
    emit("update", { ...props.candidateExperience, companyName });
    validateField('companyName', props.form, null, 'candidateExperiences', props.loop);
  },
});

let selectedJobTitle = computed({
  get: () => props.candidateExperience.jobTitle,
  set: (jobTitle) => {
    emit("update", { ...props.candidateExperience, jobTitle });
    validateField('jobTitle', props.form, null, 'candidateExperiences', props.loop);
  },
});

let selectedStartDate = computed({
  get: () => props.candidateExperience.startDate,
  set: (startDate) => {
    emit("update", { ...props.candidateExperience, startDate });
    // validateField('startDate', props.form, null, 'candidateExperiences', props.loop);
  },
});

let selectedEndDate = computed({
  get: () => props.candidateExperience.endDate,
  set: (endDate) => {
    emit("update", { ...props.candidateExperience, endDate });
    // validateField('endDate', props.form, null, 'candidateExperiences', props.loop);
  },
});

let selectedIsCurrentEmployment = computed({
  get: () => props.candidateExperience.isCurrentEmployer,
  set: (isCurrentEmployer) => emit("update", {...props.candidateExperience, isCurrentEmployer}),
});

let selectedResponsibilities = computed({
  get: () => props.candidateExperience.responsibilities,
  set: (responsibilities) => {
    emit("update", { ...props.candidateExperience, responsibilities });
    validateField('responsibilities', props.form, null, 'candidateExperiences', props.loop);
  },
});

let selectedAchievements = computed({
  get: () => props.candidateExperience.achievements,
  set: (achievements) => emit("update", {...props.candidateExperience, achievements}),
});

const currentEmploymentOptions = ref([
  { name: 'currentEmployment', value: 'yes', label: 'Yes', checked: false },
  { name: 'currentEmployment', value: 'no', label: 'No', checked: true },
]);

const toggleAccordion = () => {
  activeIndex.value = (activeIndex.value === mainIndex.value) ? null : mainIndex.value;
};

watch(() => selectedIsCurrentEmployment, (currentEmployment) => {
  if(currentEmployment.value === 'yes') {
    selectedEndDate.value = null;
  }
}, {deep: true});

function formatDate(date) {
  const options = {year: 'numeric', month: 'short'};
  return new Date(date).toLocaleDateString(undefined, options);
}

</script>

<template>
  <div id="accordion-flush" class="my-2">
    <h2 :id="`candidate-experience-heading-${mainIndex}`">
      <button
          type="button"
          :aria-expanded="activeIndex === mainIndex"
          @click="toggleAccordion"
          class="flex items-center justify-between w-full py-2 font-bold text-lg text-gray-500 border-b border-secondary-400 dark:border-gray-700 dark:text-gray-400 gap-3"
          :aria-controls="`candidate-experience-accordion-${mainIndex}`"
      >
        <span>{{ experienceLabel }} <sup class="text-xs text-secondary-400">{{ experienceDuration }}</sup> </span>
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
        <div class="grid lg:grid-cols-4 gap-3">
          <div class="col-span-1">
            <Label :required="true">Are you currently work here ?</Label>
            <RadioGroupInput
                v-model="selectedIsCurrentEmployment"
                :items="currentEmploymentOptions"
                :index="mainIndex"
            />
          </div>
        </div>
        <div class="grid lg:grid-cols-2 gap-3">
          <div class="col-span-1">
            <Label :required="true">Job Title</Label>
            <Input
                id="selectedJobTitle"
                v-model="selectedJobTitle"
                autocomplete="selectedJobTitle"
                name="selectedJobTitle"
                type="text"
                maxlength="61"
            />
            <InputError :message="errors.jobTitle" class="mt-2"/>
          </div>
          <div class="col-span-1">
            <Label :required="true">Company Name</Label>
            <Input
                id="selectedCompanyName"
                v-model="selectedCompanyName"
                autocomplete="selectedCompanyName"
                name="selectedCompanyName"
                type="text"
                maxlength="41"
            />
            <InputError :message="errors.companyName" class="mt-2"/>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-3">
          <div class="col-span-1">
            <Label :required="true">Start Date</Label>
            <Calendar
                id="selectedStartDate"
                :model="selectedStartDate"
                autocomplete="off"
                name="selectedStartDate"
                :max-date="maxDate()"
                :field-name="'startDate'"
                @update:model="selectedStartDate = $event"
            />
            <InputError :message="errors.startDate" class="mt-2"/>
          </div>

          <div class="col-span-1" v-show="selectedIsCurrentEmployment === 'no' ">
            <Label :required="true">End Date</Label>
            <Calendar
                id="selectedEndDate"
                :model="selectedEndDate"
                autocomplete="off"
                name="selectedEndDate"
                :max-date="maxDate()"
                :min-date="selectedStartDate ? selectedStartDate : maxDate()"
                :field-name="'endDate'"
                @update:model="selectedEndDate = $event"
            />
            <InputError :message="errors.endDate" class="mt-2"/>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-3">
          <div class="col-span-2">
            <Label :required="true">Responsibilities / Projects</Label>
            <Textarea
                id="selectedResponsibilities"
                v-model="selectedResponsibilities"
                rows="4"
                name="selectedResponsibilities"
            />
            <InputError :message="errors.responsibilities" class="mt-2"/>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-3">
          <div class="col-span-2">
            <Label>Achievements</Label>
            <Textarea
                id="selectedAchievements"
                v-model="selectedAchievements"
                rows="4"
                name="selectedAchievements"
            />
          </div>
        </div>

        <div
            class="flex flex-row mt-3"
            v-if="props.candidateExperience.index !== 0"
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
