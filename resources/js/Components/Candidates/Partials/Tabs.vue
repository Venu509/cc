<template>
  <div class="overflow-x-auto whitespace-nowrap cursor-grab" ref="scrollContainer">
    <ul class="flex -mb-px text-sm font-medium text-center text-gray-500 dark:text-gray-400">
      <li
          class="relative "
          v-for="tab in tabs"
          :key="tab.value"
          :class="{ 'hidden': isDisabled(tab.value) }"
      >
        <a
            href="#"
            @click.prevent="!isDisabled(tab.value) && setActiveTab(tab.value)"
            :class="[
          activeTab === tab.value ? 'text-secondary-600 border-secondary-600 border-b-2' : '',
          'inline-flex items-center justify-center p-4 rounded-t-lg'
        ]"
            class="inline-flex items-center justify-center p-4 rounded-t-lg"
        >
          <component
              :is="iconMap[tab.icon]"
              :class="[
            activeTab === tab.value ? 'text-secondary-600 dark:text-secondary-500' : '',
            'w-6 h-6 me-2'
          ]"
          />
          {{ tab.label }}
        </a>

        <CheckCircleIcon v-if="tab.isCompleted"
                         class="absolute inline-flex items-center justify-center w-5 h-5 text-green-500 border-2 border-white rounded top-1 -end-2 dark:border-gray-900"/>
      </li>
    </ul>
  </div>
</template>

<script setup>
import {defineEmits, defineProps, nextTick, onMounted, onUnmounted, ref} from 'vue';
import {
  AcademicCapIcon,
  DotsHorizontalIcon,
  GlobeAltIcon,
  RssIcon,
  ShareIcon,
  TranslateIcon,
  TrendingUpIcon,
  UserCircleIcon,
  CheckCircleIcon,
  DocumentTextIcon,
  DocumentAddIcon,
} from "@heroicons/vue/solid";


const iconMap = {
  UserCircleIcon: UserCircleIcon,
  GlobeAltIcon: GlobeAltIcon,
  AcademicCapIcon: AcademicCapIcon,
  TrendingUpIcon: TrendingUpIcon,
  TranslateIcon: TranslateIcon,
  ShareIcon: ShareIcon,
  RssIcon: RssIcon,
  DotsHorizontalIcon: DotsHorizontalIcon,
  DocumentTextIcon: DocumentTextIcon,
  DocumentAddIcon: DocumentAddIcon,
};

const props = defineProps({
  tabs: {
    type: Array,
    required: true,
  },
  activeTab: {
    type: String,
    required: true,
  },
  isDisabled: {
    type: Function,
    required: true,
  },
});

const emit = defineEmits(['update:activeTab']);

function setActiveTab(tabValue) {
  emit('update:activeTab', tabValue);
}

const scrollContainer = ref(null);
let isDragging = false;
let startX, scrollLeft;

const handleMouseDown = (e) => {
  isDragging = true;
  startX = e.pageX - scrollContainer.value.offsetLeft;
  scrollLeft = scrollContainer.value.scrollLeft;
  scrollContainer.value.classList.add('cursor-grabbing');
};

const handleMouseUp = () => {
  isDragging = false;
  scrollContainer.value.classList.remove('cursor-grabbing');
};

const handleMouseMove = (e) => {
  if (!isDragging) return;
  const x = e.pageX - scrollContainer.value.offsetLeft;
  const walk = (x - startX) * 3;
  scrollContainer.value.scrollLeft = scrollLeft - walk;
};

const handleWheel = (e) => {
  e.preventDefault();
  scrollContainer.value.scrollLeft += e.deltaY * 0.5;
};

onMounted(async () => {
  await nextTick();
  if (scrollContainer.value) {
    scrollContainer.value.addEventListener('mousedown', handleMouseDown);
    scrollContainer.value.addEventListener('mouseup', handleMouseUp);
    scrollContainer.value.addEventListener('mousemove', handleMouseMove);
    scrollContainer.value.addEventListener('wheel', handleWheel);
  } else {
    console.error('scrollContainer is still null after nextTick');
  }
});

onUnmounted(() => {
  if (scrollContainer.value) {
    scrollContainer.value.removeEventListener('mousedown', handleMouseDown);
    scrollContainer.value.removeEventListener('mouseup', handleMouseUp);
    scrollContainer.value.removeEventListener('mousemove', handleMouseMove);
    scrollContainer.value.removeEventListener('wheel', handleWheel);
  }
});
</script>

<style scoped>
.scrollable-container {
  overflow-x: auto;
  white-space: nowrap;
  cursor: grab;
  scroll-behavior: smooth;
}

.scrollable-container:active {
  cursor: grabbing;
}

.scrollable-container::-webkit-scrollbar {
  display: none;
}

.scrollable-tabs {
  overflow-x: auto;
  white-space: nowrap;
  cursor: grab;
}

.scrollable-tabs:active {
  cursor: grabbing;
}

.cursor-grab {
  cursor: grab;
}

.cursor-grabbing {
  cursor: grabbing;
}
</style>