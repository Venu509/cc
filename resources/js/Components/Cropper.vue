<script setup>
import { Cropper } from 'vue-advanced-cropper'
import 'vue-advanced-cropper/dist/style.css';
import {ref} from "vue";

const props = defineProps({
  imageSrc: String,
  width: {
    type: Number,
    default: 1, // Default aspect ratio width part
  },
  height: {
    type: Number,
    default: 1, // Default aspect ratio height part
  },
});

const emits = defineEmits(['update:image']);

const cropper = ref(null);

function onCrop(event) {
  // Crop event handling
}

function cropImage() {
  const result = cropper.value.getResult();
  if (result) {
    emits('update:image', result.canvas.toDataURL());
  }
}
</script>

<template>
  <Cropper
      ref="cropper"
      :src="imageSrc"
      :stencilProps="{ aspectRatio: width / height }"
      @change="onCrop"
  />
</template>