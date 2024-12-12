<template>
    <textarea
        ref="input"
        :value="modelValue"
        class="appearance-none block w-full bg-white text-gray-500 py-3 px-4 leading-tight border-gray-300 focus:border-sky-500 focus:ring focus:ring-sky-500 focus:ring-opacity-50 rounded-xl shadow-sm"
        cols="50"
        :rows="rows"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <slot/>
    </textarea>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        modelValue: {
            type: [String, Number, Object],
            default: null,
        },
        rows: {
            type: [Number],
            default: 4,
        },
    },

    emits: ["update:modelValue"],
});
</script>
