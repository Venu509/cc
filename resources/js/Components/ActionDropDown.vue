<template>
  <div class="menu-container">
    <Menu v-if="type === 'dropdowns'" as="div" class="inline-block text-left">
      <div>
        <MenuButton
            class="inline-flex w-full justify-center text-sm font-medium text-gray hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-black focus-visible:ring-opacity-75"
        >
          <DotsVerticalIcon class="h-6 w-6"/>
        </MenuButton>
      </div>

      <transition
          enter-active-class="transition duration-100 ease-out"
          enter-from-class="transform scale-95 opacity-0"
          enter-to-class="transform scale-100 opacity-100"
          leave-active-class="transition duration-75 ease-in"
          leave-from-class="transform scale-100 opacity-100"
          leave-to-class="transform scale-95 opacity-0"
      >
        <div class="relative z-20">
          <MenuItems
              class="absolute right-5 md:right-4 lg:right-7 md:bottom-4 lg:bottom-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
          >
            <div class="px-1 py-1">
              <MenuItem
                  v-for="(action, index) in actions"
                  v-show="action.active"
                  :key="index"
                  v-slot="{ active }"
              >
                <button
                    :class="[
                            active ? 'bg-secondary-700 text-white ' : 'text-gray-900',
                            'group flex w-full items-center rounded-md px-2 py-2 text-sm z-20',
                        ]"
                    @click="action.callback()"
                >
                  <component
                      :is="action.icon"
                      :class="[
                                active ? 'text-white' : 'text-gray-400',
                                'h-5 w-5 mr-1 z-20',
                            ]"
                      aria-hidden="true"
                  />
                  {{ action.name }}
                </button>
              </MenuItem>
            </div>
          </MenuItems>
        </div>
      </transition>

    </Menu>

    <div v-else  class="flex space-x-2">
      <div
          v-for="(action, index) in actions"
          v-show="action.active"
          :key="index"
      >
        <button
            class="w-full items-center rounded-md px-1.5 py-1.5 text-sm z-20 bg-gray-200"
            @click="action.callback()"
        >
          <component
              :is="action.icon"
              class="text-gray-600 h-5 w-5 mr-1 z-20"
              aria-hidden="true"
          />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {Menu, MenuButton, MenuItem, MenuItems} from "@headlessui/vue";
import {DotsVerticalIcon} from "@heroicons/vue/solid";

let props = defineProps({
  actions: {
    type: Array,
    required: true,
  },
  type: {
    type: String,
    default: 'buttons'
  }
});

</script>
<style scoped>
.menu-container {
  position: relative;
  z-index: 50;
}
</style>