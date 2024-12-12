<script setup>
import { ref, watch, defineEmits } from "vue";
import Editor from "@tinymce/tinymce-vue";
import {validateField} from "@/Components/Services/Validation";

const props = defineProps({
  content: {
    type: [String, Number, Object],
    default: null,
  },
  form: {
    type: [Object],
    required: true,
  },
  validateField: {
    type: [Function],
    required: true,
  },
  column: {
    type: [String],
    default: 'description',
  },
  height: {
    type: [String],
    default: '600px',
  },
});

const content = ref(props.content);
const emit = defineEmits(["update:content"]);

const editorConfig = {
  plugins: [
    "code",
    "table",
    "image",
    "fullscreen",
    "spellchecker",
    "imagetools",
    "codesample",
    "charmap",
    "print",
    "save",
    "template",
    "paste",
    "insertdatetime",
    "searchreplace",
    "pagebreak",
    "wordcount",
    "visualblocks",
    "lists",
  ],
  menu: {
    file: {
      title: "File",
      items: "newdocument restoredraft | preview | print ",
    },
    edit: {
      title: "Edit",
      items: "undo redo | cut copy paste | selectall | searchreplace",
    },
    view: {
      title: "View",
      items: "code | visualaid visualchars visualblocks | spellchecker | preview fullscreen | searchreplace | visualblocks",
    },
    insert: {
      title: "Insert",
      items: "image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime",
    },
    format: {
      title: "Format",
      items: "bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat",
    },
    tools: {
      title: "Tools",
      items: "spellchecker spellcheckerlanguage | code wordcount",
    },
    table: {
      title: "Table",
      items: "inserttable | cell row column | tableprops deletetable",
    },
    help: {
      title: "Help",
      items: "help",
    },
  },
  mobile: {
    toolbar: [
      "undo redo | bold italic | link image | bullist numlist",
    ],
    menubar: false,  // Hide menu for mobile to save space
    plugins: [
      "autosave", "lists", "autolink", "code", "fullscreen",
    ],
  },
  theme: "silver",
  toolbar_mode: "floating",
  file_picker_types: "file image media",
  toolbar: [
    "undo redo | styleselect | fontselect | fontsizeselect | forecolor backcolor | table | code | link image | fullscreen | outdent | indent ",
    "bold italic underline | lineheight | alignleft aligncenter alignright alignjustify bullist numlist | superscript subscript | insertdatetime | pagebreak | print | wordcount | charmap | save | copy paste ",
  ],
  setup(editor) {
    // Capture paste event
    editor.on('paste', () => {
      // After pasting content, update the content ref
      content.value = editor.getContent();
    });

    // Capture other input changes
    editor.on('change input', () => {
      content.value = editor.getContent();
    });
  },
};

watch(content, (newContent) => {
  emit("update:content", newContent);
});
</script>

<template>
  <Editor
      v-model="content"
      api-key="quwe5bb71xi23o4kr0la4vt6rrsyyb17pgah8i56w71f3r3c"
      :init="editorConfig"
      content-type="html"
      theme="snow"
      @input="validateField(column, form)"
      :style="{ height: height }"
  />
</template>

<style scoped>
</style>
