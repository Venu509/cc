<script setup>
import {onMounted, ref} from "vue";
import mammoth from "mammoth";
import Button from "@/Components/Button.vue";
import {CloudDownloadIcon} from '@heroicons/vue/solid';

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
  <div class="bg-secondary-50 p-6 rounded-lg shadow-lg mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Resume</h2>

    <div class="flex justify-between items-center" v-if="fileUrl">
      <div class="flex space-x-4">
        <div>
          <div v-if="isPDF">
            <iframe :src="fileUrl" class="w-full" style="height: 500px;" frameborder="0"></iframe>
          </div>
          <div v-else>
            <div v-html="docxContent" class="prose max-w-none overflow-y-auto" style="height: 500px;"></div>
          </div>
        </div>

        <div>
          <p class="text-sm text-blue-600 cursor-pointer">
            <a :href="fileUrl" :download="fileUrl" target="_blank">
              Tap to view
            </a>
          </p>
        </div>
      </div>
    </div>

    <div v-else>
      <p>No CV attached.</p>
    </div>
  </div>
</template>

<style scoped>

</style>