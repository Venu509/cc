<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import Button from '@/Components/Button.vue';
import Input from '@/Components/Input.vue';
import FileInput from "@/Components/FileInput.vue";
import {Inertia} from "@inertiajs/inertia";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";
import {computed, onMounted, ref, watch} from "vue";
import {allowOnlyNumbers, errors, fetchRules, maxDate, validateField} from "@/Components/Services/Validation";
import PasswordVisibilityToggleIcon from "@/Components/Widgets/PasswordVisibilityToggleIcon.vue";
import Multiselect from "@vueform/multiselect";
import OTPVerificationInput from "@/Components/Widgets/OTPVerificationInput.vue";
import axios from "axios";
import Calendar from "@/Components/Widgets/Calendar.vue";

const props = defineProps({
  selectedRole: String,
  noticePeriods: {
    type: Object,
    required: true,
  },
  keySkills: {
    type: Object,
    required: true,
  },
  userPreferredLocations: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  via: 'email',
  companyName: '',
  contactPerson: '',
  contactPersonEmail: '',
  contactPersonPhone: '',
  contactPersonAddress: '',
  phone: '',
  websiteUrl: '',
  address: '',
  gst: '',
  yearsOfExistence: '',
  registrationDoc: null,
  dateOfRegistration: null,
  username: '',
  name: '',
  email: '',

  fullName: '',
  firstName: '',
  lastName: '',
  studentID: '',
  gender: 'male',
  dob: '',
  resume: null,
  panNumber: '',
  aadharNumber: '',
  qualification: '',
  maritalStatus: 'single',
  avatar: null,
  experience: '',
  skillSet: '',
  alternativeNumber: '',
  street: '',
  city: '',
  state: '',
  postalCode: '',
  country: {
    value: null,
    label: null,
  },

  password: '',
  password_confirmation: '',
  selectedRole: props.selectedRole,
  terms: false,
  isRegistrationForm: true,
});

const otpForm = useForm({
  type: 'register',
  via: 'email',
  email: null,
  phone: null,
})

const otpVerifyForm = useForm({
  type: 'register',
  via: 'email',
  email: null,
  phone: null,
  code: null,
})

const buttonText = computed(() => {
  return isSaving.value ? 'Saving ...' : 'Register';
});

const otpButtonText = computed(() => {
  return isSaving.value ? 'Sending OTP ...' : 'Send OTP';
});

const otpVerifyButtonText = computed(() => {
  return isSaving.value ? 'Verifying ...' : 'Verify';
});

const isSaving = ref(false);
const isLoading = ref(false);
const isOTPSent = ref(false);
const isVerifiedOTP = ref(false);
const incomingResponse = ref({});
const countries = ref({});

const showPassword = ref({
  password: false,
  passwordConfirmation: false,
});

const togglePasswordVisibility = (field) => {
  showPassword.value[field] = !showPassword.value[field];
};

function handleRegistrationDocUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.registrationDoc = file;
    validateField('registrationDoc', form);
  }
}

function handleResumeUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.resume = file;
    validateField('resume', form);
  }
}

function handleAvatarUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.avatar = file;
    validateField('avatar', form);
  }
}

