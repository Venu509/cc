<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Vacancy" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="p-6">
        <div v-if="!isSaving">
          <div v-if="!isCompanyUser" class="grid grid-cols-1 lg:grid-cols-3 gap-3">
            <div class="col-span-1 lg:col-span-1">
              <Label for="company" value="Select Company" :required="true"/>

              <Multiselect
                  v-model="form.company"
                  mode="single"
                  class="mt-1"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :options="companies"
                  :loading="isLoadingCompanies"
                  :object="true"
              />
              <InputError :message="errors.company" class="mt-2"/>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-6 gap-3">
            <div class="col-span-6 lg:col-span-3">
              <Label for="title" value="Job Title" :required="true"/>
              <Input
                  id="title"
                  v-model="form.title"
                  autocomplete="title"
                  name="title"
                  type="text"
                  maxlength="101"
                  @input="validateField('title', form)"
              />
              <InputError :message="errors.title" class="mt-2"/>
            </div>

            <div class="col-span-6 lg:col-span-3">
              <Label for="locations" value="Locations" :required="true"/>
              <Multiselect
                  v-model="form.locations"
                  mode="tags"
                  class="mt-1 p-0"
                  placeholder="Search or add locations"
                  tag-placeholder="Add this as new locations"
                  :multiple="true"
                  :taggable="true"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :create-option="true"
                  :preselect-first="true"
                  :options="locations"
                  :loading="false"
                  :object="false"
                  @select="validateField('locations', form)"
              />
              <InputError :message="errors.locations" class="mt-2"/>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-6 gap-3">
            <div class="col-span-6 lg:col-span-3">
              <Label for="workModes" value="Work Modes" :required="true"/>
              <Multiselect
                  v-model="form.workModes"
                  mode="tags"
                  class="mt-1 p-0"
                  placeholder="Search or add work modes"
                  :multiple="true"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :preselect-first="true"
                  :options="workModes"
                  :loading="false"
                  :object="false"
                  @select="validateField('workModes', form)"
              />
              <InputError :message="errors.workModes" class="mt-2"/>
            </div>

            <div class="col-span-6 lg:col-span-3">
              <Label for="yearsOfExperiences" value="Required Experiences (Yrs)" :required="true"/>
              <Input
                  id="yearsOfExperiences"
                  v-model="form.yearsOfExperiences"
                  autocomplete="yearsOfExperiences"
                  name="yearsOfExperiences"
                  type="text"
                  maxlength="50"
                  @input="validateField('yearsOfExperiences', form)"
                  @keydown="allowOnlyNumbers"
              />
              <InputError :message="errors.yearsOfExperiences" class="mt-2"/>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-6 gap-3">
            <div class="col-span-6 lg:col-span-3">
              <Label for="qualifications" value="Qualifications" :required="true"/>
              <Multiselect
                  v-model="form.qualifications"
                  mode="tags"
                  class="mt-1 p-0"
                  placeholder="Search or add qualifications"
                  tag-placeholder="Add this as new qualifications"
                  :multiple="true"
                  :taggable="true"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :create-option="true"
                  :preselect-first="true"
                  :options="qualifications"
                  :loading="false"
                  :object="false"
                  @select="validateField('qualifications', form)"
              />
              <InputError :message="errors.qualifications" class="mt-2"/>
            </div>

            <div class="col-span-6 lg:col-span-3">
              <Label for="benefits" value="Benefits" :required="true"/>
              <Multiselect
                  v-model="form.benefits"
                  mode="tags"
                  class="mt-1 p-0"
                  placeholder="Search or add benefits"
                  tag-placeholder="Add this as new benefits"
                  :multiple="true"
                  :taggable="true"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :create-option="true"
                  :preselect-first="true"
                  :options="benefits"
                  :loading="false"
                  :object="false"
                  @select="validateField('benefits', form)"
              />
              <InputError :message="errors.benefits" class="mt-2"/>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-8 gap-3">
            <div class="col-span-8 lg:col-span-4">
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

            <div class="col-span-8 lg:col-span-2">
              <Label for="salary" value="Salary" :required="true"/>
              <Input
                  id="salary"
                  v-model="form.salary"
                  autocomplete="off"
                  name="salary"
                  type="text"
                  maxlength="50"
                  @input="validateField('salary', form)"
                  @keydown="allowOnlyNumbers"
              />
              <InputError :message="errors.salary" class="mt-2"/>
            </div>

            <div class="col-span-8 lg:col-span-2">
              <div class="col-span-1">
                <Label for="salaryFrequency" value="Salary Frequency" :required="true"/>
                <RadioGroupInput
                    v-model="form.salaryFrequency"
                    :items="salaryFrequencies"
                    @change="validateField('salaryFrequency', form)"
                />
                <InputError class="mt-2" :message="errors.salaryFrequency"/>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-8 gap-3">
            <div class="col-span-8 lg:col-span-3">
              <Label for="parent" value="Main Category" :required="true"/>
              <Multiselect
                  v-model="form.parent"
                  mode="single"
                  class="mt-1"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :options="parents"
                  :loading="isLoadingParents"
                  :object="true"
                  @select="getChildCategories"
              />
              <InputError :message="errors.parent" class="mt-2"/>
            </div>

            <div class="col-span-8 lg:col-span-3">
              <Label for="child" value="Sub Category" :required="true"/>
              <Multiselect
                  v-model="form.child"
                  mode="single"
                  class="mt-1"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :options="children"
                  :loading="isLoadingChildren"
                  :object="true"
                  @select="validateField('child', form)"
              />
              <InputError :message="errors.child" class="mt-2"/>
            </div>

            <div class="col-span-8 lg:col-span-2">
              <Label for="expireDate" value="Last Date" :required="true"/>
              <Calendar
                  id="expireDate"
                  :model="form.expireDate"
                  autocomplete="off"
                  name="expireDate"
                  :min-date="minDate()"
                  :field-name="'expireDate'"
                  @update:model="form.expireDate = $event"
                  @input="validateField('expireDate', form)"
              />
              <InputError :message="errors.expireDate" class="mt-2"/>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-8 gap-3">
            <div class="col-span-8 lg:col-span-2">
              <Label for="isWalkInInterview" value="Walk In Interview ?" :required="true"/>
              <RadioGroupInput
                  v-model="form.isWalkInInterview"
                  :items="isWalkInInterviewOption"
                  @change="validateField('isWalkInInterview', form)"
              />
              <InputError class="mt-2" :message="errors.isWalkInInterview"/>
            </div>

            <div v-if="isThisWalkInInterview" class="col-span-8 lg:col-span-2">
              <Label for="startDate" value="Start Date" :required="true"/>

              <Calendar
                  id="startDate"
                  :model="form.startDate"
                  autocomplete="off"
                  name="startDate"
                  :min-date="minDate()"
                  :field-name="'startDate'"
                  @update:model="form.startDate = $event"
                  @input="validateField('startDate', form)"
              />
              <InputError :message="errors.startDate" class="mt-2"/>
            </div>

            <div v-if="isThisWalkInInterview" class="col-span-8 lg:col-span-2">
              <Label for="endDate" value="End Date" :required="true"/>

              <Calendar
                  id="endDate"
                  :model="form.endDate"
                  autocomplete="off"
                  name="endDate"
                  :min-date="minDate()"
                  :field-name="'endDate'"
                  @update:model="form.endDate = $event"
                  @input="validateField('endDate', form)"
              />
              <InputError :message="errors.endDate" class="mt-2"/>
            </div>

            <div class="col-span-8 lg:col-span-2">
              <Label for="noOfOpenings" value="No Of Openings" :required="true"/>
              <Input
                  id="noOfOpenings"
                  v-model="form.noOfOpenings"
                  autocomplete="off"
                  name="noOfOpenings"
                  type="number"
                  maxlength="3"
                  @input="validateField('noOfOpenings', form)"
              />
              <InputError :message="errors.noOfOpenings" class="mt-2"/>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-8 gap-3">
            <div class="col-span-8 lg:col-span-2">
              <Label for="applicationMethod" value="Application method" :required="true"/>
              <RadioGroupInput
                  v-model="form.applicationMethod"
                  :items="applicationMethods"
                  @change="validateField('applicationMethod', form)"
              />
              <InputError class="mt-2" :message="errors.applicationMethods"/>
            </div>

            <div v-if="!isInternalApplication" class="col-span-8 lg:col-span-6">
              <Label for="externalLink" value="External Link" :required="true"/>
              <Input
                  id="externalLink"
                  v-model="form.externalLink"
                  autocomplete="off"
                  name="externalLink"
                  type="text"
                  @input="validateField('externalLink', form)"
              />
              <InputError :message="errors.externalLink" class="mt-2"/>
            </div>
          </div>

          <div class="grid lg:grid-cols-2 gap-3">
            <div class="col-span-2">
              <Label :required="true">Description</Label>
              <RichTextEditor
                  column="description"
                  :form="form"
                  height="400"
                  :validate-field="validateField"
                  v-model:content="form.description"/>
              <InputError :message="errors.description" class="mt-2"/>
            </div>
          </div>

          <div class="grid lg:grid-cols-4 gap-3 my-3">
            <div class="col-span-1">
              <Label for="hasAdditionalQuestions" value="Do you have any questions for candidate ?" :required="true"/>
              <RadioGroupInput
                  v-model="form.hasAdditionalQuestions"
                  :items="questionOption"
                  @change="validateField('hasAdditionalQuestions', form)"
              />
              <InputError class="mt-2" :message="errors.hasAdditionalQuestions"/>
            </div>
          </div>

          <div v-if="hasAdditionalQuestions" class="grid lg:grid-cols-1 gap-3 my-3">
            <div class="col-span-1">
              <AdditionalQuestions :form="form" :is-editing="isEditing"/>
            </div>
          </div>
        </div>

        <Saving v-else/>
      </div>
    </div>
  </div>

