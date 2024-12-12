<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import {computed, onMounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {errors, validateField, fetchRules} from "@/Components/Services/Validation";
import Button from "@/Components/Button.vue";
import PasswordVisibilityToggleIcon from "@/Components/Widgets/PasswordVisibilityToggleIcon.vue";
import Label from "@/Components/Label.vue";

const props = defineProps({
  canResetPassword: {
    type: Boolean,
    default: true
  },
  status: String,
  selectedRole: String,
});

let page = usePage().props.value;
let intendedRoute = page.intendedRoute;

const isSaving = ref(false);

const showPassword = ref({
  password: false,
});

const togglePasswordVisibility = (field) => {
  showPassword.value[field] = !showPassword.value[field];
};

const form = useForm({
  username: '',
  password: '',
  remember: false,
  intendedRoute: intendedRoute,
});

const buttonText = computed(() => {
  return isSaving.value ? 'Login in...' : 'Login';
});

function save() {
  isSaving.value = true;

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

  // const transformedData = {
  //   ...form.data(),
  //   remember: form.remember ? 'on' : '',
  // };

  form.transform(data => ({
    ...data,
    remember: form.remember ? 'on' : '',
  })).post(route('admin.login'), {
    onFinish: () => form.reset('password'),
  });

  // Inertia.post(route('admin.login'), transformedData, {
  //   onError: handleError,
  //   onSuccess: () => {
  //     form.reset('password');
  //     isSaving.value = false;
  //   },
  // });
}

onMounted(() => {
  const isAuthenticated = !!page.authUser

  if (isAuthenticated) {
    window.history.back();
  }

  fetchRules(route("admin.login.rules"));
});


</script>

<template>
  <Head title="Log in"/>

  <AuthenticationCard>
    <template #logo>
      <AuthenticationCardLogo/>
    </template>

    <template #pageTitle>
      <h2 class="font-medium text-lg capitalize">Welcome Back To Dream Career</h2>
    </template>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="save" enctype="multipart/form-data">
      <div>
        <InputLabel for="username" value="Username (Email / Phone)"/>
        <TextInput
            id="username"
            v-model="form.username"
            type="text"
            class="mt-1 block w-full"
            required
            autofocus
            autocomplete="username"
            @input="validateField('username', form)"
        />
        <InputError class="mt-2" :message="errors.username"/>
      </div>

      <div class="mt-4 relative">
        <InputLabel for="password" value="Password"/>
        <div class="relative flex items-center">
          <TextInput
              id="password"
              v-model="form.password"
              :type="showPassword.password ? 'text' : 'password'"
              class="mt-1 block w-full pr-10 h-10 text-base"
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

      <div class="block mt-4">
        <label class="flex items-center">
          <Checkbox v-model:checked="form.remember" name="remember"/>
          <span class="ms-2 text-sm text-gray-600">Remember me</span>
        </label>
      </div>

      <div class="flex items-center justify-end mt-4">
        <Link v-if="canResetPassword" :href="route('password.request')"
              class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
          Forgot your password?
        </Link>

        <Button class="ms-4"
                btn-color="dark"
                @click="save" :disabled="isSaving">
          {{ buttonText }}
        </Button>
      </div>
    </form>

    <template #additional>
      <Link :href="route('front.index')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4">Go Home</Link>
    </template>
  </AuthenticationCard>
</template>
