<template>
  <form ref="connectionForm">
    <div class="mt-5 md:mt-0 md:col-span-2">
      <div class="px-4 py-5 bg-white sm:p-6">
        <div class="grid grid-cols-6 gap-3">
          <div class="col-span-4">
            <Label :required="true">Title</Label>
            <Input
                id="title"
                v-model="form.title"
                autocomplete="title"
                class="mt-1 block w-full"
                name="title"
                type="text"
            />
            <InputError :message="form.errors.title" class="mt-2" />
          </div>

          <div class="col-span-2">
            <Label :required="true">Image</Label>
            <input
                type="file"
                class="mt-1 block w-full"
                name="image"
                @change="handleImageUpload"
            />
            <InputError :message="form.errors.image" class="mt-2" />
          </div>

          <div class="col-span-2">
            <Label>Preview</Label>
            <img
                v-if="form.imagePreview"
                :src="form.imagePreview"
                class="w-12"
                style="background-color: black;"
            />
          </div>
        </div>
      </div>
    </div>
  </form>
</template>

<script setup>
import Input from "@/Components/Input";
import Label from "@/Components/Label.vue";
import InputError from "@/Components/InputError.vue";
import { ref } from "vue";
import Textarea from "@/Components/Textarea.vue";
import Multiselect from "@vueform/multiselect";

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
  isEditing: {
    type: Boolean,
    required: true,
  },
});

const form = ref(props.modelValue);

function handleImageUpload(event) {
  const file = event.target.files[0];
  if (file) {
    form.value.image = file;

    const reader = new FileReader();
    reader.onload = (e) => {
      form.value.imagePreview = e.target.result;
    };
    reader.readAsDataURL(file);
  }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>