</template>

<script setup>
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import {allowOnlyNumbers, errors, fetchRules, minDate, validateField} from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
import Multiselect from "@vueform/multiselect";
import axios from "axios";
import RichTextEditor from "@/Components/Widgets/RichTextEditor.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import AdditionalQuestions from "@/Components/Vacancies/AdditionalQuestions.vue";
import FormButtonAndHeaderSection from "@/Components/Widgets/FormButtonAndHeaderSection.vue";
import Calendar from "@/Components/Widgets/Calendar.vue";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
  workModes: {
    type: Object,
    required: true,
  },
  qualifications: {
    type: Object,
    required: true,
  },
  benefits: {
    type: Object,
    required: true,
  },
  locations: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
});

const form = useForm({...props.modelValue});

let isLoading = ref(true)
const isSaving = ref(false);
const isLoginViaMobile = ref(true);
const isLoadingParents = ref(true);
const isLoadingChildren = ref(false);
const isLoadingCompanies = ref(false);
const parents = ref({});
const children = ref({});
const companies = ref({});

const questionOption = ref([
  {name: 'questionOption', value: 'no', label: 'No', checked: hasAdditionalQuestions},
  {name: 'questionOption', value: 'yes', label: 'Yes', checked: hasAdditionalQuestions},
])

const applicationMethods = ref([
  {name: 'applicationMethods', value: 'internal', label: 'Through the App', checked: true},
  {name: 'applicationMethods', value: 'external', label: 'External Portal', checked: false},
])

