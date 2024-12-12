<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Company" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div v-if="!isSaving">
        <BasicDetails :form="form" :errors="errors" :is-editing="isEditing" :validate-field="validateField"/>
      </div>

      <Saving v-else/>
    </div>
  </div>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import BasicDetails from "@/Components/Companies/Partials/BasicDetails.vue";
import { fetchRules, validateField, errors } from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
import FormButtonAndHeaderSection from "@/Components/Widgets/FormButtonAndHeaderSection.vue";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
});

const form = useForm({...props.modelValue});

let isLoading = ref(true)
const isSaving = ref(false);

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
});

onMounted(() => {
  fetchRules(route("admin.companies.rules"));
});

const handleError = (errorResponse) => {
  if (errorResponse) {
    const newErrors = {};

    for (const [field, messages] of Object.entries(errorResponse)) {
      if (Array.isArray(messages)) {
        newErrors[field] = messages.join('\n');
      } else {
        newErrors[field] = messages;
      }
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
    Inertia.post(route("admin.companies.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }

  Inertia.post(route("admin.companies.store"), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
