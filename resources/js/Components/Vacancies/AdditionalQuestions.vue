<script setup>
import Button from "@/Components/Button.vue";
import Questions from "@/Components/Vacancies/Partials/Questions.vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
});

function addQuestion() {
  const additionalQuestion = {
    id: props.form.id,
    question: '',
    answers: '',
  };

  props.form.additionalQuestions = [...props.form.additionalQuestions, additionalQuestion];
}

if (props.form.additionalQuestions.length === 0) {
  addQuestion();
}

</script>

<template>
  <div class="">
    <Questions
        v-model:additionalQuestions="form.additionalQuestions"
        class="border-b mb-4"
        :is-editing="isEditing"
    />
    <div
        v-if="form.errors.additionalQuestions"
        class="text-red-500 text-xs my-1"
        v-text="form.errors.additionalQuestions"
    ></div>

    <div class="grid grid-cols-3 gap-6">
      <div class="col-span-3">
        <Button
            class="text-white cursor-pointer"
            btn-size="xxm"
            btn-color="green"
            type="button"
            :is-rounded="false"
            @click="addQuestion"
        >Add More
        </Button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>