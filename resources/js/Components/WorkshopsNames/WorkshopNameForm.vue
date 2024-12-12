<template>
  <div class="flex flex-col gap-6">
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
    </div>
  </div>

</template>

<script setup>
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import {fetchRules, validateField, errors} from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";


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
    Inertia.post(route("admin.workshops-names.update", props.modelValue.id), form, {
      onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.workshops-names.store"), form, {
      onError: handleError,
    onSuccess: handleSuccess,
  });
}

function back() {
  window.history.back();
  Inertia.reload()
}

onMounted(() => {
    fetchRules(route("admin.workshops-names.rules"));
});
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
