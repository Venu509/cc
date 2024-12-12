<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Lead" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="p-6">
        <div v-if="!isSaving">
          <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-6">
            <div class="col-span-1 sm:col-span-2 md:col-span-4">
              <Label :required="true">Lead Name</Label>
              <Input
                  id="title"
                  v-model="form.title"
                  autocomplete="title"
                  class="mt-1 block w-full"
                  name="title"
                  type="text"
                  @input="validateField('title', form)"
              />
              <InputError :message="errors.title" class="mt-2"/>
            </div>

            <div class="col-span-1 sm:col-span-1 md:col-span-2">
              <Label :required="true">Lead Type</Label>
              <Multiselect
                  v-model="form.type"
                  mode="single"
                  class="mt-0.5"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :reset-after="true"
                  :object="true"
                  :options="types"
                  @select="validateField('type', form)"
              />
              <InputError :message="errors.type" class="mt-2"/>
            </div>

            <div class="col-span-1 sm:col-span-2 md:col-span-6">
              <Label for="status">Status</Label>
              <Textarea
                  id="status"
                  v-model="form.status"
                  autocomplete="status"
                  class="mt-1 block w-full"
                  name="status"
                  type="text"
                  rows="2"
                  @input="validateField('status', form)"
              />
              <InputError :message="errors.status" class="mt-2"/>
            </div>

            <div class="col-span-1 sm:col-span-2 md:col-span-6">
              <Label for="description">Description</Label>
              <Textarea
                  id="description"
                  v-model="form.description"
                  autocomplete="description"
                  class="mt-1 block w-full"
                  name="description"
                  type="text"
                  @input="validateField('description', form)"
              />
              <InputError :message="errors.description" class="mt-2"/>
            </div>
          </div>
        </div>

        <Saving v-else/>
      </div>
    </div>
  </div>
</template>

<script setup>
import Input from "@/Components/Input";
import Label from "@/Components/Label.vue";
import InputError from "@/Components/InputError.vue";
import {computed, onMounted, ref} from "vue";
import Textarea from "@/Components/Textarea.vue";
import Multiselect from "@vueform/multiselect";
import { fetchRules, validateField, errors} from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
import {useForm} from "@inertiajs/vue3";
import {Inertia} from "@inertiajs/inertia";
import FormButtonAndHeaderSection from "@/Components/Widgets/FormButtonAndHeaderSection.vue";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
  types: {
    type: Object,
    required: true,
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
    Inertia.post(route("admin.leads.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.leads.store"), form, {
    onError: handleError,
    onSuccess: handleSuccess,
  });
}

onMounted(() => {
  fetchRules(route("admin.leads.rules"));
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>


