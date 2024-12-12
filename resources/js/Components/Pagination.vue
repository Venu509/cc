<template>
  <div class="px-4 py-3 flex flex-col sm:flex-row items-center justify-between sm:px-6 text-xs font-semibold text-slate-500">
    <!-- Mobile View (Only Numbers and Arrows) -->
    <div class="flex-1 flex justify-between sm:hidden mb-2">
      <p class="text-sm text-gray-700">
        Showing
        <span class="font-medium">{{ items.from }}</span>
        to
        <span class="font-medium">{{ items.to }}</span>
        of
        <span class="font-medium">{{ items.total }}</span>
        results
      </p>
    </div>

    <!-- Web View Pagination (No Arrows) -->
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Showing
          <span class="font-medium">{{ items.from }}</span>
          to
          <span class="font-medium">{{ items.to }}</span>
          of
          <span class="font-medium">{{ items.total }}</span>
          results
        </p>
      </div>
      <div>
        <nav aria-label="Pagination" class="relative z-0 inline-flex rounded-md shadow-sm space-x-1">
          <!-- Web view doesn't show Previous Arrow -->
          <template v-for="(link, key) in pageLinks" :key="`link-${key}`">
            <button
                @click="handlePageChange(link.url)"
                :class="[
                  link.active
                    ? 'bg-secondary-600 text-white'
                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
                  'px-4 py-2 rounded-md border border-gray-300 text-sm font-medium',
                  link.url === null ? 'cursor-not-allowed' : 'cursor-pointer'
                ]"
                v-html="link.label"
                :disabled="link.url === null"
            />
          </template>
          <!-- Web view doesn't show Next Arrow -->
        </nav>
      </div>
    </div>

    <!-- Mobile View (Arrows with Numbers) -->
    <div class="sm:hidden">
      <nav aria-label="Pagination" class="relative z-0 inline-flex rounded-md shadow-sm space-x-1">
        <!-- Mobile view: Show previous arrow -->
        <button
            @click="handlePreviousPage"
            :disabled="!items.prev_page_url && !isInertia"
            :class="[
                !items.prev_page_url && !isInertia ? 'cursor-not-allowed' : 'cursor-pointer',
                'relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50'
            ]"
        >
          <ChevronLeftIcon aria-hidden="true" class="h-5 w-5" />
        </button>

        <template v-for="(link, key) in compactPageLinks" :key="`link-${key}`">
          <button
              @click="handlePageChange(link.url)"
              :class="[ link.active
                ? 'bg-secondary-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-gray-200',
              'px-4 py-2 rounded-md border border-gray-300 text-sm font-medium' ]"
              v-html="link.label"
              :disabled="link.url === null"
          />
        </template>

        <!-- Mobile view: Show next arrow -->
        <button
            @click="handleNextPage"
            :disabled="!items.next_page_url && !isInertia"
            :class="[
                !items.next_page_url && !isInertia ? 'cursor-not-allowed' : 'cursor-pointer',
                'relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50'
            ]"
        >
          <ChevronRightIcon aria-hidden="true" class="h-5 w-5" />
        </button>
      </nav>
    </div>
  </div>
</template>

<script setup>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import { computed, defineProps, defineEmits } from "vue";
import { Inertia } from '@inertiajs/inertia'; // Inertia usage

const props = defineProps({
  items: {
    type: Object,
    required: true,
  },
  isInertia: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(['page-change']);

const pageLinks = computed(() => {
  if (!props.items.links || !Array.isArray(props.items.links)) {
    // Handle the case where links are undefined or not an array (Axios case)
    return [];
  }

  // Ensure URLs are passed correctly for Inertia
  return props.items.links.map((link) => {
    return {
      label: link.label,
      active: link.active,
      url: link.url
    };
  });
});

const compactPageLinks = computed(() => {
  const totalPages = props.items.last_page || 1;
  const currentPage = props.items.current_page || 1;
  const maxVisiblePages = 5;

  let startPage = Math.max(currentPage - Math.floor(maxVisiblePages / 2), 1);
  let endPage = Math.min(startPage + maxVisiblePages - 1, totalPages);

  if (endPage - startPage < maxVisiblePages - 1) {
    startPage = Math.max(endPage - maxVisiblePages + 1, 1);
  }

  // Handle cases where links might not be available (Axios case)
  return Array.from({ length: endPage - startPage + 1 }, (_, i) => startPage + i)
      .map(page => ({
        page,
        label: page.toString(),
        active: page === currentPage,
        url: props.items.links?.[page - 1]?.url || null  // Safely access links
      }));
});

const handlePageChange = (url) => {
  if (url) {
    if (props.isInertia) {
      // Handle Inertia pagination with full URL
      Inertia.visit(url);
    } else {
      const pageNumber = getPageFromUrl(url);
      emit('page-change', pageNumber);
    }
  }
};

const handlePreviousPage = () => {
  if (props.isInertia) {
    if (props.items.prev_page_url) {
      Inertia.visit(props.items.prev_page_url);
    }
  } else {
    handlePageChange(props.items.prev_page_url);
  }
};

const handleNextPage = () => {
  if (props.isInertia) {
    if (props.items.next_page_url) {
      Inertia.visit(props.items.next_page_url);
    }
  } else {
    handlePageChange(props.items.next_page_url);
  }
};

const getPageFromUrl = (url) => {
  if (!url) return null;
  const match = url.match(/page=(\d+)/);
  return match ? parseInt(match[1], 10) : null;
};
</script>

<style scoped>
@media (max-width: 640px) {
  .pagination-nav button {
    padding: 0.75rem; /* Increase tap target size on mobile */
  }
}
</style>
