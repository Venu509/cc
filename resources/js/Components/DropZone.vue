<template>
  <div class="dropzone" @dragover.prevent @drop.prevent="handleDrop" @click="triggerFileInput">
    Drag & drop files here or click to upload
    <input
        ref="fileInput"
        type="file"
        multiple
        accept="image/*"
        style="display: none"
        @change="handleFiles"
    />
  </div>
  <div
      v-for="(file, index) in internalFiles"
      :key="file.id || index"
      class="file-preview"
  >
    <template v-if="file.type === 'poster'">
      <img :src="file.preview" alt="Preview"/>
    </template>
    <template v-else>
      <video
          controls
          autoplay
          muted
          loop
          :src="file.preview"
          style="max-width: 100px; max-height: 100px;"
      ></video>
    </template>

    <button @click="removeFile(index)">Remove</button>
  </div>
</template>

<script setup>
import {defineEmits, defineProps, onMounted, ref, watch} from "vue";

const props = defineProps({
  modelValue: {
    type: Array,
    default: () => [],
  },
});

const emits = defineEmits(["update:modelValue"]);
const fileInput = ref(null);
const internalFiles = ref([]);

onMounted(() => {
  internalFiles.value = props.modelValue.map(file => ({
    ...file,
    preview: file.filePreview || file.preview,
  }));
});

const triggerFileInput = () => {
  fileInput.value.click();
};

const handleDrop = (event) => {
  event.preventDefault();
  const fileList = event.dataTransfer.files;
  addFiles(fileList);
};

const handleFiles = () => {
  const fileList = fileInput.value.files;
  addFiles(fileList);
};

const addFiles = (fileList) => {
  for (const file of fileList) {
    if (file.type.startsWith("image/")) {
      const reader = new FileReader();
      reader.onload = (e) => {
        internalFiles.value.push({
          image: file,
          type: 'poster',
          preview: e.target.result,
          isExisting: false,
        });
      };
      reader.readAsDataURL(file);
    } else if (file.type.startsWith("video/")) {
      const videoUrl = URL.createObjectURL(file);
      internalFiles.value.push({
        type: 'video',
        preview: videoUrl,
        isExisting: false,
      });
    }
  }
};


const removeFile = (index) => {
  internalFiles.value.splice(index, 1);
  emits("update:modelValue", internalFiles.value);
};

watch(internalFiles, (newFiles) => {
  emits("update:modelValue", newFiles);
}, {deep: true});
</script>

<style scoped>
.dropzone {
  border: 2px dashed #ccc;
  padding: 20px;
  text-align: center;
  align-content: center;
  cursor: pointer;
  user-select: none;
}

.file-preview {
  display: inline-block;
  margin: 10px;
  align-items: center;
  text-align: center;
}

.file-preview img {
  max-width: 100px;
  max-height: 50px;
  display: block;
  margin: 0 auto;
}

.file-preview button {
  background: red;
  color: #ffffff;
  padding: 5px 10px;
  border-radius: 8px;
  cursor: pointer;
}
</style>
