<template>
  <div class="app-wrapper">

    <Head :title="title"/>

    <SideMenu :auth-user="authUser"/>

    <div class="app-content overflow-x-auto">

      <TopBar
          :title="title"
          :auth-user="authUser"
      />

      <main class="p-6">
        <slot/>
      </main>
      <Footer/>
    </div>

    <Transition name="fade">
      <div v-if="showSearchModal" class="hs-overlay fixed top-0 start-0 w-full h-full z-[60] overflow-x-hidden overflow-y-auto">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
          <div class="flex flex-col bg-white shadow-sm rounded-xl pointer-events-auto overflow-hidden">
            <div class="relative z-[60]">
              <input type="search" id="search-input" class="form-input ps-10"/>
              <span class="absolute start-3 top-1.5">
                <i class="uil uil-search text-lg"></i>
              </span>
              <span class="absolute end-3 top-1.5">
                <button @click="showSearchModal = false">
                  <i class="uil uil-times text-lg"></i>
                </button>
              </span>
            </div>
          </div>
        </div>
      </div>
    </Transition>

    <Notification />

    <ProgressSpinner />

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted  } from 'vue';
import {Head, usePage} from '@inertiajs/inertia-vue3';
import SideMenu from '@/Components/Menus/SideMenu.vue';
import TopBar from '@/Components/Menus/TopBar.vue';
import Footer from '@/Components/Menus/Footer.vue';
import Notification from '@/Components/Notification.vue';
import debounce from 'lodash/debounce';
import ProgressSpinner from "@/Components/Widgets/ProgressSpinner.vue";

const showSearchModal = ref(false);
const previousWidth = ref(window.innerWidth);

const props = defineProps({
  title: {
    type: String,
    default: 'Dashboard',
  },
});

const page = usePage().props.value;
const authUser = page.authUser;

const detectDevice = () => {
  const userConfirmed = window.confirm("We've detected your screen size changes. Are you sure you want to reload the page?");
  if (userConfirmed) {
    location.reload();
  }
};

const debouncedDetectDevice = debounce(() => {
  const currentWidth = window.innerWidth;

  if (currentWidth !== previousWidth.value) {
    detectDevice();
    previousWidth.value = currentWidth;
  }
}, 200);

// onMounted(() => {
//   window.addEventListener('resize', debouncedDetectDevice);
// });
//
// onUnmounted(() => {
//   window.removeEventListener('resize', debouncedDetectDevice);
// });

</script>
