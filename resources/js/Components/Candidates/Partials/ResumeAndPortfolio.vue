<script setup>
import InputError from "@/Components/InputError.vue";
import Label from "@/Components/Label.vue";
import FileInput from "@/Components/FileInput.vue";
import Textarea from "@/Components/Textarea.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import {ref} from "vue";
import {validateField} from "@/Components/Services/Validation";

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

function handlePortfolioUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.portfolio = file;
  }
}

function handleCoverLetterUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.coverLetterFile = file;
  }
}

const coverLetterTypeOptions = ref([
  { name: 'coverLetterType', value: 'text', label: 'Text', checked: true },
  { name: 'coverLetterType', value: 'file', label: 'File Upload', checked: false },
]);

</script>

<template>
  <div class="p-6">
    <div class="grid lg:grid-cols-2 gap-3">
      <div class="col-span-1">
        <Label>Portfolio</Label>
        <FileInput
            type="file"
            class="mt-1 block w-full"
            name="portfolio"
            accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf"
            @change="handlePortfolioUpload"
        />
        <a class="cursor-pointer text-secondary-400" v-if="form.portfolioLink" :href="form.portfolioLink" target="_blank" download @click="form.portfolioLink">Download</a>
        <InputError :message="errors.portfolio"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-3">
      <div class="col-span-1">
        <Label :required="true">Cover Letter Type</Label>
        <RadioGroupInput
            v-model="form.coverLetterType"
            :items="coverLetterTypeOptions"
        />
        <InputError :message="errors.coverLetterType"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-3">
      <div class="col-span-2" v-if="form.coverLetterType === 'text'">
        <Label>Cover Letter</Label>
        <Textarea
            id="coverLetter"
            v-model="form.coverLetter"
            rows="4"
            name="coverLetter"
            @input="validateField('coverLetter', form)"
        />
        <InputError :message="errors.coverLetter"/>
      </div>

      <div class="col-span-1" v-if="form.coverLetterType === 'file'">
        <Label>Cover Letter</Label>
        <FileInput
            type="file"
            class="mt-1 block w-full"
            name="coverLetterFile"
            @change="handleCoverLetterUpload"
        />
        <InputError :message="errors.coverLetterFile"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
