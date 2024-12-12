<script setup>
import {computed, onMounted, ref} from 'vue';
import { useForm } from '@inertiajs/vue3';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {Inertia} from "@inertiajs/inertia";
import Button from "@/Components/Button.vue";
import PasswordVisibilityToggleIcon from "@/Components/Widgets/PasswordVisibilityToggleIcon.vue";
import {errors, fetchRules, validateField} from "@/Components/Services/Validation";
import Textarea from "@/Components/Textarea.vue";
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";


const props = defineProps({
  user: Object,
});

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const isSaving = ref(false);

const buttonText = computed(() => {
  return isSaving.value ? 'Updating ....' : 'Update';
});

const form = useForm({
  email: props.user.email,
  currentPassword: '',
  password: '',
  passwordConfirmation: '',
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
  Inertia.post(route('admin.auth.change-password'), form.data(), {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

const showPassword = ref({
    password: false,
    currentPassword: false,
    passwordConfirmation: false,
});

const togglePasswordVisibility = (field) => {
    showPassword.value[field] = !showPassword.value[field];
};

onMounted(() => {
  fetchRules(route("admin.auth.change-password-rules"));
});
</script>

<template>
  <div class="flex-1 bg-gray-50 p-6 rounded-lg shadow-md">
    <h3 class="text-lg font-semibold">Change Password</h3>
    <p class="text-gray-600 text-sm mb-6">Manage your personal information, including phone numbers and email
      address where you can be contacted</p>

    <div class="grid grid-cols-6 gap-3">

      <div class="col-span-6">
        <Label :required="true">Current Password</Label>
        <div class="relative flex items-center">
          <TextInput
              id="currentPassword"
              v-model="form.currentPassword"
              :type="showPassword.currentPassword ? 'text' : 'password'"
              class="mt-1 block w-full pr-10 h-10 text-base"
              required
              autocomplete="current-password"
              @input="validateField('currentPassword', form)"
          />

          <PasswordVisibilityToggleIcon
              class="absolute right-3 top-1/2 transform -translate-y-1/2"
              :showPassword="showPassword.currentPassword"
              label="currentPassword"
              @toggle-visibility="togglePasswordVisibility"
          />
        </div>
        <InputError :message="errors.currentPassword" class="mt-2" />
      </div>

      <div class="col-span-6">
        <Label :required="true">Password</Label>
        <div class="relative flex items-center">
          <TextInput
              id="password"
              ref="passwordInput"
              v-model="form.password"
              :type="showPassword.password ? 'text' : 'password'"
              class="mt-1 block w-full pr-10 h-10 text-base"
              autocomplete="new-password"
              @input="validateField('password', form)"
          />

          <PasswordVisibilityToggleIcon
              class="absolute right-3 top-1/2 transform -translate-y-1/2"
              :showPassword="showPassword.password"
              label="password"
              @toggle-visibility="togglePasswordVisibility"
          />
        </div>
        <InputError :message="errors.password" class="mt-2" />
      </div>

      <div class="col-span-6">
        <Label :required="true">Password Confirm</Label>
        <div class="relative flex items-center">
          <TextInput
              id="password_confirmation"
              v-model="form.passwordConfirmation"
              :type="showPassword.passwordConfirmation ? 'text' : 'password'"
              class="mt-1 block w-full pr-10 h-10 text-base"
              autocomplete="new-password"
              @input="validateField('passwordConfirmation', form)"
          />
          <PasswordVisibilityToggleIcon
              class="absolute right-3 top-1/2 transform -translate-y-1/2"
              :showPassword="showPassword.passwordConfirmation"
              label="passwordConfirmation"
              @toggle-visibility="togglePasswordVisibility"
          />
        </div>
        <InputError :message="errors.passwordConfirmation" class="mt-2" />
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
