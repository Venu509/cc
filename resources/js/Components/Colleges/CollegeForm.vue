<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Collage" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="p-6">
        <div v-if="!isSaving">
          <div class="grid gap-3 lg:grid-cols-4 md:grid-cols-2 sm:grid-cols-1">
            <div class="col-span-1" v-if="!isEditing">
              <Label for="via" value="Login Using?" :required="true"/>
              <RadioGroupInput
                  v-model="form.via"
                  :items="loginOption"
                  @change="validateField('username', form)"
              />
              <InputError class="mt-2" :message="errors.via"/>
            </div>
            <div class="col-span-1">
              <Label :required="true">Type</Label>
              <Multiselect
                  v-model="form.type"
                  mode="single"
                  class="mt-1"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :options="types"
                  :loading="false"
                  :object="true"
                  @select="validateField('type', form)"
              />
              <InputError :message="errors.type" class="mt-2"/>
            </div>
          </div>

          <div class="grid gap-3 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 mt-4">
            <div class="col-span-1" v-if="!isEditing">
              <Label for="username" :value="form.via === 'email' ? 'Username (Email)' : 'Username (Phone)'" :required="true"/>
              <Input
                  id="username"
                  v-model="form.username"
                  :type="form.via === 'email' ? 'email' : 'tel'"
                  class="block w-full"
                  required
                  autofocus
                  autocomplete="username"
                  @input="validateField('username', form)"
                  @keydown="form.via === 'phone' ? allowOnlyNumbers : null"
              />
              <InputError :message="errors.username" class="mt-2"/>
            </div>

            <div class="col-span-1">
              <Label :required="true">College Name</Label>
              <Input
                  id="name"
                  v-model="form.name"
                  autocomplete="name"
                  name="name"
                  type="text"
                  @input="validateField('name', form)"
              />
              <InputError :message="errors.name" class="mt-2"/>
            </div>

            <div class="col-span-1">
              <Label :required="true">Date of Registration</Label>
              <Calendar
                  id="dateOfRegister"
                  :model="form.dateOfRegister"
                  autocomplete="off"
                  name="dateOfRegister"
                  :max-date="maxDate()"
                  :field-name="'dateOfRegister'"
                  @update:model="form.dateOfRegister = $event"
                  @input="validateField('dateOfRegister', form)"
              />
              <InputError :message="errors.dateOfRegister" class="mt-2"/>
            </div>
          </div>

          <div class="grid gap-3 lg:grid-cols-2 sm:grid-cols-1 mt-4">
            <div class="col-span-2">
              <Label :required="true">Address</Label>
              <Textarea
                  id="address"
                  v-model="form.address"
                  rows="2"
                  name="address"
                  @input="validateField('address', form)"
              />
              <InputError :message="errors.address" class="mt-2"/>
            </div>
          </div>

          <div class="grid gap-3 lg:grid-cols-2 sm:grid-cols-1 mt-4">
            <div>
              <Label :required="true">Mobile Number</Label>
              <Input
                  id="mobileNumber"
                  v-model="form.mobileNumber"
                  autocomplete="mobileNumber"
                  name="mobileNumber"
                  type="tel"
                  maxlength="12"
                  @input="validateField('mobileNumber', form)"
                  @keydown="allowOnlyNumbers"
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
          </div>

          <div class="grid gap-3 lg:grid-cols-2 sm:grid-cols-1 mt-4">
            <div>
              <Label :required="true">Years of Existence</Label>
              <Input
                  id="yearsOfExistence"
                  v-model="form.yearsOfExistence"
                  autocomplete="yearsOfExistence"
                  name="yearsOfExistence"
                  type="text"
                  @input="validateField('yearsOfExistence', form)"
                  @keydown="allowOnlyNumbers"
              />
              <InputError :message="errors.yearsOfExistence" class="mt-2"/>
            </div>

            <div>
              <Label :required="true">Contact Person Name</Label>
              <Input
                  id="contactPerson"
                  v-model="form.contactPerson"
                  autocomplete="contactPerson"
                  name="contactPerson"
                  type="text"
                  @input="validateField('contactPerson', form)"
              />
              <InputError :message="errors.contactPerson" class="mt-2"/>
            </div>
          </div>

          <div class="grid gap-3 lg:grid-cols-2 sm:grid-cols-1 mt-4">
            <div>
              <Label :required="true">Contact Person Email</Label>
              <Input
                  id="contactPersonEmail"
                  v-model="form.contactPersonEmail"
                  autocomplete="contactPersonEmail"
                  name="contactPersonEmail"
                  type="email"
                  @input="validateField('contactPersonEmail', form)"
              />
              <InputError :message="errors.contactPersonEmail" class="mt-2"/>
            </div>

            <div>
              <Label :required="true">Contact Person Phone</Label>
              <Input
                  id="contactPersonPhone"
                  v-model="form.contactPersonPhone"
                  autocomplete="contactPersonPhone"
                  name="contactPersonPhone"
                  type="tel"
                  @input="validateField('contactPersonPhone', form)"
                  @keydown="allowOnlyNumbers"
              />
              <InputError :message="errors.contactPersonPhone" class="mt-2"/>
            </div>
          </div>

          <div class="grid gap-3 lg:grid-cols-2 sm:grid-cols-1 mt-4">
            <div>
              <Label :required="true">Contact Person Address</Label>
              <Input
                  id="contactPersonAddress"
                  v-model="form.contactPersonAddress"
                  autocomplete="contactPersonAddress"
                  name="contactPersonAddress"
                  type="text"
                  @input="validateField('contactPersonAddress', form)"
              />
              <InputError :message="errors.contactPersonAddress" class="mt-2"/>
            </div>

            <div>
              <Label :required="true">Registration Document</Label>
              <FileInput
                  type="file"
                  class="mt-1 block w-full"
                  name="registrationDoc"
                  accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf"
                  @change="handleRegistrationDocUpload"
              />
              <InputError :message="errors.registrationDoc"/>
              <div v-if="form.registrationDocPreview && isEditing">
                <Label></Label> <br/>
                <a class="cursor-pointer text-secondary-400" :href="form.registrationDocPreview" target="_blank" download @click="form.registrationDocPreview">Download</a>
              </div>
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
import Textarea from "@/Components/Textarea.vue";
import {fetchRules, validateField, errors, maxDate, allowOnlyNumbers, minDate} from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
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
  types: {
    type: Object,
    required: true,
  }
});

const form = useForm({...props.modelValue});

let isLoading = ref(true)
const isSaving = ref(false);

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

const loginOption = ref([
  { name: 'loginOption', value: 'email', label: 'Email', checked: true },
  { name: 'loginOption', value: 'phone', label: 'Phone', checked: false },
])

function handleRegistrationDocUpload(event) {
    const file = event.target.files[0];
    if (file) {
        form.registrationDoc = file;
        validateField('registrationDoc', form);
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
    Inertia.post(route("admin.colleges.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.colleges.store"), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

onMounted(() => {
    fetchRules(route("admin.colleges.rules"));
});

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
