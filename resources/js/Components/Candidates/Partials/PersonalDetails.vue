<script setup>
import Input from "@/Components/Input.vue";
import InputError from "@/Components/InputError.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import Label from "@/Components/Label.vue";
import {ref} from "vue";
import FileInput from "@/Components/FileInput.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {allowOnlyNumbers, maxDate, validateField} from "@/Components/Services/Validation";
import Multiselect from "@vueform/multiselect";
import Calendar from "@/Components/Widgets/Calendar.vue";

let page = usePage().props.value;
let role = page.role;

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  errors: {
    type: Object,
    required: true,
  },
  countries: {
    type: Object,
    required: true,
  },
  validateField: {
    type: Function,
    required: true,
  },
  maxDate: {
    type: Function,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const genderOptions = ref([
  {name: 'genderOption', value: 'male', label: 'Male', checked: false},
  {name: 'genderOption', value: 'female', label: 'Female', checked: false},
  {name: 'genderOption', value: 'other', label: 'Other', checked: false},
]);

const maritalStatusOptions = ref([
  {name: 'maritalStatus', value: 'single', label: 'Single', checked: false},
  {name: 'maritalStatus', value: 'married', label: 'Married', checked: false},
  {name: 'maritalStatus', value: 'divorced', label: 'Divorced', checked: false},
  {name: 'maritalStatus', value: 'widowed', label: 'Widowed', checked: false},
]);

const loginOption = ref([
  {name: 'loginOption', value: 'email', label: 'Email', checked: true},
  {name: 'loginOption', value: 'phone', label: 'Phone', checked: false},
])

function handleAvatarUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.avatar = file;
    validateField('avatar', props.form);
    const reader = new FileReader();
    reader.onload = (e) => {
      props.form.profilePreview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}

function handleResumeUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.resume = file;
    validateField('resume', props.form);
  }
}
</script>

<template>
  <div class="p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3" v-if="!isEditing">
      <div class="col-span-1">
        <Label for="via" value="Login Using?" :required="true"/>
        <RadioGroupInput
            v-model="form.via"
            :items="loginOption"
            @change="validateField('username', form)"
        />
        <InputError class="mt-2" :message="errors.via"/>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
      <div class="col-span-1 sm:col-span-1" v-if="!isEditing">
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
        <InputError class="mt-2" :message="errors.username"/>
      </div>

      <div class="col-span-1 sm:col-span-1">
        <Label :required="true">Full Name</Label>
        <Input
            id="fullName"
            v-model="form.fullName"
            autocomplete="fullName"
            name="fullName"
            type="text"
            maxlength="50"
            @input="validateField('fullName', form)"
        />
        <InputError :message="errors.fullName" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-1">
        <Label :required="true">Date Of Birth</Label>
        <Calendar
            id="dob"
            :model="form.dob"
            autocomplete="off"
            name="dob"
            :max-date="maxDate()"
            :field-name="'dob'"
            @update:model="form.dob = $event"
            @input="validateField('dob', form)"
        />
        <InputError :message="errors.dob" class="mt-2"/>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-8 gap-3">
      <div class="col-span-1 sm:col-span-3">
        <Label :required="true">Gender</Label>

        <select
            v-model="form.gender"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:hidden"
            @change="validateField('gender', form)">
          <option v-for="option in genderOptions" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>

        <RadioGroupInput
            v-model="form.gender"
            :items="genderOptions"
            @change="validateField('gender', form)"
            class="hidden sm:block"
        />

        <InputError :message="errors.gender" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-5">
        <Label :required="true">Marital Status</Label>

        <select
            v-model="form.maritalStatus"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:hidden"
            @change="validateField('maritalStatus', form)">
          <option v-for="option in maritalStatusOptions" :key="option.value" :value="option.value">
            {{ option.label }}
          </option>
        </select>

        <RadioGroupInput
            v-model="form.maritalStatus"
            :items="maritalStatusOptions"
            @change="validateField('maritalStatus', form)"
            class="hidden sm:block"
        />

        <InputError :message="errors.maritalStatus" class="mt-2"/>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
      <div class="col-span-1 sm:col-span-2">
        <Label :required="true">Email Address</Label>
        <Input
            id="email"
            v-model="form.email"
            autocomplete="email"
            name="email"
            type="email"
            maxlength="50"
            @input="validateField('email', form)"
        />
        <InputError :message="errors.email" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-2">
        <Label :required="true">Contact Number</Label>
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

      <div class="col-span-1 sm:col-span-2">
        <Label :required="false">Alternative Phone Number</Label>
        <Input
            id="alternativeNumber"
            v-model="form.alternativeNumber"
            autocomplete="alternativeNumber"
            name="alternativeNumber"
            type="tel"
            maxlength="12"
            @input="validateField('alternativeNumber', form)"
            @keydown="allowOnlyNumbers"
        />
        <InputError :message="errors.alternativeNumber" class="mt-2"/>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-12 gap-3">
      <div class="col-span-1 sm:col-span-4">
        <Label :required="true">Street Address</Label>
        <Input
            id="street"
            v-model="form.street"
            autocomplete="street"
            name="street"
            type="text"
            maxlength="100"
            @input="validateField('street', form)"
        />
        <InputError :message="errors.street" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-2">
        <Label :required="true">City</Label>
        <Input
            id="city"
            v-model="form.city"
            autocomplete="city"
            name="city"
            type="text"
            maxlength="41"
            @input="validateField('city', form)"
        />
        <InputError :message="errors.city" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-2">
        <Label :required="true">State</Label>
        <Input
            id="state"
            v-model="form.state"
            autocomplete="state"
            name="state"
            type="text"
            maxlength="41"
            @input="validateField('state', form)"
        />
        <InputError :message="errors.state" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-2">
        <Label :required="true">Postal Code</Label>
        <Input
            id="postalCode"
            v-model="form.postalCode"
            autocomplete="postalCode"
            name="postalCode"
            type="text"
            maxlength="8"
            @input="validateField('postalCode', form)"
            @keydown="allowOnlyNumbers"
        />
        <InputError :message="errors.postalCode" class="mt-2"/>
      </div>

      <div class="col-span-1 sm:col-span-2">
        <Label :required="true">Country</Label>
        <Multiselect
            v-model="form.country"
            mode="single"
            class="mt-1"
            :hide-selected="true"
            :close-on-select="true"
            :searchable="true"
            :options="countries"
            :loading="false"
            :object="true"
            @select="validateField('country', form)"
        />
        <InputError :message="errors.country" class="mt-2"/>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-8 gap-3">
      <div class="col-span-1 sm:col-span-3">
        <Label :required="true">Resume</Label>
        <FileInput
            type="file"
            class="mt-1 block w-full"
            name="resume"
            accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf"
            @change="handleResumeUpload"
        />
        <a class="cursor-pointer text-secondary-400" v-if="isEditing && form.resume" :href="form.resume" target="_blank"
           download @click="form.resume">Download</a>
        <InputError :message="errors.resume"/>
      </div>

      <div class="col-span-1 sm:col-span-3">
        <Label :required="role === 'candidate'">Profile Image</Label>
        <FileInput
            type="file"
            class="mt-1 block w-full"
            name="avatar"
            accept="image/x-png,image/gif,image/jpeg"
            @change="handleAvatarUpload"
        />
        <InputError :message="errors.avatar"/>
      </div>

      <div class="col-span-1 sm:col-span-2">
        <div v-show="form.profilePreview" class="mt-2">
          <img :src="form.profilePreview" class="rounded-full h-20 w-20 object-cover"/>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>
