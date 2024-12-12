<template>
  <form ref="connectionForm">
    <div class="mt-5 md:mt-0 md:col-span-2">
      <div class="px-4 py-5 bg-white sm:p-6">
        <div class="grid grid-cols-6 gap-3">
          <div class="col-span-6">
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

let props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  },
});

const form = ref(props.modelValue);
</script>