const handleError = (errorResponse) => {
  console.log({errorResponse})
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

function save() {
  isSaving.value = true;
  Inertia.post(route('register'), form, {
    onError: handleError,
    onSuccess: () => {
      form.reset();
      isSaving.value = false;
    },
  });
}

function sendOTP() {
  isSaving.value = true;

  axios.post(route('auth.sendOtp'), otpForm)
      .then(response => {
        const data = response.data;
        isSaving.value = false;
        isOTPSent.value = true
        otpVerifyForm.email = otpForm.email
        otpVerifyForm.via = otpForm.via
        otpVerifyForm.phone = otpForm.phone
        otpVerifyForm.username = otpForm.via === 'email' ? otpForm.email : otpForm.phone
      }).catch(error => {
    isOTPSent.value = false;
    isSaving.value = false;

    const formattedErrors = {};
    const errors = error.response.data.errors || {};

    for (const [field, messages] of Object.entries(errors)) {
      if (Array.isArray(messages)) {
        formattedErrors[field] = messages[0];
      }
    }

    handleError(formattedErrors);
  })
}

const handleOtpSubmission = (otp) => {
  isSaving.value = true;

  otpVerifyForm.code = otp;

  axios.post(route('auth.verifyOtp'), otpVerifyForm)
      .then(response => {
        const data = response.data;
        incomingResponse.value = response
        isSaving.value = false;
        if (data.status) {
          isVerifiedOTP.value = true
          form.email = otpVerifyForm.email
          form.via = otpVerifyForm.via
          form.username = otpVerifyForm.via === 'email' ? otpVerifyForm.email : otpVerifyForm.phone
          form.phone = otpVerifyForm.phone
        } else {
          isVerifiedOTP.value = false
        }

      }).catch(error => {
    isSaving.value = false;

    const formattedErrors = {};
    const errors = error.response.data.errors || {};

    for (const [field, messages] of Object.entries(errors)) {
      if (Array.isArray(messages)) {
        formattedErrors[field] = messages[0];
      }
    }

    handleError(formattedErrors);
  });
};

const loginOption = ref([
  {name: 'loginOption', value: 'email', label: 'Email', checked: true},
  {name: 'loginOption', value: 'phone', label: 'Phone', checked: false},
])

const genderOptions = ref([
  {name: 'genderOption', value: 'male', label: 'Male', checked: true},
  {name: 'genderOption', value: 'female', label: 'Female', checked: false},
  {name: 'genderOption', value: 'other', label: 'Other', checked: false},
]);

const maritalStatusOptions = ref([
  {name: 'maritalStatus', value: 'single', label: 'Single', checked: true},
  {name: 'maritalStatus', value: 'married', label: 'Married', checked: false},
  {name: 'maritalStatus', value: 'divorced', label: 'Divorced', checked: false},
  {name: 'maritalStatus', value: 'widowed', label: 'Widow', checked: false},
]);

const isGovernment = computed(() => props.selectedRole === 'government')

const isCandidate = computed(() => props.selectedRole === 'candidate')

const isInstitution = computed(() => props.selectedRole === 'institution')

const isCompany = computed(() => props.selectedRole === 'company')

const isValidOTPSent = computed(() => {
  return isCompany.value ? isOTPSent.value : true;
})

const isCompanyOTPVerified = computed(() => {
  return isCompany.value ? (isVerifiedOTP.value) : true;
})

function switchProvider() {
  isOTPSent.value = false
}

onMounted(() => {
  getCountries()
  fetchRules(route("register.rules"));
});

function filterInput(column, event) {
  validateField(column, form);
  allowOnlyNumbers(event);
}

function getCountries() {
  isLoading.value = true
  axios.get(route('admin.countries.fetch'))
      .then(response => {
        countries.value = response.data.countries
        isLoading.value = false
      })
      .catch(error => {
        console.error('Error fetching rules:', error);
        isLoading.value = false
      });
}

// const validatePasswordConfirmation = () => {
//   if (form.password !== form.password_confirmation) {
//     errors.password_confirmation = 'Passwords do not match.';
//   } else {
//     errors.password_confirmation = '';
//   }
// };
//
// watch(
//     () => form.password,
//     validatePasswordConfirmation
// );
// watch(
//     () => form.password_confirmation,
//     validatePasswordConfirmation
// );
</script>

<template>
  <Head title="Register"/>

  <AuthenticationCard
      max-width="max-w-3xl"
      override-classes="bg-gray-100 shadow-none sm:rounded-none`"
  >
    <template #logo>
      <AuthenticationCardLogo/>
    </template>

    <template #pageTitle>
      <h2 class="font-medium text-lg capitalize">{{ selectedRole }} Registration</h2>
    </template>

    <form v-show="isCompany ? (isCompanyOTPVerified && isVerifiedOTP) : true" @submit.prevent="submit"
          enctype="multipart/form-data">
      <div class="bg-white shadow-xl sm:rounded-xl px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
          <div class="col-span-1" v-if="isCandidate">
            <Label for="fullName" value="Full Name" :required="true"/>
            <Input
                id="fullName"
                v-model="form.fullName"
                type="text"
                class="block w-full"
                required
                autocomplete="off"
                @input="validateField('fullName', form)"
            />
            <InputError class="mt-2" :message="errors.fullName"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution || isCompany">
            <Label for="companyName" :value="!isCompany ? 'College Name' : 'Company Name'" :required="true"/>
            <Input
                id="companyName"
                v-model="form.companyName"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('companyName', form)"
            />
            <InputError class="mt-2" :message="errors.companyName"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution">
            <Label for="dateOfRegistration" value="Date Of Registration" :required="true"/>
            <Calendar
                id="dateOfRegistration"
                :model="form.dateOfRegistration"
                autocomplete="off"
                name="dateOfRegistration"
                :max-date="maxDate()"
                :field-name="'dateOfRegistration'"
                @update:model="form.dateOfRegistration = $event"
                @input="validateField('dateOfRegistration', form)"
            />
            <InputError class="mt-2" :message="errors.dateOfRegistration"/>
          </div>

          <div class="col-span-1" v-if="isCompany || isGovernment || isInstitution">
            <Label for="address" :value="!isCompany ? 'Address' : 'Company Address'" :required="true"/>
            <Input
                id="address"
                v-model="form.address"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('address', form)"
            />
            <InputError class="mt-2" :message="errors.address"/>
          </div>

          <div class="col-span-1">
            <Label for="phone" class="capitalize"
                   :value="`${(isGovernment || isInstitution) ? 'College' : '' } Phone Number`" :required="true"/>
            <Input
                id="phone"
                v-model="form.phone"
                type="tel"
                class="block w-full"
                autocomplete="off"
                @input="validateField('phone', form)"
                @keydown="allowOnlyNumbers"
            />
            <InputError class="mt-2" :message="errors.phone"/>
          </div>

          <div class="col-span-1">
            <Label for="email" class="capitalize" :value="`${(isGovernment || isInstitution) ? 'College' : '' } Email`"
                   :required="true"/>
            <Input
                id="email"
                v-model="form.email"
                type="email"
                class="block w-full"
                autocomplete="off"
                @input="validateField('email', form)"
            />
            <InputError class="mt-2" :message="errors.email"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution">
            <Label for="yearsOfExistence" value="Number of Years of Existence" :required="true"/>
            <Input
                id="yearsOfExistence"
                v-model="form.yearsOfExistence"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('yearsOfExistence', form)"
                @keydown="(event) => allowOnlyNumbers(event, false)"
            />
            <InputError class="mt-2" :message="errors.yearsOfExistence"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution || isCompany">
            <Label for="contactPerson" value="Contact Person Name" :required="true"/>
            <Input
                id="contactPerson"
                v-model="form.contactPerson"
                type="text"
                class="block w-full"
                autocomplete="off"
                maxlength="60"
                @input="validateField('contactPerson', form)"
            />
            <InputError class="mt-2" :message="errors.contactPerson"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution || isCompany">
            <Label for="contactPersonPhone" value="Contact Person Number" :required="true"/>
            <Input
                id="contactPersonPhone"
                v-model="form.contactPersonPhone"
                type="tel"
                class="block w-full"
                autocomplete="off"
                @input="validateField('contactPersonPhone', form)"
                @keydown="allowOnlyNumbers"
            />
            <InputError class="mt-2" :message="errors.contactPersonPhone"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution || isCompany">
            <Label for="contactPersonEmail" value="Contact Person Email" :required="true"/>
            <Input
                id="contactPersonEmail"
                v-model="form.contactPersonEmail"
                type="email"
                class="block w-full"
                autocomplete="off"
                @input="validateField('contactPersonEmail', form)"
            />
            <InputError class="mt-2" :message="errors.contactPersonEmail"/>
          </div>

          <div class="col-span-1" v-if="isGovernment || isInstitution || isCompany">
            <Label for="contactPersonAddress" value="Contact Person Address" :required="true"/>
            <Input
                id="contactPersonAddress"
                v-model="form.contactPersonAddress"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('contactPersonAddress', form)"
            />
            <InputError class="mt-2" :message="errors.contactPersonAddress"/>
          </div>

          <div class="col-span-1" v-if="isCompany || isGovernment || isInstitution">
            <Label for="registrationDoc" :value="(isCompany ? 'Company' :  isGovernment ? 'Government' : isInstitution? 'Institution' : '') + (' Registration Document')" :required="true"/>
            <FileInput
                id="registrationDoc"
                type="file"
                accept=".doc, .docx, .pdf"
                class="block w-full"
                name="registrationDoc"
                @change="handleRegistrationDocUpload"
            />
            <InputError class="mt-2" :message="errors.registrationDoc"/>
          </div>

          <div class="col-span-1" v-if="isCompany">
            <Label for="gst" value="GST" :required="false" note="(Optional)" note-text-color="text-indigo-500"/>
            <Input
                id="gst"
                v-model="form.gst"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('gst', form)"
            />
            <InputError class="mt-2" :message="errors.gst"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label for="dob" value="Date Of Birth" :required="true"/>
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
            <InputError class="mt-2" :message="errors.dob"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label for="gender" value="Gender" :required="true"/>

            <select
                v-model="form.gender"
                class="block w-full border-gray-300 rounded-md shadow-sm sm:hidden"
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
            <InputError class="mt-2" :message="errors.gender"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label for="resume" value="Resume" :required="true"/>
            <FileInput
                id="resume"
                type="file"
                accept=".doc, .docx, .pdf"
                class="block w-full"
                name="resume"
                @change="handleResumeUpload"
            />
            <InputError class="mt-2" :message="errors.resume"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label for="maritalStatus" value="Marital Status" :required="true"/>

            <select
                v-model="form.maritalStatus"
                class="block w-full border-gray-300 rounded-md shadow-sm sm:hidden"
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
            <InputError class="mt-2" :message="errors.maritalStatus"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label for="avatar" value="Profile Picture" :required="true"/>
            <FileInput
                id="avatar"
                type="file"
                accept="image/x-png,image/gif,image/jpeg"
                class="block w-full"
                name="avatar"
                @change="handleAvatarUpload"
            />
            <InputError class="mt-2" :message="errors.avatar"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label for="alternativeNumber" value="Alternative Number"/>
            <Input
                id="alternativeNumber"
                v-model="form.alternativeNumber"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('alternativeNumber', form)"
                @keydown="allowOnlyNumbers"
            />
            <InputError class="mt-2" :message="errors.alternativeNumber"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="preferredJobRole" value="Preferred Job Title"/>
            <Input
                id="preferredJobRole"
                v-model="form.preferredJobRole"
                type="text"
                class="block w-full"
                autocomplete="off"
                maxlength="41"
                @input="validateField('preferredJobRole', form)"
            />
            <InputError class="mt-2" :message="errors.preferredJobRole"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="preferredJobLocation" value="Expected Job Location"/>
            <Multiselect
                v-model="form.preferredJobLocation"
                class="p-0"
                mode="tags"
                placeholder="Search or add job location"
                tag-placeholder="Add this as new job location"
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

          <div class="col-span-1" v-if="isCandidate">
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

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true">Key Skills</Label>
            <Multiselect
                v-model="form.keySkills"
                class="p-0"
                mode="tags"
                placeholder="Search or add skills"
                tag-placeholder="Add this as new skills"
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

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="expectedSalary" value="Expected Salary(per annum)"/>
            <Input
                id="expectedSalary"
                v-model="form.expectedSalary"
                type="text"
                class="block w-full placeholder-gray-400"
                maxlength="11"
                autocomplete="off"
                placeholder="Ex : 300000"
                @keydown="allowOnlyNumbers"
                @input="validateField('expectedSalary', form)"
            />
            <InputError class="mt-2" :message="errors.expectedSalary"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="street" value="Street"/>
            <Input
                id="street"
                v-model="form.street"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('street', form)"
            />
            <InputError class="mt-2" :message="errors.street"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="city" value="City"/>
            <Input
                id="city"
                v-model="form.city"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('city', form)"
            />
            <InputError class="mt-2" :message="errors.city"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="state" value="State"/>
            <Input
                id="state"
                type="text"
                v-model="form.state"
                class="block w-full"
                autocomplete="off"
                @input="validateField('state', form)"
            />
            <InputError class="mt-2" :message="errors.state"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="postalCode" value="Postal Code"/>
            <Input
                id="postalCode"
                v-model="form.postalCode"
                type="text"
                class="block w-full"
                autocomplete="off"
                @input="validateField('postalCode', form)"
                @keydown="allowOnlyNumbers"
            />
            <InputError class="mt-2" :message="errors.postalCode"/>
          </div>

          <div class="col-span-1" v-if="isCandidate">
            <Label :required="true" for="country" value="Country"/>
            <Multiselect
                v-model="form.country"
                mode="single"
                class="mt-1"
                :hide-selected="true"
                :close-on-select="true"
                :searchable="true"
                :options="countries"
                :loading="isLoading"
                :object="true"
                @select="validateField('country', form)"
            />
            <InputError class="mt-2" :message="errors.country"/>
          </div>
        </div>
      </div>

      <div class="bg-white shadow-xl sm:rounded-xl px-6 py-4 mt-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
          <div class="col-span-1">
            <Label for="via" value="Login Using?" :required="true"/>
            <RadioGroupInput
                v-model="form.via"
                :items="loginOption"
                @change="validateField('username', form)"
            />
            <InputError class="mt-2" :message="errors.via"/>
          </div>

          <div class="col-span-1">
            <Label for="username" :value="form.via === 'email' ? 'Username (Email)' : 'Username (Phone)'"
                   :required="true"/>
            <Input
                id="username"
                v-model="form.username"
                :type="form.via === 'email' ? 'email' : 'tel'"
                class="block w-full"
                autofocus
                autocomplete="off"
                @input="validateField('username', form)"
                @keydown="form.via === 'phone' ? allowOnlyNumbers : null"
            />
            <InputError class="mt-2" :message="errors.username"/>
          </div>

          <div class="col-span-1 relative">
            <Label for="password  " value="Password" :required="true"/>
            <div class="relative flex items-center">
              <Input
                  id="password"
                  v-model="form.password"
                  :type="showPassword.password ? 'text' : 'password'"
                  class="block w-full pr-10 h-10 text-base"
                  required
                  autocomplete="off"
                  @input="validateField('password', form)"
              />
              <PasswordVisibilityToggleIcon
                  class="absolute right-3 top-1/2 transform -translate-y-1/2"
                  :showPassword="showPassword.password"
                  label="password"
                  @toggle-visibility="togglePasswordVisibility"
              />
            </div>
            <InputError class="mt-2" :message="errors.password"/>
          </div>

          <div class="col-span-1 relative">
            <Label for="password_confirmation" value="Confirm Password" :required="true"/>
            <div class="relative flex items-center">
              <Input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :type="showPassword.passwordConfirmation ? 'text' : 'password'"
                  class="block w-full pr-10 h-10 text-base"
                  autocomplete="off"
                  @input="validateField('password_confirmation', form)"
              />
              <PasswordVisibilityToggleIcon
                  class="absolute right-3 top-1/2 transform -translate-y-1/2"
                  :showPassword="showPassword.passwordConfirmation"
                  label="passwordConfirmation"
                  @toggle-visibility="togglePasswordVisibility"
              />
            </div>
            <InputError class="mt-2" :message="errors.password_confirmation"/>
          </div>
        </div>
      </div>


      <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="text-center mt-4">
        <Label for="terms">
          <div class="flex items-center">
            <Checkbox id="terms" v-model:checked="form.terms" name="terms" required/>

            <div class="ms-2">
              I agree to the <a target="_blank" :href="route('terms.show')"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Terms
              of Service</a> and <a target="_blank" :href="route('policy.show')"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Privacy
              Policy</a>
            </div>
          </div>
          <InputError class="mt-2" :message="errors.terms"/>
        </Label>
      </div>

      <div class="flex items-center justify-between mt-2 mx-4 sm:mx-20">
        <Link :href="route('login')"
              class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Already registered?
        </Link>

        <Button class="ms-4"
                btn-color="dark"
                @click="save" :disabled="isSaving">
          {{ buttonText }}
        </Button>
      </div>
    </form>

    <div v-show="isCompany ? (!isCompanyOTPVerified && !isValidOTPSent) : false">
      <div class="bg-white shadow-xl sm:rounded-xl px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-3">
          <div class="col-span-1" v-if="isCompany && otpForm.via === 'phone'">
            <Label for="phone" value="Enter Mobile Number" :required="true"/>
            <Input
                id="phone"
                v-model="otpForm.phone"
                type="text"
                class="block w-full"
                required
                autofocus
                autocomplete="off"
                @input="validateField('phone', otpForm)"
                @keydown="allowOnlyNumbers"
            />
            <InputError class="mt-2" :message="errors.phone"/>
          </div>

          <div class="col-span-1" v-if="isCompany && otpForm.via === 'email'">
            <Label for="email" value="Enter Email Address" :required="true"/>
            <Input
                id="email"
                v-model="otpForm.email"
                type="email"
                class="block w-full"
                required
                autofocus
                autocomplete="off"
                @input="validateField('email', otpForm)"
            />
            <InputError class="mt-2" :message="errors.email"/>
          </div>

          <div v-if="isCompany" class="col-span-1 flex items-center justify-end mt-2">
            <Button class="ms-4"
                    btn-color="dark"
                    @click="sendOTP" :disabled="isSaving">
              {{ otpButtonText }}
            </Button>
          </div>
        </div>
      </div>
    </div>

    <div v-show="isCompany ? (isValidOTPSent && !isVerifiedOTP) : false">
      <div class="bg-white shadow-xl sm:rounded-xl px-6 py-4">
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-3">
          <OTPVerificationInput :response="incomingResponse"
                                :is-sending="isSaving"
                                :via="otpForm.via"
                                :form="otpForm"
                                @otp-submitted="handleOtpSubmission"
                                @switch-provider="switchProvider"
                                @resend-requested="sendOTP"
          />
        </div>
      </div>
    </div>

    <template #additional>
      <Link :href="route('front.index')"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4">
        Go Home
      </Link>
    </template>
  </AuthenticationCard>
</template>
