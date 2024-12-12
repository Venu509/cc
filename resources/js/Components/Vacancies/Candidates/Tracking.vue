<script setup>
let props = defineProps({
  histories: {
    type: Object,
    required: true,
  },
});

const getHoverColorClass = (color) => {
  return `hover:bg-${color}-100 dark:hover:bg-${color}-600/10`;
};
</script>

<template>
  <div v-for="(history, index) in histories" :key="index">
    <div class="ps-2 my-2 first:mt-0">
      <h3 class="text-xs font-medium uppercase text-gray-500 dark:text-neutral-400">
        {{ index }}
      </h3>
    </div>

    <div v-for="(item, key) in history" :key="key">
      <div
          :class="`flex gap-x-3 relative group rounded-lg ${getHoverColorClass(item.color)}`"
      >
        <div class="w-16 text-end">
          <span class="text-xs text-gray-500 dark:text-neutral-400">{{ item.time }}</span>
        </div>

        <div class="relative last:after:hidden after:absolute after:top-0 after:bottom-0 after:start-3.5 after:w-px after:-translate-x-[0.5px] after:bg-gray-200 dark:after:bg-neutral-700 dark:group-hover:after:bg-neutral-600">
          <div class="relative z-10 size-7 flex justify-center items-center">
            <div
                :class="`size-2 rounded-full bg-white border-2 border-${item.color}-500 group-hover:border-gray-600 dark:bg-neutral-800 dark:group-hover:border-neutral-600`"
            ></div>
          </div>
        </div>

        <div class="grow p-2 pb-8">
          <h3 :class="`flex gap-x-1.5 font-semibold text-${item.color}-600`">
            {{ item.remarks }}
          </h3>

          <button
              type="button"
              class="mt-1 -ms-1 p-1 relative z-10 inline-flex items-center gap-x-2 text-xs rounded-lg border border-transparent text-gray-500 hover:bg-white hover:shadow-sm disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:hover:bg-neutral-800"
          >
            <img class="shrink-0 size-4 rounded-full" :src="item.accessedBy.avatar" alt="Avatar">
            {{ item.accessedBy.name }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.size-7 {
  width: 1.75rem;
  height: 1.75rem;
}

.size-4 {
  width: 1rem;
  height: 1rem;
}

.size-2 {
  width: 0.5rem;
  height: 0.5rem;
}
</style>
