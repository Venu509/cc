<template>
  <div class="app-menu bg-secondary-600 overflow-y-scroll overflow-x-hidden flex flex-col h-screen z-10" ref="sidebar">
    <a class="logo-box">
      <div class="flex flex-col h-screen transition-all duration-300 z-10 relative">
        <button
            @click="toggleSidebarMenu"
            class="fixed top-4 right-[9.7rem] sm:right-16 md:right-[46.5rem] lg:right-40 text-white focus:outline-none bg-red-700 hover:bg-red-200 z-[99999] hover:text-gray-900 rounded-full text-sm p-1 md:hidden"
            style="z-index: 99999;"
        >
        <span class="flex items-center justify-center h-6 w-6">
            <ChevronLeftIcon class="text-2xl" />
        </span>
        </button>
      </div>

      <img :src="logoWhite" class="logo-light h-28" alt="Light logo" />
      <img :src="logoWhite" class="logo-dark h-28" alt="Dark logo" />
    </a>

    <div class="flex justify-center">
      <h2 class="text-sm font-bold text-white capitalize">{{ authUser.name }}</h2>
      <h2 class="text-sm font-bold text-white ml-1">
        <BadgeCheckIcon class="h-4 w-4" />
      </h2>
    </div>

    <div class="flex justify-center">
      <h2 class="text-sm text-white capitalize">{{ role.name }}</h2>
    </div>

    <div data-simplebar class="flex-grow">
      <ul class="menu">
        <SideBarItem
            v-for="item in singleMenus"
            v-show="item.access"
            :key="item.name"
            :item="item"
            :aria-current="item.current ? 'page' : undefined"
            class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 cursor-pointer"
            @click="handleMenuItemClick(item)"
        />
        <SideBarGroupItem
            v-for="item in nestedMenus"
            v-show="item.access"
            :key="item.name"
            :item="item"
            :has-active-child="isActiveItem(item) || hasActiveChild(item)"
            :active-condition="hasActiveChild(item)"
            class="px-3 py-2 rounded-sm mb-0.5 last:mb-0 cursor-pointer"
        />
      </ul>
    </div>
  </div>
</template>

<script setup>
import {Inertia} from "@inertiajs/inertia";
import {usePage} from "@inertiajs/inertia-vue3";
import {
  HomeIcon,
  TruckIcon,
  UserGroupIcon,
  XCircleIcon,
  ClockIcon,
  ChartSquareBarIcon,
  FlagIcon,
  ViewListIcon,
  BadgeCheckIcon,
  ChartBarIcon,
  MenuAlt4Icon,
  OfficeBuildingIcon,
  DocumentSearchIcon,
  PlusIcon,
  SearchIcon,
  BookmarkIcon,
  UploadIcon,
  ChevronLeftIcon,
} from "@heroicons/vue/solid";

import {onMounted, onUnmounted, ref, watch} from "vue";
import SideBarItem from "@/Components/SideBarItem.vue";
import SideBarGroupItem from "@/Components/SideBarGroupItem.vue";

const props = defineProps({
  sidebarOpen: {
    type: Boolean,
    default: false,
  },
  authUser: {
    type: Object,
    required: true,
  },
});

defineEmits(["update:modelValue", "close-sidebar"]);
let page = usePage().props.value;
let logo = page.logo;
let logoWhite = page.logoWhite;
let role = page.role;
let menus = page.menus;

const trigger = ref(null);
const sidebar = ref(null);

const sidebarOpen = ref(false);  // Main sidebar state
const htmlElement = ref(null);
const view = ref('hidden');

