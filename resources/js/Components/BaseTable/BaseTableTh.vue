<template>
  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider z-1">
      <span class="flex items-center">
        <slot></slot>
<!--          <svg class="w-5 h-5 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24"-->
<!--               fill="none" viewBox="0 0 24 24">-->
<!--            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"-->
<!--                  d="m8 15 4 4 4-4m0-6-4-4-4 4"/>-->
<!--          </svg>-->
      </span>
  </th>
</template>

<script>
export default {
  name: "BaseTableTh",
};
</script>
