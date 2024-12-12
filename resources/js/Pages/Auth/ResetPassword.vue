<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import {computed, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import PasswordVisibilityToggleIcon from "@/Components/Widgets/PasswordVisibilityToggleIcon.vue";

const props = defineProps({
  email: String,
  token: String,
});

let page = usePage().props.value;
let authUser = page.authUser;

const isSaving = ref(false);

const showPassword = ref({
  password: false,
  passwordConfirmation: false,
});

const togglePasswordVisibility = (field) => {
  showPassword.value[field] = !showPassword.value[field];
};


const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

const form = useForm({
  token: props.token,
  email: authUser.email,
  currentPassword: '',
  password: '',
  password_confirmation: '',
});

const submit = () => {
  isSaving.value = true;
  Inertia.post(route('admin.auth.change-password'), form.data(), {
    onError: (errors) => {
      form.clearErrors().setErrors(errors);
      isSaving.value = false;
    },
    onSuccess: () => {
      form.reset('currentPassword', 'password', 'password_confirmation');
      isSaving.value = false;
    },
  });
};
</script>

<template>
  <Head title="Reset Password" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
            required
            disabled="disabled"
            autofocus
            autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-4">
        <InputLabel for="currentPassword" value="Current Password" />
        <TextInput
            id="currentPassword"
            v-model="form.currentPassword"
            type="password"
            class="mt-1 block w-full"
            required
            autocomplete="current-password"
        />
        <InputError class="mt-2" :message="form.errors.currentPassword" />
      </div>

      <div class="mt-4 relative">
        <InputLabel for="password" value="Password" />
        <div class="relative flex items-center">
          <TextInput
              id="password"
              v-model="form.password"
              :type="showPassword.password ? 'text' : 'password'"
              class="mt-1 block w-full"
              required
              autocomplete="new-password"
          />
          <PasswordVisibilityToggleIcon
              :showPassword="showPassword.password"
              label="password"
              @toggle-visibility="togglePasswordVisibility"
          />
        </div>
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4 relative">
        <InputLabel for="password_confirmation" value="Confirm Password" />
        <div class="relative flex items-center">
          <TextInput
              id="password_confirmation"
              v-model="form.password_confirmation"
              :type="showPassword.passwordConfirmation ? 'text' : 'password'"
              class="mt-1 block w-full"
              required
              autocomplete="new-password"
          />
          <PasswordVisibilityToggleIcon
              :showPassword="showPassword.passwordConfirmation"
              label="passwordConfirmation"
              @toggle-visibility="togglePasswordVisibility"
          />
        </div>
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Reset Password
        </PrimaryButton>
      </div>
    </form>
  </AuthenticationCard>
</template>