const iconMap = {
  HomeIcon: HomeIcon,
  TruckIcon: TruckIcon,
  UserGroupIcon: UserGroupIcon,
  XCircleIcon: XCircleIcon,
  ClockIcon: ClockIcon,
  ChartSquareBarIcon: ChartSquareBarIcon,
  FlagIcon: FlagIcon,
  ViewListIcon: ViewListIcon,
  ChartBarIcon: ChartBarIcon,
  MenuAlt4Icon: MenuAlt4Icon,
  OfficeBuildingIcon: OfficeBuildingIcon,
  DocumentSearchIcon: DocumentSearchIcon,
  PlusIcon: PlusIcon,
  SearchIcon: SearchIcon,
  BookmarkIcon: BookmarkIcon,
  UploadIcon: UploadIcon,
};

function processMenu(menu, index = 0) {
  const menuItem = {
    name: menu.name,
    access: menu.access,
    hasChildren: menu.hasChildren,
    isActive: menu.isActive,
    id: index,
  };

  if (menu.params) {
    menuItem.params = menu.params;
  }

  if (menu.icon) {
    menuItem.icon = iconMap[menu.icon];
  }

  if (menu.route && !menu.hasChildren) {
    menuItem.route = menu.route;
    menuItem.callback = (component) => {
      inertiaVisit(menu.route, component, menuItem.params);
    };
  }

  if (menu.component) {
    menuItem.component = menu.component;
  }

  if (menu.children && menu.children.length > 0) {
    menuItem.children = menu.children.map((childMenu, childIndex) =>
        processMenu(childMenu, childIndex + 1)
    );
  }

  return menuItem;
}

const navigation = menus.map((menu, index) => processMenu(menu, index));
const singleMenus = navigation.filter((menu) => !menu.hasChildren);
const nestedMenus = navigation.filter((menu) => menu.hasChildren);

function handleMenuItemClick(item) {
  if (typeof item.callback === "function") {
    item.callback(item.component);
  }
}

function inertiaVisit(path, component, params) {
  Inertia.visit(route(path, params));
  isActiveItem(component);
}

function hasActiveChild(item) {
  return item.children.some((child) => isActiveItem(child));
}

function isActiveItem(item) {
  return item.isActive
}

const storedSidebarExpanded = localStorage.getItem("sidebar-expanded");
const sidebarExpanded = ref(
    storedSidebarExpanded === null ? false : storedSidebarExpanded === "true"
);

// Single click handler to close sidebar if clicked outside
const clickHandler = ({ target }) => {
  if (sidebarOpen.value && sidebar.value && !sidebar.value.contains(event.target)) {
    toggleSidebarMenu();  // Close the sidebar
  }
};

const keyHandler = ({ keyCode }) => {
  if (!sidebarOpen.value || keyCode !== 27) return;
  toggleSidebarMenu(); // Close sidebar on "Escape" key
  emit("close-sidebar");
};

onMounted(() => {
  htmlElement.value = document.documentElement;
  view.value = htmlElement.value.getAttribute('data-sidebar-view');

  if (view.value === 'mobile') {
    toggleSidebarMenu();
    sidebarOpen.value = false;
  }

  document.addEventListener("click", clickHandler);
  document.addEventListener("keydown", keyHandler);
});

onUnmounted(() => {
  document.removeEventListener("click", clickHandler);
  document.removeEventListener("keydown", keyHandler);
});

watch(
    () => view.value,
    () => {
      view.value = htmlElement.value.getAttribute('data-sidebar-view')
    }
);

watch(sidebarExpanded, () => {
  localStorage.setItem("sidebar-expanded", sidebarExpanded.value);
  if (sidebarExpanded.value) {
    document.querySelector("body").classList.add("sidebar-expanded");
  } else {
    document.querySelector("body").classList.remove("sidebar-expanded");
  }
});

// Leave the original toggleSidebarMenu function unchanged
const toggleSidebarMenu = () => {
  console.log(view.value);
  if (view.value === 'mobile') {
    htmlElement.value.classList.toggle('sidebar-open', sidebarOpen.value);
  } else {
    if (view.value === 'hidden') {
      htmlElement.value.setAttribute('data-sidebar-view', 'default');
    } else {
      htmlElement.value.setAttribute('data-sidebar-view', 'hidden');
    }
  }
};
</script>
