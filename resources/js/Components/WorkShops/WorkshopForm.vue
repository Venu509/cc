<template>
  <div class="flex flex-col gap-6">
    <FormButtonAndHeaderSection title="Add Workshop" :is-saving="isSaving" :button-text="buttonText" @save="save"/>

    <div class="card">
      <div class="p-6">
        <div v-if="!isSaving">
          <div class="grid lg:grid-cols-2 gap-3">
          <div >
              <Label :required="true">Workshop Name</Label>
              <Multiselect
                  v-model="form.name"
                  class="mt-1 p-0"
                  mode="single"
                  placeholder="Search or add a name"
                  tag-placeholder="Add this as new name"
                  :multiple="false"
                  :taggable="true"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :create-option="true"
                  :preselect-first="true"
                  :options="workshopsNames"
                  :loading="false"
                  :object="true"
                  @select="validateField('name', form)"
              />
              <InputError :message="errors.name" class="mt-2"/>
          </div>
          <div>
            <Label :required="true">Branch</Label>
              <Multiselect
                  v-model="form.branch"
                  mode="single"
                  class="mt-1"
                  :hide-selected="true"
                  :close-on-select="true"
                  :searchable="true"
                  :options="branches"
                  :loading="false"
                  :object="true"
                  @select="validateField('branch', form)"
              />
            <InputError :message="errors.branch" class="mt-2"/>
          </div>
            <div>
                <div>
                    <Label :required="true">Semester</Label>
                    <Select
                        v-model="form.semester"
                        @change="validateField('semester', form)"
                    >
                        <option value="1"> 1</option>
                        <option value="2"> 2</option>
                        <option value="3"> 3</option>
                        <option value="4"> 4</option>
                        <option value="5"> 5</option>
                        <option value="6"> 6</option>
                        <option value="7"> 7</option>
                        <option value="8"> 8</option>
                    </Select>
                    <InputError :message="errors.semester" class="mt-2"/>
                </div>
            </div>
            <div>
                <Label :required="true">Start Date</Label>
                <Calendar
                    id="startDate"
                    :model="form.startDate"
                    autocomplete="off"
                    name="startDate"
                    :max-date="form.endDate ? form.endDate : 9999-12-31"
                    :field-name="'startDate'"
                    @update:model="form.startDate = $event"
                    @input="validateField('startDate', form)"
                />
                <InputError :message="errors.startDate" class="mt-2"/>
            </div>
            <div>
                <Label :required="true">End Date</Label>
                <Calendar
                    id="endDate"
                    :model="form.endDate"
                    autocomplete="off"
                    name="endDate"
                    :min-date="form.startDate"
                    :field-name="'endDate'"
                    @update:model="form.endDate = $event"
                    @input="validateField('endDate', form)"
                />
                <InputError :message="errors.endDate" class="mt-2"/>
            </div>
              <div>
                  <Label :required="false">Description</Label>
                  <Textarea
                      id="description"
                      v-model="form.description"
                      autocomplete="description"
                      name="description"
                      @input="validateField('description', form)"
                  >
                    </Textarea>
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
import Label from "@/Components/Label.vue";
import Input from "@/Components/Input.vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";
import {useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import FileInput from "@/Components/FileInput.vue";
import Toggle from "@/Components/Toggle.vue";
import Select from "@/Components/Select.vue";
import Textarea from "@/Components/Textarea.vue";
import {fetchRules, validateField, errors, minDate, maxDate} from '@/Components/Services/Validation';
import Saving from "@/Components/Widgets/Saving.vue";
import Multiselect from "@vueform/multiselect";
import FormButtonAndHeaderSection from "@/Components/Widgets/FormButtonAndHeaderSection.vue";
import Calendar from "@/Components/Widgets/Calendar.vue";


let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    default: false,
  },
  branches: {
    type: Object,
    required: true,
  },
    workshopsNames: {
    type: Object,
    required: true,
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
    Inertia.post(route("admin.workshops.update", props.modelValue.id), form, {
        onError: handleError,
      onSuccess: handleSuccess,
    });
    return;
  }
  Inertia.post(route("admin.workshops.store"), form, {
      onError: handleError,
    onSuccess: handleSuccess,
  });
}

function back() {
  window.history.back();
  Inertia.reload()
}

onMounted(() => {
    fetchRules(route("admin.workshops.rules"));
});

</script>

<style src="@vueform/multiselect/themes/default.css"></style>
