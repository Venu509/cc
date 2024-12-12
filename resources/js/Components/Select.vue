<template>
    <select
        id="primary-currency"
        class="custom-input block w-full px-3 py-2 bg-white border border-slate-300 text-sm shadow-sm placeholder-slate-400
            focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500 rounded-xl
            disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
            invalid:border-pink-500 invalid:text-pink-600
            focus:invalid:border-pink-500 focus:invalid:ring-pink-500 capitalize"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
    >
        <slot />
    </select>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        modelValue: {
            type: [String, Number, Object],
            default: null,
        },
    },

    emits: ["update:modelValue"],
});
</script>
