<script setup>

import Experiences from "@/Components/Candidates/Partials/Experiences/Experiences.vue";
import Button from "@/Components/Button.vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
  errors: {
    type: Object,
    required: true,
  },
  validateField: {
    type: Function,
    required: true,
  },
  maxDate: {
    type: Function,
    required: true,
  },
});

function addExperience() {
  const candidateExperience = {
    id: props.form.id,
    companyName: '',
    jobTitle: '',
    startDate: '',
    endDate: '',
    responsibilities: '',
    achievements: '',
    isCurrentEmployer: 'no',
  };

  props.form.candidateExperiences = [...props.form.candidateExperiences, candidateExperience];
}

if (props.form.candidateExperiences.length === 0) {
  addExperience();
}
</script>

<template>
  <div class="p-6">
    <Experiences
        v-model:candidateExperiences="form.candidateExperiences"
        class="border-b mb-4"
        :is-editing="isEditing"
        :max-date="maxDate"
        :form="form"
        :errors="errors"
    />
    <div
        v-if="form.errors.candidateExperiences"
        class="text-red-500 text-xs my-1"
        v-text="form.errors.candidateExperiences"
    ></div>

    <div class="grid grid-cols-3 gap-6">
      <div class="col-span-3">
        <Button
            class="text-white cursor-pointer"
            btn-size="xxm"
            btn-color="green"
            type="button"
            :is-rounded="false"
            @click="addExperience"
        >Add More
        </Button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
