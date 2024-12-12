<script setup>
import Vue3TagsInput from "vue3-tags-input";

defineProps(["modelValue", "placeholder"]);

let emit = defineEmits(["update:modelValue"]);

function handleChangeTag(event) {
    console.log(event);
    emit("update:modelValue", event);
}
</script>

<template>
    <Vue3TagsInput
        :tags="modelValue"
        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-xl shadow-sm px-2 py-1"
        :placeholder="placeholder"
        @on-tags-changed="handleChangeTag"
    />
</template>
