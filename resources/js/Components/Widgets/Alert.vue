<script setup>
import {
  XIcon
} from "@heroicons/vue/solid";
import { onMounted, computed } from 'vue'
import { initFlowbite } from 'flowbite'
import { Inertia } from "@inertiajs/inertia";

let props = defineProps({
  action: {
    type: Object,
    required: true,
  },
  profileCompletion: {
    type: Object,
    required: true,
  },
});

onMounted(() => {
  initFlowbite();
})

function redirect() {
  Inertia.visit(props.action.actionUrl);
}

const ALERT_THEME = {
  danger: {
    background: "bg-red-200",
    textIcon: "text-red-500",
    text: "focus:ring-2 focus:ring-red-400 text-red-800",
    hover: "hover:text-red-900 hover:font-bold",
  },
  warning: {
    background: "bg-yellow-200",
    textIcon: "text-yellow-500",
    text: "focus:ring-2 focus:ring-yellow-400 text-yellow-800",
    hover: "hover:text-yellow-900 hover:font-bold",
  },
  info: {
    background: "bg-indigo-200",
    textIcon: "text-indigo-500",
    text: "focus:ring-2 focus:ring-indigo-400 text-indigo-800",
    hover: "hover:text-indigo-900 hover:font-bold",
  },
  primary: {
    background: "bg-secondary-200",
    textIcon: "text-secondary-500",
    text: "focus:ring-2 focus:ring-secondary-400 text-secondary-800",
    hover: "hover:text-secondary-900 hover:font-bold",
  },
  secondary: {
    background: "bg-gray-200",
    textIcon: "text-gray-500",
    text: "focus:ring-2 focus:ring-gray-400 text-gray-800",
    hover: "hover:text-gray-900 hover:font-bold",
  },
};

const stylesForStatus = computed(() => ALERT_THEME[props.action.type]);

</script>

<template>
  <div id="alert-1"
       :class="['rounded-md p-4', stylesForStatus.background]"
       class="flex items-center p-4 mb-4 text-secondary-800 rounded-lg bg-secondary-50 dark:bg-gray-800 dark:text-secondary-400"
       role="alert">
    <component
        :is="action.icon"
        :class="['h-5 w-5', stylesForStatus.textIcon]"
        aria-hidden="true"
    />
    <span class="sr-only">Info</span>
    <div class="ms-3 text-sm font-medium">
      <p :class="['text-sm', stylesForStatus.text]">
        {{ action.body }}
      </p>
      <p v-if="action.hasAction" class="mt-3 text-sm">
        <a
            :class="[
                            'whitespace-nowrap cursor-pointer font-medium',
                            stylesForStatus.text,
                            stylesForStatus.hover,
                        ]"
            @click="redirect"
        >
          {{ action.actionText }}
          <span aria-hidden="true"> &rarr;</span>
        </a>
      </p>
    </div>

    <button type="button"
            :class="[stylesForStatus.background, stylesForStatus.text]"
            class="ms-auto -mx-1.5 -my-1.5 rounded-lg p-1.5 inline-flex items-center justify-center h-8 w-8"
            data-dismiss-target="#alert-1" aria-label="Close">
      <span class="sr-only">Close</span>
      <XIcon class="w-3 h-3"/>
    </button>
  </div>
</template>