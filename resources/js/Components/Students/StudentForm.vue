<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Student" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="p-6">
        <div v-if="!isSaving">
          <div class="grid lg:grid-cols-2 gap-3">
            <div>
              <Label :required="true">First Name</Label>
              <Input
                  id="firstName"
                  v-model="form.firstName"
                  autocomplete="firstName"
                  name="firstName"
                  type="text"
                  @input="validateField('firstName', form)"
              />
              <InputError :message="errors.firstName" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Last Name</Label>
              <Input
                  id="lastName"
                  v-model="form.lastName"
                  autocomplete="lastName"
                  name="lastName"
                  type="text"
                  @input="validateField('lastName', form)"
              />
              <InputError :message="errors.lastName" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Student ID</Label>
              <Input
                  id="studentId"
                  v-model="form.studentId"
                  autocomplete="studentId"
                  name="studentId"
                  type="text"
                  @input="validateField('studentId', form)"
              />
              <InputError :message="errors.studentId" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Date Of Birth</Label>
              <Calendar
                  id="dateOfBirth"
                  :model="form.dateOfBirth"
                  autocomplete="off"
                  name="dateOfBirth"
                  :max-date="maxDate()"
                  :field-name="'dateOfBirth'"
                  @update:model="form.dateOfBirth = $event"
                  @input="validateField('dateOfBirth', form)"
              />
              <InputError :message="errors.dateOfBirth" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Mobile Number</Label>
              <Input
                  id="mobileNumber"
                  v-model="form.mobileNumber"
                  autocomplete="mobileNumber"
                  name="mobileNumber"
                  type="tel"
                  @input="validateField('mobileNumber', form)"
              />
              <InputError :message="errors.mobileNumber" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Email</Label>
              <Input
                  id="email"
                  v-model="form.email"
                  autocomplete="email"
                  name="email"
                  type="email"
                  @input="validateField('email', form)"
              />
              <InputError :message="errors.email" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Address</Label>
              <Input
                  id="address"
                  v-model="form.address"
                  autocomplete="address"
                  name="adress"
                  type="text"
                  @input="validateField('address', form)"
              />
              <InputError :message="errors.address" class="mt-2"/>
            </div>
            <div>
              <Label :required="false">Resume</Label>
              <FileInput
                  type="file"
                  class="mt-1 block w-full"
                  name="resume"
                  accept=".pdf,.doc,.docx"
                  @change="handleResumeUpload"
                  @input="validateField('resume', form)"
              />
            <a
                class="cursor-pointer text-secondary-400"
                v-if="form.resume && isEditing"
                :href="form.resume"
                target="_blank"
                download
                @click="form.resume">Download</a>

              <InputError :message="errors.resume" class="mt-2"/>
            </div>
            <div>
              <Label >PAN Number</Label>
              <Input
                  id="panNumber"
                  v-model="form.panNumber"
                  name="panNumber"
                  type="text"
                  class="uppercase"
                  @input="validateField('panNumber', form)"
              />
              <InputError :message="errors.panNumber" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Branch</Label>
              <Multiselect
                  v-model="form.branch"
                  mode="single"
                  class="mt-1"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :options="branches"
                  :loading="false"
                  :object="true"
                  @select="validateField('branch', form)"
              />
              <InputError :message="errors.branch" class="mt-2"/>
            </div>
            <div>
              <Label :required="true">Aadhaar Number</Label>
              <Input
                  id="aadhaarNumber"
                  v-model="form.aadhaarNumber"
                  name="aadhaarNumber"
                  type="text"
                  @input="validateField('aadhaarNumber', form)"
              />
              <InputError :message="errors.aadhaarNumber" class="mt-2"/>
            </div>

          <div>
            <Label :required="true">Qualification</Label>
            <Select
                v-model="form.qualification"
                @change="validateField('qualification', form)">
                <option value="under gradution">Under gradution</option>
                <option value="post gradution">Post gradution</option>
                <option value="gradution">Gradution</option>
            </Select>
            <InputError :message="errors.qualification" class="mt-2"/>
          </div>
          <div >
            <Label :required="true">Gender</Label>
            <Select
                v-model="form.gender"
                @change="validateField('gender', form)"
            >
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </Select>
            <InputError :message="errors.gender" class="mt-2"/>
          </div>
          <div>
            <Label :required="true">Marital Status</Label>
            <Select
                v-model="form.maritalStatus"
                @change="validateField('maritalStatus', form)"
            >
              <option value="single"> Single</option>
              <option value="married"> Married</option>

              </Select>
              <InputError :message="errors.maritalStatus" class="mt-2"/>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-3 mt-4">
          <div>
            <Label :required="true" for="image">Profile Picture</Label>
            <FileInput
                type="file"
                accept="image/x-png,image/gif,image/jpeg"
                class="mt-1 block w-full"
                name="profilePicture"
                @change="handleImageUpload"
            />
            <InputError :message="errors.profilePicture" class="mt-2"/>
          </div>
          <div v-if="form.imagePreview">
            <Label>Preview</Label>
            <img
                v-if="form.imagePreview"
                :src="form.imagePreview"
                class="rounded-full h-20 w-20 object-cover"
                style="background-color: black;"
            />
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
import FileInput from "@/Components/FileInput.vue";
import Multiselect from "@vueform/multiselect";
import Select from "@/Components/Select.vue";
import { fetchRules, validateField, errors, maxDate } from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
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
  branches: {
    type: Object,
    required: true,
  },
});

const form = useForm({...props.modelValue});

let isLoading = ref(true)
const isSaving = ref(false);
const isLoginViaMobile = ref(true);

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

function handleImageUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.profilePicture = file;
    validateField('profilePicture', form);
    const reader = new FileReader();
    reader.onload = (e) => {
      form.imagePreview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function handleResumeUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.resume = file;
  }
}

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
    Inertia.post(route("admin.students.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.students.store"), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

onMounted(() => {
    fetchRules(route("admin.students.rules"));
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
