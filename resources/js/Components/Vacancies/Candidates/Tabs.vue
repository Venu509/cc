<script setup>
import {onMounted, ref, watch} from 'vue';

const emit = defineEmits(['change-tab']);
const tabs = ['pending', 'shortlisted', 'rejected'];
const currentTab = ref('recent');

function changeTab(recentTab, tab) {
  emit('change-tab', tab);
  currentTab.value = tab;
}

function getTabFromUrl() {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('tab') || 'pending';
}

watch(currentTab, (newValue) => {
  window.history.replaceState({}, '', `${window.location.pathname}?tab=${newValue}`);
});

onMounted(() => {
  currentTab.value = getTabFromUrl();
});
</script>

<template>
  <div class="sm:flex justify-center items-center mb-6">
    <div class="rounded-full flex space-x-3">
      <button
          v-for="(tab, index) in tabs"
          :key="index"
          :class="[
        'px-4 py-2 rounded-full capitalize font-bold',
        currentTab === tab ? 'bg-blue-600 text-white' : 'text-gray-500 bg-white'
      ]"
          @click="changeTab(currentTab, tab)"
      >
        {{ tab }}
      </button>
    </div>
  </div>
</template>
