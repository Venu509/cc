<template>
  <li class="menu-item">
    <a :class="[
            isActiveItem(item) ?
            'text-secondary-600 bg-white' :
            'text-white',
            'gap-2.5 p-2 px-4 relative flex items-center whitespace-nowrap transition-all ease-in-out rounded-full duration-150 hover:bg-secondary-500 focus:bg-secondary-400'
          ]">
      <component
          :is="item.icon"
          :class="[
                    isActiveItem(item)
                        ? 'text-secondary-600'
                        : 'text-white group-hover:text-gray-500',
                    'menu-icon h-6 w-6',
                ]"
          aria-hidden="true"
      />
      <span :class="[
                    isActiveItem(item)
                        ? ''
                        : 'group-hover:text-gray-500',
                    'menu-icon h-6 w-6',
            ]"> {{ item.name }} </span>
    </a>
  </li>
</template>

<script setup>
import {usePage} from "@inertiajs/inertia-vue3";

let props = defineProps({
  item: {
    type: Object,
    default: true,
  },
});

function isActiveItem(item) {
  const currentPageComponent = usePage().component.value;

  if (!item || !item.component || !currentPageComponent) {
    return false;
  }

  return currentPageComponent.split("/")[0] === item.component.split("/")[0];
}
</script>

<style scoped>

</style>
