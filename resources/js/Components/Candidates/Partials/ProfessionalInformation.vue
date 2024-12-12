<script setup>
import Input from "@/Components/Input.vue";
import InputError from "@/Components/InputError.vue";
import Multiselect from "@vueform/multiselect";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import Label from "@/Components/Label.vue";
import {ref} from "vue";
import {validateField} from "@/Components/Services/Validation";
import FileInput from "@/Components/FileInput.vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  noOfExperiences: {
    type: Object,
    required: true,
  },
  noticePeriods: {
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

const canRelocatedOptions = ref([
  { name: 'canRelocated', value: 'yes', label: 'Yes', checked: false },
  { name: 'canRelocated', value: 'no', label: 'No', checked: false },
]);

</script>

<template>
  <div class="p-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
      <div class="col-span-1 lg:col-span-2">
        <Label :required="true">Current Job Title</Label>
        <Input
            id="currentJobTitle"
            v-model="form.currentJobTitle"
            autocomplete="currentJobTitle"
            name="currentJobTitle"
            type="text"
            maxlength="50"
            @input="validateField('currentJobTitle', form)"
        />
        <InputError :message="errors.currentJobTitle" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Current Company</Label>
        <Input
            id="currentCompany"
            v-model="form.currentCompany"
            autocomplete="currentCompany"
            name="currentCompany"
            type="text"
            maxlength="41"
            @input="validateField('currentCompany', form)"
        />
        <InputError :message="errors.currentCompany" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Current Salary</Label>
        <Input
            id="currentSalary"
            v-model="form.currentSalary"
            autocomplete="currentSalary"
            name="currentSalary"
            type="text"
            maxlength="11"
            @input="validateField('currentSalary', form)"
        />
        <InputError :message="errors.currentSalary" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Expected Salary</Label>
        <Input
            id="expectedSalary"
            v-model="form.expectedSalary"
            autocomplete="expectedSalary"
            name="expectedSalary"
            type="text"
            maxlength="11"
            @input="validateField('expectedSalary', form)"
        />
        <InputError :message="errors.expectedSalary" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">No of years of Experience</Label>
        <Multiselect
            v-model="form.noOfExperience"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="noOfExperiences"
            :loading="false"
            :object="true"
            @select="validateField('noOfExperience', form)"
        />
        <InputError :message="errors.noOfExperience" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Notice Period</Label>
        <Multiselect
            v-model="form.noticePeriod"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="noticePeriods"
            :loading="false"
            :object="true"
            @select="validateField('noticePeriod', form)"
        />
        <InputError :message="errors.noticePeriod" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Can Relocated?</Label>
        <RadioGroupInput
            v-model="form.canRelocated"
            :items="canRelocatedOptions"
        />
        <InputError :message="errors.canRelocated" class="mt-2"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
