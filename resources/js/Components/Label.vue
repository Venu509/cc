<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: {
        value: {
            type: String,
            default: null,
        },
        required: {
            type: Boolean,
            default: false,
        },
        note: {
            type: String,
            default: null,
        },
        noteTextColor: {
          type: String,
          default: 'text-red-600',
        },
    },
});
</script>

<template>
    <label class="text-gray-800 text-sm font-medium inline-block mt-2 mb-1">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
        <span v-if="required" class="text-red-600 font-bold"> * </span>
        <small v-if="note" class="ml-2">
            <span :class="noteTextColor">Please note: </span>
            <span class="text-secondary-500 font-xs">{{ note }}</span>
        </small>
    </label>
</template>
