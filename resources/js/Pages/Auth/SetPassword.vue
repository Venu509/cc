<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import {computed, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {EyeIcon, EyeOffIcon} from "@heroicons/vue/solid";

const props = defineProps({
  email: String,
  token: String,
});

let page = usePage().props.value;

const isSaving = ref(false);

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

const form = useForm({
  token: props.token,
  email: props.email,
  password: '',
  password_confirmation: '',
});

const submit = () => {
  isSaving.value = true;
  Inertia.post(route('front.auth.set-password'), form.data(), {
    onError: (errors) => {
      form.clearErrors().setErrors(errors);
      isSaving.value = false;
    },
    onSuccess: () => {
      form.reset('password', 'password_confirmation');
      isSaving.value = false;
    },
  });
};

const showPassword = ref({
    password: false,
    passwordConfirmation: false,
});

const togglePasswordVisibility = (field) => {
    showPassword.value[field] = !showPassword.value[field];
};
</script>

<template>
  <Head title="Set Password" />

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo />
    </template>

    <template #pageTitle>
      <h2 class="font-medium text-lg capitalize">Set Your New Password</h2>
    </template>

    <form @submit.prevent="submit">
      <div>

        <InputError class="mt-2" :message="form.errors.token" />

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

        <div class="mt-4 relative">
            <InputLabel for="password" value="Password" />
            <TextInput
                id="password"
                v-model="form.password"
                :type="showPassword.password ? 'text' : 'password'"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
            />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 top-[26px]">
                <EyeIcon v-if="showPassword.password" class="w-5 h-5 text-gray-700 cursor-pointer" @click="togglePasswordVisibility('password')"/>

                <EyeOffIcon v-if="!showPassword.password" class="w-5 h-5 text-gray-700 cursor-pointer" @click="togglePasswordVisibility('password')"/>
            </div>
            <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4 relative">
            <InputLabel for="password_confirmation" value="Confirm Password" />
            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showPassword.passwordConfirmation ? 'text' : 'password'"
                class="mt-1 block w-full"
                required
                autocomplete="new-password"
            />
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 top-[26px]">
                <EyeIcon v-if="showPassword.passwordConfirmation" class="w-5 h-5 text-gray-700 cursor-pointer" @click="togglePasswordVisibility('passwordConfirmation')"/>

                <EyeOffIcon v-if="!showPassword.passwordConfirmation" class="w-5 h-5 text-gray-700 cursor-pointer" @click="togglePasswordVisibility('passwordConfirmation')"/>
            </div>
            <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Set Password
        </PrimaryButton>
      </div>
    </form>

    <template #additional>
      <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4">Login</Link>
    </template>
  </AuthenticationCard>
</template>
