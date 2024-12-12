<script setup>

import InputError from "@/Components/InputError.vue";
import Multiselect from "@vueform/multiselect";
import Label from "@/Components/Label.vue";
import {ref} from "vue";
import Textarea from "@/Components/Textarea.vue";
import {validateField} from "@/Components/Services/Validation";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
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
});
</script>

<template>
  <div class="p-6">
    <div class="grid lg:grid-cols-2 gap-3">
      <div class="col-span-2">
        <Label :required="true">Key Skills</Label>
        <Multiselect
            v-model="form.keySkills"
            class="mt-1 p-0"
            mode="tags"
            placeholder="Search or add a skill"
            tag-placeholder="Add this as new skill"
            :multiple="true"
            :taggable="true"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :create-option="true"
            :preselect-first="true"
            :options="keySkills"
            :loading="false"
            :object="true"
            @select="validateField('keySkills', form)"
        />
        <InputError :message="errors.keySkills" class="mt-2"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-3">
      <div class="col-span-2">
        <Label :required="false">Certifications</Label>
        <Textarea
            id="certifications"
            v-model="form.certifications"
            rows="4"
            name="certifications"
            @input="validateField('certifications', form)"
        />
        <InputError :message="errors.certifications" class="mt-2"/>
      </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-3">
      <div class="col-span-2">
        <Label :required="false">Known Languages</Label>
        <Textarea
            id="knownLanguages"
            v-model="form.knownLanguages"
            rows="4"
            name="knownLanguages"
            @input="validateField('knownLanguages', form)"

        />
        <InputError :message="errors.knownLanguages" class="mt-2"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
