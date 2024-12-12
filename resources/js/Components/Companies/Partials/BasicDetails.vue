<script setup>
import Input from "@/Components/Input.vue";
import InputError from "@/Components/InputError.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import Label from "@/Components/Label.vue";
import {ref} from "vue";
import Textarea from "@/Components/Textarea.vue";
import {allowOnlyNumbers, validateField} from "@/Components/Services/Validation";
import FileInput from "@/Components/FileInput.vue";

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
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const genderOptions = ref([
  { name: 'genderOption', value: 'male', label: 'Male', checked: false },
  { name: 'genderOption', value: 'female', label: 'Female', checked: false },
  { name: 'genderOption', value: 'other', label: 'Other', checked: false },
]);

const maritalStatusOptions = ref([
  { name: 'maritalStatus', value: 'single', label: 'Single', checked: false },
  { name: 'maritalStatus', value: 'married', label: 'Married', checked: false },
  { name: 'maritalStatus', value: 'divorced', label: 'Divorced', checked: false },
  { name: 'maritalStatus', value: 'widowed', label: 'Widowed', checked: false },
]);

const loginOption = ref([
  { name: 'loginOption', value: 'email', label: 'Email', checked: true },
  { name: 'loginOption', value: 'phone', label: 'Phone', checked: false },
])

function handleAvatarUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.avatar = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      props.form.profilePreview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function handleRegistrationDocumentUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.registrationDoc = file;
    validateField('registrationDoc', props.form);
  }
}

</script>

<template>
  <div class="p-6">
    <!-- First Grid Row for "Login Using?" -->
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4">
      <div class="col-span-1">
        <Label for="via" value="Login Using?" :required="true" />
        <RadioGroupInput
            v-model="form.via"
            :items="loginOption"
            @change="validateField('username', form)"
        />
      </div>
    </div>

    <!-- Second Grid Row for "Username" and "Company Name" -->
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-4">
      <div class="col-span-1 lg:col-span-1">
        <Label
            for="username"
            :value="form.via === 'email' ? 'Username (Email)' : 'Username (Phone)'"
            :required="true"
        />
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
        <InputError class="mt-2" :message="errors.username" />
      </div>

      <div class="col-span-1 lg:col-span-2">
        <Label :required="true">Company Name</Label>
        <Input
            id="companyName"
            v-model="form.companyName"
            autocomplete="companyName"
            name="companyName"
            type="text"
            maxlength="50"
            @input="validateField('companyName', form)"
        />
        <InputError :message="errors.companyName" class="mt-2" />
      </div>

      <div class="col-span-1 lg:col-span-1">
        <Label :required="false" note="(Optional)" note-text-color="text-indigo-500">GST Number</Label>
        <Input
            id="gst"
            v-model="form.gst"
            autocomplete="gst"
            name="gst"
            type="text"
            maxlength="16"
            @input="validateField('gst', form)"
        />
        <InputError :message="errors.gst" class="mt-2" />
      </div>
    </div>

    <div class="grid grid-cols-1 gap-3">
      <div class="col-span-1">
        <Label :required="true">Company Address</Label>
        <Textarea
            id="address"
            v-model="form.address"
            rows="2"
            name="address"
            @input="validateField('address', form)"
        />
        <InputError :message="errors.address" class="mt-2" />
      </div>
    </div>

    <!-- Third Grid Row for "Email" and "Phone Number" -->
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
      <div class="col-span-1">
        <Label for="email" value="Email Address" :required="true" />
        <Input
            id="email"
            v-model="form.email"
            autocomplete="email"
            name="email"
            type="email"
            maxlength="50"
            @input="validateField('email', form)"
        />
        <InputError :message="errors.email" class="mt-2" />
      </div>

      <div class="col-span-1">
        <Label for="mobileNumber" value="Phone Number" :required="true" />
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
        <InputError :message="errors.mobileNumber" class="mt-2" />
      </div>
    </div>

    <!-- Fourth Grid Row for "Contact Person Details" -->
    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 lg:grid-cols-3">
      <div class="col-span-1">
        <Label :required="true">Contact Person Name</Label>
        <Input
            id="contactPerson"
            v-model="form.contactPerson"
            autocomplete="contactPerson"
            name="contactPerson"
            type="text"
            maxlength="50"
            @input="validateField('contactPerson', form)"
        />
        <InputError :message="errors.contactPerson" class="mt-2" />
      </div>

      <div class="col-span-1">
        <Label :required="true">Contact Person Email</Label>
        <Input
            id="contactPersonEmail"
            v-model="form.contactPersonEmail"
            autocomplete="contactPersonEmail"
            name="contactPersonEmail"
            type="email"
            maxlength="50"
            @input="validateField('contactPersonEmail', form)"
        />
        <InputError :message="errors.contactPersonEmail" class="mt-2" />
      </div>

      <div class="col-span-1">
        <Label :required="true">Contact Person Phone</Label>
        <Input
            id="contactPersonPhone"
            v-model="form.contactPersonPhone"
            autocomplete="contactPersonPhone"
            name="contactPersonPhone"
            type="tel"
            maxlength="12"
            @input="validateField('contactPersonPhone', form)"
            @keydown="allowOnlyNumbers"
        />
        <InputError :message="errors.contactPersonPhone" class="mt-2" />
      </div>
    </div>

    <div class="grid grid-cols-1 gap-3">
      <div class="col-span-1">
        <Label :required="true">Contact Person Address</Label>
        <Textarea
            id="contactPersonAddress"
            v-model="form.contactPersonAddress"
            rows="2"
            name="contactPersonAddress"
            @input="validateField('contactPersonAddress', form)"
        />
        <InputError :message="errors.contactPersonAddress" class="mt-2" />
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-8 gap-3">
      <div class="col-span-1 sm:col-span-3">
        <Label :required="true">Registration Document</Label>
        <FileInput
            type="file"
            class="mt-1 block w-full"
            name="registrationDoc"
            accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf"
            @change="handleRegistrationDocumentUpload"
        />
        <a class="cursor-pointer text-secondary-400" v-if="isEditing && form.registrationDoc" :href="form.registrationDoc" target="_blank"
           download @click="form.registrationDoc">Download</a>
        <InputError :message="errors.registrationDoc"/>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
