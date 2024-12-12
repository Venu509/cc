<script setup>
import {onMounted, ref} from "vue";
import mammoth from "mammoth";

const fileUrl = ref(null);
const docxContent = ref(null);
const isPDF = ref(false);

const props = defineProps({
  url: {
    type: Object,
    required: true
  }
})

const resumeUrl = props.url;

onMounted(async () => {
  if (resumeUrl) {
    const fileExtension = resumeUrl.split('.').pop().toLowerCase();
    if (fileExtension === 'pdf') {
      isPDF.value = true;
      fileUrl.value = resumeUrl;
    } else if (fileExtension === 'docx') {
      isPDF.value = false;
      try {
        const response = await fetch(resumeUrl);
        const arrayBuffer = await response.arrayBuffer();
        const result = await mammoth.convertToHtml({arrayBuffer});
        docxContent.value = result.value;
      } catch (error) {
        console.error("Error fetching or converting DOCX:", error);
      }
    }
  }
});
</script>

<template>
  <div class="items-center" v-if="fileUrl">
    <div v-if="isPDF">
      <iframe :src="fileUrl" class="w-full" style="height: 800px;" frameborder="0"></iframe>
    </div>
    <div v-else>
      <div v-html="docxContent" class="prose max-w-none overflow-y-auto" style="height: 800px;"></div>
    </div>
  </div>
  <div v-else>
    <p>No CV attached.</p>
  </div>
</template>

<style scoped>

</style>