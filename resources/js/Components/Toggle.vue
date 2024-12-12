<template>
    <Switch
        v-model="enabled.isActive"
        :disabled="disableToggleOnActive"
        :class="[
            enabled.isActive ? 'bg-secondary-400' : 'bg-gray-200',
            disableToggleOnActive ? 'cursor-not-allowed' : 'cursor-pointer',
            'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent cur rounded-full transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-500',
        ]"
    >
        <span class="sr-only">Use setting</span>
        <span
            :class="[
                enabled.isActive ? 'translate-x-5' : 'translate-x-0',
                'pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
            ]"
        >
            <span
                :class="[
                    enabled.isActive
                        ? 'opacity-0 ease-out duration-100'
                        : 'opacity-100 ease-in duration-200',
                    'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity',
                ]"
                aria-hidden="true"
            >
                <svg
                    class="h-3 w-3 text-gray-400"
                    fill="none"
                    viewBox="0 0 12 12"
                >
                    <path
                        d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                    />
                </svg>
            </span>
            <span
                :class="[
                    enabled.isActive
                        ? 'opacity-100 ease-in duration-200'
                        : 'opacity-0 ease-out duration-100',
                    'absolute inset-0 h-full w-full flex items-center justify-center transition-opacity',
                ]"
                aria-hidden="true"
            >
                <svg
                    class="h-3 w-3 text-secondary-400"
                    fill="currentColor"
                    viewBox="0 0 12 12"
                >
                    <path
                        d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z"
                    />
                </svg>
            </span>
        </span>
    </Switch>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { Switch } from "@headlessui/vue";

let emit = defineEmits(["updateStatus"]);

const props = defineProps({
  data: {
    type: Object,
    required: true,
  },
  isDisabled: {
    type: Boolean,
    default: false,
  },
});

const enabled = ref({ ...props.data });

watch(
    () => enabled.value.isActive,
    (newValue) => {
      if (enabled.value.id) {
        emit("updateStatus", { ...enabled.value });
      }
    },
    { immediate: true }
);

const disableToggleOnActive = computed(() => {
  return props.data.isActive && props.isDisabled;
});
</script>

