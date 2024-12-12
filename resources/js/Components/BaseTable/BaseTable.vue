<template>
  <div class="flex justify-between items-center py-1 mb-2">
    <h4 class="card-title hidden lg:block mr-2">{{ title }}</h4>
    <div class="hidden sm:block"></div>

    <div class="flex items-center overflow-x-auto whitespace-normal cursor-grab w-full sm:w-auto space-x-2">
      <Button
          v-if="hasSelectedIds"
          @click="bulkDelete"
          btn-size="xm"
          btn-color="red"
      >
        Delete Selected
      </Button>

      <SearchBar :has-search="hasSearch" @search="emit('search', $event)"/>

      <input
          v-if="startDate"
          v-model="startDateTerm"
          @input="handleStartDateChange"
          type="date"
          class="border-2 border-gray-300 bg-white h-10 px-3 sm:px-5 rounded-lg text-sm focus:outline-none w-full sm:w-auto mx-2 sm:mx-3"
      />

      <input
          v-if="endDate"
          v-model="endDateTerm"
          @input="handleEndDateChange"
          type="date"
          class="border-2 border-gray-300 bg-white h-10 px-3 sm:px-5 text-sm rounded-full focus:outline-none w-full sm:w-auto mx-2 sm:mx-3"
      />

      <Select
          v-if="isDropDownFilter"
          v-model="selectedDropDownFilter"
          @input="handleDropDownFilterChange"
          class="border-2 border-gray-300 bg-white h-10 px-3 sm:px-5 text-sm rounded-full focus:outline-none w-40 sm:w-auto mx-2 sm:mx-3">
        <option
            v-for="dropDownFilter in dropDownFilters"
            :key="dropDownFilter.value"
            :value="dropDownFilter.value"
        >
          {{ dropDownFilter.label }}
        </option>
      </Select>

      <svg
          v-if="clear"
          @click="handleClear"
          class="h-8 w-8 hover:text-red-500 text-gray-500 cursor-pointer mx-2 sm:mx-3"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
      >
        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
      </svg>

      <div v-if="hasButton">
        <Button v-if="buttonRoute && isButton && showButton"
                @click="redirect(buttonRoute)">
          {{ buttonText }}
        </Button>
        <Button v-else-if="!buttonRoute && isButton && showButton"
                @click="$emit('button-clicked')"
                class="btn btn-sm bg-secondary-700 !text-sm text-white">
          {{ buttonText }}
        </Button>
      </div>
    </div>
  </div>

  <div class="card relative overflow-x-auto">

    <!-- Table Content -->
    <div class="min-w-full inline-block align-middle whitespace-nowrap">
      <div class="overflow-x-visible">
        <table class="min-w-full divide-y divide-gray-300 bg-[#FBF7F3]" id="main-table">
          <slot></slot>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import SearchBar from "@/Components/SearchBar.vue";
import Select from "@/Components/Select.vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";
import eventBus from '@/Components/Widgets/Events/EventBus.js';

const emit = defineEmits(["search", "start-date", "end-date", "clear", 'dropDownFilters', "bulk-delete"]);

const props = defineProps({
  title: {type: String, default: 'Table'},
  hasButton: {type: Boolean, default: true},
  buttonText: {type: String, default: 'Create'},
  buttonRoute: {type: String, default: null},
  isButton: {type: Boolean, default: true},
  showButton: {type: Boolean, default: true},
  hasSearch: {type: Boolean, default: true},
  startDate: {type: Boolean, default: false},
  endDate: {type: Boolean, default: false},
  clear: {type: Boolean, default: false},
  isDropDownFilter: {type: Boolean, default: false},
  dropDownFilters: {type: Array, required: false},
  dropDownUrlParamLabel: {type: String, default: 'type'},
  items: {type: Object, default: {}},
  dataLength: {
    type: Number,
    require: true,
  },
  selectedIds: {type: Array, default: []},
});

let urlSearchParams = new URLSearchParams(window.location.search);
let startDateTerm = ref(urlSearchParams.get('startDate') || '');
let endDateTerm = ref(urlSearchParams.get('endDate') || '');
let selectedDropDownFilter = ref(urlSearchParams.get(props.dropDownUrlParamLabel) || "all")

let hasSelectedIds = computed(() => {
  return props.selectedIds && props.selectedIds.length > 0;

});

const handleStartDateChange = () => emit('startDate', startDateTerm.value);
const handleEndDateChange = () => emit('endDate', endDateTerm.value);
const handleDropDownFilterChange = () => emit('dropDownFilters', selectedDropDownFilter.value);
const handleClear = () => emit('clear');

function redirect(link) {
  Inertia.replace(link)
}

onMounted(() => {
  const tableElement = document.getElementById("main-table");
  if (tableElement) {
    // const dataTable = new DataTable(tableElement, {
    //   paging: false,
    //   searchable: false,
    //   sortable: true,
    //   footer: false,
    //   header: true,
    //   labels: {
    //     noRows: ''
    //   }
    // });

    if (!props.items?.data?.length) {
      const tableBody = tableElement.querySelector('tbody');
      if (tableBody) {
        const footerHTML = `
          <tfoot>
            <tr>
              <td colspan="${props.dataLength}" class="px-2 first:pl-5 last:pr-5 whitespace-nowrap py-4 pl-4 pr-3 text-sm text-center font-medium text-gray-500 sm:pl-6">
                No data available
              </td>
            </tr>
          </tfoot>
        `;
        tableBody.insertAdjacentHTML('afterend', footerHTML);
      }
    }
  }
});

eventBus.on('clear-selected-ids', () => {
  console.log('event bus called')
  hasSelectedIds.value = false;
  props.selectedIds = null
});
function bulkDelete() {
  emit("bulk-delete", props.selectedIds)
}
</script>

<style scoped>
</style>
