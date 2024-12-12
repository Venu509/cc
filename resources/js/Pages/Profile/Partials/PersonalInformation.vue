<script setup>
import InputError from "@/Components/InputError.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import Button from "@/Components/Button.vue";
import {allowOnlyNumbers, maxDate, validateField} from "@/Components/Services/Validation";
import FileInput from "@/Components/FileInput.vue";
import Calendar from "@/Components/Widgets/Calendar.vue";

let props = defineProps({
  form: {
    type: Object,
    required: true,
  },
  validateField: {
    type: Function,
    required: true,
  },
  errors: {
    type: Object,
    required: true,
  },
  isSaving: {
    type: Boolean,
    default: false,
  },
});

let emit = defineEmits(['save'])

let page = usePage().props.value;
let role = page.role

const buttonText = computed(() => {
  return props.isSaving ? 'Updating ....' : 'Update';
});

const isGovernment = computed(() => role.name === 'government')

const isCandidate = computed(() => role.name === 'candidate')

const isInstitution = computed(() => role.name === 'institution')

const isCompany = computed(() => role.name === 'company')

const isInstitutionOrGovernment = computed(() => isInstitution || isGovernment)

function save() {
  emit('save', props.form)
}

function handleRegistrationDocUpload(event) {
  const file = event.target.files[0];
  if (file) {
    props.form.registrationDoc = file;
    validateField('registrationDoc', props.form);
    const reader = new FileReader();
    reader.readAsDataURL(file);
  }
}
</script>

<template>
  <div class="flex-1 bg-gray-50 p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold">Personal information</h3>
    <p class="text-gray-600 text-sm mb-6">Manage your personal information, including phone numbers and email
      address where you can be contacted</p>

    <div class="grid grid-cols-6 gap-3">
      <div v-if="isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Collage Name</Label>
        <Input
            id="name"
            v-model="form.name"
            autocomplete="name"
            class="mt-1 block w-full"
            name="name"
            type="text"
            @input="validateField('name', form)"
        />
        <InputError :message="errors.name" class="mt-2"/>
      </div>

      <div v-if="isCompany" class="col-span-3">
        <Label :required="true">{{ isCompany ? 'Company' : 'Collage'  }}  Name</Label>
        <Input
            id="companyName"
            v-model="form.companyName"
            autocomplete="companyName"
            class="mt-1 block w-full"
            name="companyName"
            type="text"
            @input="validateField('companyName', form)"
        />
        <InputError :message="errors.companyName" class="mt-2"/>
      </div>

      <div v-if="isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Date Of Registration</Label>
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

      <div v-if="isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Number of Years of Existence</Label>
        <Input
            id="yearsOfExistence"
            v-model="form.yearsOfExistence"
            type="text"
            class="block w-full"
            autofocus
            autocomplete="off"
            @input="validateField('yearsOfExistence', form)"
            @keydown="(event) => allowOnlyNumbers(event, false)"
        />
        <InputError :message="errors.yearsOfExistence" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <Label :required="true">Email</Label>
        <Input
            id="email"
            v-model="form.email"
            autocomplete="email"
            class="mt-1 block w-full"
            name="email"
            type="text"
            @input="validateField('email', form)"
        />
        <InputError :message="errors.email" class="mt-2"/>
      </div>

      <div class="col-span-3">
        <Label :required="true">Phone</Label>
        <Input
            id="mobileNumber"
            v-model="form.mobileNumber"
            autocomplete="mobileNumber"
            class="mt-1 block w-full"
            name="mobileNumber"
            type="text"
            @keydown="allowOnlyNumbers"
            @input="validateField('mobileNumber', form)"
        />
        <InputError :message="errors.mobileNumber" class="mt-2"/>
      </div>

      <div v-if="isCompany || isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">{{ isCompany ? 'Company' : ''  }} Address</Label>
        <Input
            id="address"
            v-model="form.address"
            autocomplete="address"
            class="mt-1 block w-full"
            name="address"
            type="text"
            @input="validateField('address', form)"
        />
        <InputError :message="errors.address" class="mt-2"/>
      </div>

      <div v-if="isCompany || isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Contact Person Name</Label>
        <Input
            id="contactPerson"
            v-model="form.contactPerson"
            autocomplete="contactPerson"
            class="mt-1 block w-full"
            name="contactPerson"
            type="text"
            @input="validateField('contactPerson', form)"
        />
        <InputError :message="errors.contactPerson" class="mt-2"/>
      </div>

      <div v-if="isCompany || isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Contact Person Number</Label>
        <Input
            id="contactPersonPhone"
            v-model="form.contactPersonPhone"
            autocomplete="contactPersonPhone"
            class="mt-1 block w-full"
            name="contactPersonPhone"
            type="text"
            @keydown="allowOnlyNumbers"
            @input="validateField('contactPersonPhone', form)"
        />
        <InputError :message="errors.contactPersonPhone" class="mt-2"/>
      </div>

      <div v-if="isCompany || isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Contact Person Email</Label>
        <Input
            id="contactPersonEmail"
            v-model="form.contactPersonEmail"
            autocomplete="contactPersonEmail"
            class="mt-1 block w-full"
            name="contactPersonEmail"
            type="text"
            @input="validateField('contactPersonEmail', form)"
        />
        <InputError :message="errors.contactPersonEmail" class="mt-2"/>
      </div>

      <div v-if="isCompany || isInstitutionOrGovernment" class="col-span-3">
        <Label :required="true">Contact Person Address</Label>
        <Input
            id="contactPersonAddress"
            v-model="form.contactPersonAddress"
            autocomplete="contactPersonAddress"
            class="mt-1 block w-full"
            name="contactPersonAddress"
            type="text"
            @input="validateField('contactPersonAddress', form)"
        />
        <InputError :message="errors.contactPersonAddress" class="mt-2"/>
      </div>

      <div v-if="isCompany" class="col-span-3">
        <Label :required="false">GST Number</Label>
        <Input
            id="gst"
            v-model="form.gst"
            autocomplete="gst"
            class="mt-1 block w-full"
            name="gst"
            type="text"
            @input="validateField('gst', form)"
        />
        <InputError :message="errors.gst" class="mt-2"/>
      </div>

      <div v-if="isCompany || isInstitutionOrGovernment" class="col-span-3">
        <Label :required="false">Registration Document</Label>
        <FileInput
            type="file"
            accept="application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf"
            class="mt-1 block w-full"
            name="registrationDoc"
            @change="handleRegistrationDocUpload"
        />
        <InputError :message="errors.registrationDoc" class="mt-2"/>

        <a class="cursor-pointer text-secondary-400" v-if="form.registrationDocPreview" :href="form.registrationDocPreview" target="_blank"
           download @click="form.registrationDocPreview">Download</a>
      </div>

      <div class="col-span-6">
        <Button
            btn-color="dark"
            :disabled="isSaving"
            @click="save"
        >
          {{ buttonText }}
        </Button>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>