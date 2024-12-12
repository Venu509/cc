<template>
  <li class="menu-item">
    <a @click.prevent="toggleMenu" :class="[
            isActiveItem(item) ?
            'text-secondary-600 bg-white' :
            'text-white',
            'gap-2.5 p-2 px-4 relative flex items-center whitespace-nowrap transition-all ease-in-out rounded-full duration-150 hover:bg-primary-900 hover:text-white focus:bg-secondary-400'
          ]">
      <component
          :is="item.icon"
          :class="[
                    isActiveItem(item)
                        ? 'text-secondary-600'
                        : 'text-white',
                    'menu-icon h-6 w-6',
        ]"
          aria-hidden="true"
      />
      <span :class="[
                    isActiveItem(item)
                        ? ''
                        : 'group-hover:text-gray-500',
                    'menu-icon h-6 w-6',
              ]">
        {{ item.name }}
      </span>
      <ChevronRightIcon class="h-5 w-5 menu-arrow ml-auto" :class="{ 'rotate-90': isExpanded }"/>
    </a>

    <transition name="expand-fade">
      <ul
          v-show="isExpanded"
          :id="`sidenavErrorPages-${item.id}`"
          class="sub-menu ml-6"
      >
        <li
            class="menu-item"
            v-for="child in item.children"
            v-show="child.access"
            :key="child.id"
            :aria-current="child.current ? 'page' : undefined"
            @click="handleMenuItemClick(child)"
        >
          <a :class="[
            isActiveItem(child) ?
            'bg-primary-900' :
            '',
            'text-white max-w-48 gap-2.5 p-2 px-4 relative flex items-center whitespace-nowrap transition-all ease-in-out rounded-full duration-150 hover:bg-secondary-500 focus:bg-secondary-400'
          ]">

            <component
                :is="child.icon"
                class="menu-icon h-4 w-4"
                aria-hidden="true"
            />
            <span
                :class="[
                    isActiveItem(child)
                        ? ''
                        : 'group-hover:text-gray-500',
                    'menu-icon h-6 w-6',
              ]">
              {{ child.name }}
            </span>
          </a>
        </li>
      </ul>
    </transition>
  </li>
</template>

<script setup>
import { computed, defineEmits, defineProps, ref } from 'vue';
import { usePage } from '@inertiajs/inertia-vue3';
import { ChevronDoubleRightIcon, ChevronRightIcon } from '@heroicons/vue/solid';

const props = defineProps({
  activeCondition: {
    type: Boolean,
    default: false,
  },
  hasActiveChild: {
    type: Boolean,
    default: false,
  },
  item: {
    type: Object,
    default: () => ({}),
  },
});

const emit = defineEmits(['toggle-expanded']);

const expandedMenuId = ref(null);

const isExpanded = computed(() => {
  return expandedMenuId.value === props.item.id || props.activeCondition || props.hasActiveChild;
});

function isActiveItem(item) {
  return item.isActive
}

function toggleMenu() {
  if (isExpanded.value) {
    expandedMenuId.value = null;
  } else {
    expandedMenuId.value = props.item.id;
  }
}

function handleMenuItemClick(item) {
  if (typeof item.callback === 'function') {
    item.callback(item.component);
  }
  emit('toggle-expanded', item);
}
</script>

<style scoped>
.menu-arrow {
  transition: transform 0.3s ease;
}

.rotate-90 {
  transform: rotate(90deg);
}

.sub-menu {
  overflow: hidden;
  transition: max-height 0.3s ease;
}

.sub-menu-hidden {
  max-height: 0;
}

.sub-menu-show {
  max-height: 500px;
}

.expand-fade-enter-active,
.expand-fade-leave-active {
  transition: max-height 0.3s ease, opacity 0.3s ease;
}

.expand-fade-enter, .expand-fade-leave-to {
  max-height: 0;
  opacity: 0;
}
</style>
