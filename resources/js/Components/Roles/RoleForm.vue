<template>
  <div class="p-6 bg-white shadow-lg rounded-lg">

    <div v-if="!isSaving">

      <div class="grid lg:grid-cols-2 gap-3 mb-2">
        <div class="col-span-1">
          <Label :required="true">Name</Label>
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
          <Label :required="true">Display Name</Label>
          <Input
              id="displayName"
              v-model="form.displayName"
              autocomplete="displayName"
              name="displayName"
              type="text"
              @input="validateField('displayName', form)"
          />
          <InputError :message="errors.displayName" class="mt-2"/>
        </div>
      </div>

      <div class="overflow-x-auto">
        <div class="mt-5 md:mt-0 md:col-span-2 px-4">
          <div class="bg-white">
            <DomainGroup
                v-for="(permission, index) in permissions"
                :key="index"
                v-model:permissions="form.permissions"
                :label="index"
                :domains="permission"
                :name="index"
                :update-permissions="permissions"
            />
          </div>
        </div>
      </div>
    </div>

    <Saving v-else/>
    <div class="flex mt-4">
      <Button
          class="mr-3"
          btn-color="dark"
          @click="back()"
      >
        Back
      </Button>

      <Button
          :disabled="isSaving"
          @click="save"
      >
        {{ buttonText }}
      </Button>
    </div>
  </div>
</template>

<script setup>

import {errors, validateField} from "@/Components/Services/Validation";
import Input from "@/Components/Input.vue";
import Label from "@/Components/Label.vue";
import InputError from "@/Components/InputError.vue";
import {useForm} from "@inertiajs/vue3";
import {computed, ref} from "vue";
import DomainGroup from "@/Components/Roles/Partials/DomainGroup.vue";
import Saving from "@/Components/Widgets/Saving.vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  permissions: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
});

const form = useForm({...props.modelValue});

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

let isLoading = ref(true)
const isSaving = ref(false);


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
    Inertia.post(route("admin.roles.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }

  Inertia.post(route("admin.roles.store"), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

function back() {
  window.history.back();
  Inertia.reload()
}
</script>

<style scoped>
input[type='checkbox'] {
  width: 1.25rem;
  height: 1.25rem;
}
</style>
