<template>
  <div v-if="loading" class="spinner-overlay">
    <div class="spinner-content">
      <ProgressSpinner
          style="width: 50px; height: 50px"
          strokeWidth="8"
          class="fill-surface-0 dark:fill-surface-800"
          animationDuration=".5s"
          aria-label="Custom ProgressSpinner"
      />
      <p class="px-3 py-1 text-lg font-medium leading-none text-center text-secondary-800 bg-secondary-200 rounded-full animate-pulse dark:bg-secondary-900 dark:text-secondary-200 loading-text">Loading ...</p>
    </div>
  </div>
</template>

<script setup>
import {ref, onMounted, onUnmounted, computed} from 'vue';

const loading = ref(false);

const restrictedRoutes = computed(() => {
  return route().current("marketing.login") || route().current('admin.login') || route().current('login') || route().current('register');
})

const startLoading = () => {
  loading.value = true;
  if (!restrictedRoutes) {
    document.body.classList.add('loading');
  }

};

const stopLoading = () => {
  loading.value = false;
  document.body.classList.remove('loading');
};

onMounted(() => {
  window.addEventListener('start', startLoading);
  window.addEventListener('finish', stopLoading);
});

onUnmounted(() => {
  window.removeEventListener('start', startLoading);
  window.removeEventListener('finish', stopLoading);
});
</script>

<style scoped>
.spinner-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100vh;
  background: rgba(255, 255, 255, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.spinner-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}
</style>
