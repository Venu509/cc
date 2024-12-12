<script setup>

import InputError from "@/Components/InputError.vue";
import Label from "@/Components/Label.vue";
import Textarea from "@/Components/Textarea.vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
    errors: {
        type: Object,
        required: true,
    },
    validateField: {
        type: Function,
        required: true,
    },
});

</script>

<template>
  <div class="p-6">
    <div class="grid lg:grid-cols-1 gap-3">
      <div>
        <Label :required="false">Career Objective</Label>
        <Textarea
            id="careerObjective"
            v-model="form.careerObjective"
            rows="4"
            name="careerObjective"
            @input="validateField('careerObjective', form)"
        />
        <InputError :message="errors.careerObjective" class="mt-2"/>
      </div>
    </div>
    <div class="grid lg:grid-cols-1 gap-3">
      <div>
        <Label :required="false">Awards And Recognitions</Label>
        <Textarea
            id="awardsAndRecognitions"
            v-model="form.awardsAndRecognitions"
            rows="4"
            name="awardsAndRecognitions"
            @input="validateField('awardsAndRecognitions', form)"
        />
        <InputError :message="errors.awardsAndRecognitions" class="mt-2"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
