<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Button from '@/Components/Button.vue';
import TextInput from '@/Components/TextInput.vue';
import {computed, onMounted, ref} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {errors, validateField, fetchRules} from "@/Components/Services/Validation";

defineProps({
    status: String,
});

const form = useForm({
  username: '',
});

const isSaving = ref(false);

const buttonText = computed(() => {
  return isSaving.value ? 'Sending ....' : 'Send Password Reset Link';
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
  isSaving.value = true;
  Inertia.post(route('admin.password.email'), form.data(), {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

onMounted(() => {
  fetchRules(route("admin.password.email.rules"));
});
</script>

<template>
    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            Forgot your password? No problem. Just let us know your username and we will send you a password reset link that will allow you to choose a new one.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form>
            <div>
                <InputLabel for="username" value="Username" />
                <TextInput
                    id="email"
                    v-model="form.username"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="off"
                    @input="validateField('username', form)"
                />
                <InputError class="mt-2" :message="errors.username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Button
                    btn-color="dark"
                    @click="save" :disabled="isSaving">
                  {{ buttonText }}
                </Button>
            </div>
        </form>

      <template #additional>
        <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mt-4">Login</Link>
      </template>
    </AuthenticationCard>
</template>
