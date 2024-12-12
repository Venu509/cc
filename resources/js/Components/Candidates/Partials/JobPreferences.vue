<script setup>

import InputError from "@/Components/InputError.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Multiselect from "@vueform/multiselect";
import {validateField} from "@/Components/Services/Validation";
import FileInput from "@/Components/FileInput.vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  industries: {
    type: Object,
    required: true,
  },
  jobTypes: {
    type: Object,
    required: true,
  },
  employmentStatus: {
    type: Object,
    required: true,
  },
  userPreferredLocations: {
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
    <div class="grid lg:grid-cols-2 gap-3">
      <div>
        <Label :required="true">Job Types</Label>
        <Multiselect
            v-model="form.preferredJobType"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="jobTypes"
            :loading="false"
            :object="true"
            @select="validateField('preferredJobType', form)"
        />
        <InputError :message="errors.preferredJobType" class="mt-2"/>
      </div>

      <div>
        <Label :required="true">Preferred Job Industry</Label>
        <Multiselect
            v-model="form.preferredJobIndustry"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="industries"
            :loading="false"
            :object="true"
            @select="validateField('preferredJobIndustry', form)"
        />
        <InputError :message="errors.preferredJobIndustry" class="mt-2"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-3">
      <div class="col-span-1">
        <Label :required="true">Job Role</Label>
        <Input
            id="preferredJobRole"
            v-model="form.preferredJobRole"
            autocomplete="preferredJobRole"
            name="preferredJobRole"
            type="text"
            maxlength="21"
            @input="validateField('preferredJobRole', form)"
        />
        <InputError :message="errors.preferredJobRole" class="mt-2"/>
      </div>

      <div class="col-span-1">
        <Label :required="true">Job Locations</Label>
        <Multiselect
            v-model="form.preferredJobLocation"
            class="mt-1 p-0"
            mode="tags"
            placeholder="Search or add preferred job location"
            tag-placeholder="Add this as new preferred job location"
            :multiple="true"
            :taggable="true"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :create-option="true"
            :preselect-first="true"
            :options="userPreferredLocations"
            :loading="false"
            :object="true"
            @select="validateField('preferredJobLocation', form)"
        />
        <InputError :message="errors.preferredJobLocation" class="mt-2"/>
      </div>

      <div>
        <Label :required="true">Employment Status</Label>
        <Multiselect
            v-model="form.preferredJobEmploymentStatus"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="employmentStatus"
            :loading="false"
            :object="true"
            @remove="validateField('preferredJobEmploymentStatus', form)"
            @select="validateField('preferredJobEmploymentStatus', form)"
        />
        <InputError :message="errors.preferredJobEmploymentStatus" class="mt-2"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
