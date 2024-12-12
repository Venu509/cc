<template>
  <AppLayout
      title="Bulk Upload"
  >
    <div class="w-full mx-auto p-6 bg-white rounded-lg shadow-lg">
      <div class="flex flex-col sm:flex-row justify-between items-center mb-6 space-y-4 sm:space-y-0">
        <div class="flex items-center space-x-2">
          <span class="text-lg font-medium text-gray-900">Import Bulk data</span>
        </div>

        <div v-if="form.fileUploadType === 'data' " @click="download()" class="text-blue-600 flex items-center cursor-pointer">
          <div class="relative mr-2">
            <CloudDownloadIcon
                data-tooltip-target="tooltip-default"
                class="cursor-pointer h-6 w-6 text-amber-700"
            />
            <div id="tooltip-default" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
              Download Sample Template
              <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
          </div>
          Download sample template
        </div>
      </div>


      <div class="flex flex-col items-center space-y-4" v-if="!isSaving">

        <div class="bg-blue-100 rounded-full p-3">
          <UploadIcon class="w-6 h-6 text-blue-600" />
        </div>

        <div class="grid lg:grid-cols-2 gap-3 w-full max-w-lg">
          <div class="col-span-2">
            <Label :required="true">Type</Label>
            <RadioGroupInput
                v-model="form.fileUploadType"
                :items="fileUploadTypes"
                @change="validateField('fileUploadType', form)"
            />
            <InputError :message="errors.fileUploadType" class="mt-2"/>
          </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-1 w-full max-w-lg">
          <div class="col-span-2">
            <span class="text-red-600">Please Note: </span>
            <span class="text-gray-900" v-if="form.fileUploadType === 'data'">Please download the sample template, fill in the data, and upload it.</span>
            <span class="text-gray-900" v-if="form.fileUploadType === 'assets'">Please rename all profile pictures using the respective student IDs, then zip the entire folder and upload it.</span>
          </div>
        </div>

        <div class="w-full max-w-lg">
          <label for="fileToUpload" class="block text-sm font-medium text-gray-700">Choose a file:</label>
          <FileInput
              type="file"
              class="mt-1 block w-full"
              name="file"
              required
              :accept="`${form.fileUploadType === 'data' ? '.xlsx, .xls, .xlsm, .csv' : '.zip'}`"
              @change="fileUpload"
          />
          <InputError :message="form.errors.file" class="mt-2"/>
        </div>

        <Button type="button"
                :disabled="isSaving"
                @click="upload()">
          {{ buttonText }}
        </Button>
      </div>

      <Saving v-else message="Uploading ..."/>
    </div>

  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import {Inertia} from "@inertiajs/inertia";
import FileInput from "@/Components/FileInput.vue";
import {useForm} from '@inertiajs/inertia-vue3';
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/Button.vue";
import {computed, ref} from "vue";
import Saving from "@/Components/Widgets/Saving.vue";
import {
  CloudDownloadIcon,
  UploadIcon,
} from "@heroicons/vue/solid";
import {errors, validateField} from "@/Components/Services/Validation";
import Label from "@/Components/Label.vue";
import RadioGroupInput from "@/Components/RadioGroupInput.vue";

let props = defineProps({});

const form = useForm({
  file: null,
  fileUploadType: 'data',
});

const fileUploadTypes = ref([
  {name: 'fileUploadTypes', value: 'data', label: 'Student Data', checked: true},
  {name: 'fileUploadTypes', value: 'assets', label: 'Student Images (Zip File)', checked: false},
])

function fileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.file = file;
  }
}

const isSaving = ref(false);

function upload() {
  isSaving.value = true;
  Inertia.post(route("admin.students.bulk"), form, {
    onError: (errors) => {
      form.clearErrors().setError(errors);
      isSaving.value = false;
    },
    onSuccess: () => {
      form.reset();
      isSaving.value = false;
    },
  });
}

const buttonText = computed(() => {
  return isSaving.value ? 'Uploading...' : 'Upload';
});

function download() {
    window.location.href = route('admin.students.download');
}

</script>
<style>

</style>
