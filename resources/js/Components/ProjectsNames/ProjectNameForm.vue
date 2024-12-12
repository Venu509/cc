<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Project Name" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="p-6">
        <div v-if="!isSaving">
          <div class="grid lg:grid-cols-2 gap-3">
              <div>
                <Label :required="true">Name</Label>
                <Input
                    id="firstName"
                    v-model="form.name"
                    autocomplete="name"
                    name="name"
                    type="text"
                    @input="validateField('name', form)"
                />
                <InputError :message="errors.name" class="mt-2"/>
              </div>
            </div>
        </div>
        <Saving v-else/>
      </div>
    </div>
  </div>

</template>

<script setup>
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import {fetchRules, validateField, errors} from '@/Components/Services/Validation';
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
const isLoginViaMobile = ref(true);

const buttonText = computed(() => {
  if (isSaving.value) {
    return props.isEditing ? 'Updating...' : 'Saving...';
  }

  return props.isEditing ? 'Update' : 'Save';
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

  if (props.isEditing) {
    Inertia.post(route("admin.projects-names.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.projects-names.store"), form, {
      onError: handleError,
    onSuccess: handleSuccess,
  });
}

onMounted(() => {
    fetchRules(route("admin.projects-names.rules"));
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
