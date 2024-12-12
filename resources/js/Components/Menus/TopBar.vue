<template>
  <header class="app-header flex items-center px-5 gap-4 static">
    <button id="button-toggle-menu" @click="toggleSidebar" class="nav-link p-2">
      <span class="sr-only">Menu Toggle Button</span>
      <span class="flex items-center justify-center h-6 w-6">
        <MenuIcon class="h-5 w-5"/>
      </span>
    </button>
    <h4 class="text-slate-900 text-xs sm:text-lg font-medium">{{ title }}</h4>

    <div class="flex justify-center items-center dark:bg-gray-500 ms-auto">
      <div class="bg-white dark:bg-gray-800 flex justify-center items-center">
        <div class="flex flex-col mx-4" v-if="role.name === 'marketing'">
          <button
              v-if="isClockedIn"
              type="button"
              @click="captureLocation"
              class="text-white bg-red-700 hover:bg-red-700/90 focus:ring-4 focus:ring-red-700/50 focus:outline-none font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:focus:ring-red-700/50">
            <ClockIcon class="h-4"/>
            <span class="hidden md:inline ml-2">Clock Out</span>
          </button>
          <button
              v-else
              type="button"
              @click="captureLocation"
              class="text-white bg-secondary-700 hover:bg-secondary-700/90 focus:ring-4 focus:ring-secondary-700/50 focus:outline-none font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center dark:focus:ring-secondary-700/50">
            <ClockIcon class="h-4"/>
            <span class="hidden md:inline ml-2">Clock In</span>
          </button>
          <div v-if="isClockedIn">
            <p class="text-xs">{{ attendance.clockInAt }}</p>
          </div>
        </div>

        <div
            @click="toggleNotificationPanel"
            class="border-b-4 border-transparent py-3 cursor-pointer me-2.5"
            :class="{'border-indigo-700 transform transition duration-300': notificationPanelOpen}">
          <transition name="fade">
            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-gray-400 hover:bg-gray-100 cursor-pointer hover:shadow-lg transition-all duration-200">
              <BellIcon class="h-6 w-6" />
            </div>
          </transition>

          <transition name="slide-fade">
            <div
                v-if="notificationPanelOpen"
                class="absolute right-[1rem] lg:right-[0rem] mt-2 top-14 bg-primary-100 overflow-hidden z-50 rounded-lg shadow border dark:border-transparent"
                style="width:20rem;">
              <div class="py-2">
                <NotificationItem
                    v-for="(notification, index) in notifications.data"
                    :key="index"
                    :notification="notification"
                    @read-notification="readNotification"
                />
              </div>
              <a href="#" class="block bg-gray-800 text-white text-center font-bold py-2">
                See All Notifications
              </a>
            </div>
          </transition>
        </div>

        <div
            @click="toggleProfileBar"
            class="relative border-b-4 border-transparent"
            :class="{'border-indigo-700 transform transition duration-300': open}">
          <transition name="fade">
            <div class="flex items-center space-x-3 cursor-pointer rounded-full border border-gray-300 px-2 py-1 shadow-sm hover:shadow-lg transition-all duration-200">
              <div class="w-9 h-9 rounded-full overflow-hidden">
                <img :src="authUser.avatar" class="w-9 h-9 rounded-full object-cover" />
              </div>
              <div class="inline font-semibold text-gray-900 text-sm">
                <div class="text-sm">{{ authUser.name }}</div>
                <div class="text-gray-500 text-xs">Online</div>
              </div>
              <div class="text-gray-500">
                <ChevronDownIcon class="h-5 w-5"/>
              </div>
            </div>
          </transition>

          <transition name="slide-fade">
            <div
                v-if="open"
                class="absolute right-0 w-36 px-5 py-3 dark:bg-gray-800 bg-white rounded-lg shadow border dark:border-transparent mt-3">
              <ul class="space-y-3 dark:text-white">
                <li class="font-medium">
                  <span
                      class="flex items-center cursor-pointer transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700"
                      @click="myAccount">
                    My Account
                  </span>
                </li>
                <li class="font-medium">
                  <span
                      class="flex items-center cursor-pointer transform transition-colors duration-200 border-r-4 border-transparent hover:border-indigo-700"
                      @click="logout">
                    Logout
                  </span>
                </li>
              </ul>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import {computed, onMounted, ref, watch} from 'vue';
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {ClockIcon, MenuIcon, ChevronDownIcon} from "@heroicons/vue/solid";
import {BellIcon} from "@heroicons/vue/outline";
import {useForm} from "@inertiajs/vue3";
import NotificationItem from "@/Components/Widgets/NotificationItem.vue";

const props = defineProps({
  authUser: {
    type: Object,
    required: true,
  },
  title: {
    type: String,
    default: 'Dashboard',
  },
});

const sidebarOpen = ref(false);
const open = ref(false);
const notificationPanelOpen = ref(false);
const location = ref(null);

const page = usePage().props.value;
const role = page.role;
const notifications = page.notifications;
const attendance = page.attendance.isClockedIn ? page.attendance.attendance.data : null;
const isClockedIn = page.attendance.isClockedIn;
const htmlElement = ref(null);

const isGovernment = computed(() => role.name === 'government')
const isCandidate = computed(() => role.name === 'candidate')
const isInstitution = computed(() => role.name === 'institution')
const isCompany = computed(() => role.name === 'company')
let isAcceptedRole = isGovernment || isCandidate || isInstitution || isCompany

let form = useForm({
  id: isClockedIn ? attendance.id : null,
  role: page.role.name,
  isClockIn: isClockedIn ? 'yes' : 'no',
  coordinates: location.value,
});

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
  document.documentElement.classList.toggle('sidebar-open', sidebarOpen.value);

  const view = htmlElement.value.getAttribute('data-sidebar-view');

  if (view === 'mobile') {
    htmlElement.value.classList.toggle('sidebar-open', sidebarOpen.value);
  } else {
    if (view === 'hidden') {
      htmlElement.value.setAttribute('data-sidebar-view', 'default');
    } else {
      htmlElement.value.setAttribute('data-sidebar-view', 'hidden');
    }
  }
};

const toggleProfileBar = () => {
  notificationPanelOpen.value = notificationPanelOpen.value ? false : false;
  open.value = !open.value;
};

const toggleNotificationPanel = () => {
  open.value = open.value ? false : false;
  notificationPanelOpen.value = !notificationPanelOpen.value;
};

const logout = () => {
  Inertia.post(route("admin.logout"));
};

function myAccount() {
  Inertia.get(route("admin.my-accounts.show", props.authUser.id), {
    tab: !isAcceptedRole ? 'change-password' : 'personal-details',
  });
}

onMounted(() => {
  htmlElement.value = document.documentElement;
});

const clockIn = () => {
  Inertia.post(route("admin.attendances.store"), form, {
    onError: (errors) => {
      form.clearErrors().setError(errors);
    },
    onSuccess: () => {
      form.reset();
      Inertia.get(route("admin.dashboard"), {}, {replace: true});
    },
  });
};

const captureLocation = () => {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        (position) => {
          location.value = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
          };
        },
        (error) => {
          if (error.code === 1) {
            alert("Location access is blocked. Please enable location services in your browser settings.");
          } else {
            alert("Unable to retrieve location. Please try again.");
          }
        }
    );
  } else {
    alert("Geolocation is not supported by this browser.");
  }
};

watch(
    () => location.value,
    () => {
      form.coordinates = location.value;
      clockIn();
    }
);

function readNotification() {
  console.log('clicked');
}
</script>
