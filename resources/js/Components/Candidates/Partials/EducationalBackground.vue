<script setup>

import InputError from "@/Components/InputError.vue";
import Input from "@/Components/Input.vue";
import Multiselect from "@vueform/multiselect";
import Textarea from "@/Components/Textarea.vue";
import Label from "@/Components/Label.vue";
import {allowOnlyNumbers, validateField} from "@/Components/Services/Validation";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import {ref} from "vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  qualifications: {
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

const hasAdditionalQualifications = ref([
  { name: 'hasAdditionalQualifications', value: 'no', label: 'No', checked: true },
  { name: 'hasAdditionalQualifications', value: 'yes', label: 'Yes', checked: false },
])
</script>

<template>
  <div class="p-6">
    <div class="grid lg:grid-cols-4 gap-3">
      <div class="col-span-1">
        <Label :required="true">Highest Qualification</Label>
        <Multiselect
            v-model="form.qualification"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="qualifications"
            :loading="false"
            :object="true"
            @select="validateField('qualification', form)"
        />
        <InputError :message="errors.qualification" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Specialized In/Major</Label>
        <Input
            id="specializedIn"
            v-model="form.specializedIn"
            autocomplete="specializedIn"
            name="specializedIn"
            type="text"
            maxlength="21"
            @input="validateField('specializedIn', form)"

        />
        <InputError :message="errors.specializedIn" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">University / College</Label>
        <Input
            id="university"
            v-model="form.university"
            autocomplete="university"
            name="university"
            type="text"
            maxlength="21"
            @input="validateField('university', form)"

        />
        <InputError :message="errors.university" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Year Of Graduation</Label>
        <Input
            id="yearOfGraduation"
            v-model="form.yearOfGraduation"
            autocomplete="yearOfGraduation"
            name="yearOfGraduation"
            type="text"
            maxlength="4"
            @input="validateField('yearOfGraduation', form)"
            @keydown="allowOnlyNumbers"

        />
        <InputError :message="errors.yearOfGraduation" class="mt-2"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-3">
      <div class="col-span-1">
        <Label for="hasAdditionalQualification" value="Do you have any additional qualifications?" :required="true"/>
        <RadioGroupInput
            v-model="form.hasAdditionalQualification"
            :items="hasAdditionalQualifications"
        />
        <InputError class="mt-2" :message="form.errors.via"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-3" v-show="form.hasAdditionalQualification === 'yes' ">
      <div class="col-span-2">
        <Label :required="true">Additional Qualification</Label>
        <Textarea
            id="additionalQualification"
            v-model="form.additionalQualification"
            rows="2"
            name="additionalQualification"
            @input="validateField('additionalQualification', form, 'hasAdditionalQualification')"
        />
        <InputError :message="errors.additionalQualification" class="mt-2"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