const isWalkInInterviewOption = ref([
  {name: 'isWalkInInterviewOption', value: 'no', label: 'No', checked: true},
  {name: 'isWalkInInterviewOption', value: 'yes', label: 'Yes', checked: false},
])

const salaryFrequencies = ref([
  {name: 'salaryFrequency', value: 'per-annum', label: 'Per Annum', checked: true},
  {name: 'salaryFrequency', value: 'per-month', label: 'Per Month', checked: false},
])

const isCompanyUser = computed(() => {
  return form.currentRole === 'company';
});

const hasAdditionalQuestions = computed(() => {
  return form.hasAdditionalQuestions === 'yes'
});

const isInternalApplication = computed(() => {
  return form.applicationMethod === 'internal'
});

const isThisWalkInInterview = computed(() => {
  return form.isWalkInInterview === 'yes'
});

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

const handleError = (errorResponse) => {
  if (errorResponse) {
    const newErrors = {};

    for (const [field, message] of Object.entries(errorResponse)) {
      newErrors[field] = message;
    }

    Object.assign(errors, newErrors);
    form.clearErrors();
    form.setError(newErrors);
  }

  isSaving.value = false;
};

const handleSuccess = () => {
  form.reset();
  Object.keys(errors).forEach(key => {
    errors[key] = '';
  });
  isSaving.value = false;
};

function save() {
  isSaving.value = true;

  if (props.isEditing) {
    Inertia.post(route("admin.vacancies.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.vacancies.store"), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

onMounted(() => {
  if (!isCompanyUser.value) {
    getCompanies()
  }
  categories()
  fetchRules(route("admin.vacancies.rules"));
});

function getChildCategories() {
  validateField('parent', form)
  validateField('child', form)
  children.value = null
  form.child = null
  categories('child', form.parent.value)
}

function categories(type = 'parent', parentID = null) {
  if (type === 'child') {
    isLoadingChildren.value = true
  }

  axios.get(route('admin.categories.fetch'), {
    params: {
      type: type,
      parentId: parentID,
    }
  })
      .then(response => {
        if (type === 'parent') {
          parents.value = response.data.categories
          isLoadingParents.value = false
        } else {
          children.value = response.data.categories
          isLoadingChildren.value = false
        }
      })
      .catch(error => {
        console.error('Error fetching rules:', error);
        isLoadingParents.value = false
        isLoadingChildren.value = false
      });
}

function getCompanies() {
  companies.value = null
  isLoadingCompanies.value = true
  axios.get(route('admin.companies.fetch'))
      .then(response => {
        companies.value = response.data.companies
        isLoadingCompanies.value = false
      })
      .catch(error => {
        console.error('Error fetching rules:', error);
        isLoadingCompanies.value = false
      });
}

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
