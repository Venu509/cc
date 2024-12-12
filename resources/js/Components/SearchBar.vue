<script setup>
import {SearchIcon} from "@heroicons/vue/solid";
import {XIcon} from "@heroicons/vue/outline";
import {ref} from "vue";

const emit = defineEmits(["search"]);

let props = defineProps({
  hasSearch: {
    type: Boolean,
    default: true
  }
});

let urlSearchParams = new URLSearchParams(window.location.search);
let searchTerm = ref(urlSearchParams.get('search') || '');

removeSearchParam()

const handleSearch = () => {
  emit('search', searchTerm.value);
  removeSearchParam()
}

function removeSearchParam() {
  if (searchTerm.value.trim() === '') {
    urlSearchParams.delete('search');
    window.history.replaceState({}, '', window.location.pathname + '?' + urlSearchParams.toString());
  }
}

function clearSearch() {
  searchTerm.value = ''
  urlSearchParams.delete('search');
  window.history.replaceState({}, '', window.location.pathname + '?' + urlSearchParams.toString());
  handleSearch()
}
</script>

<template>

  <div class="flex items-center border border-gray-300 rounded-full px-4 w-full max-w-lg mx-auto bg-white shadow-sm" v-if="hasSearch">
    <SearchIcon class="h-8 w-8 text-gray-400 mr-3"/>

    <input
        type="text"
        placeholder="Search for anything..."
        v-model="searchTerm"
        @input="handleSearch"
        autofocus
        class="w-full bg-transparent text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-transparent border-transparent"
    />
    <button v-if="searchTerm" @click="clearSearch" class="focus:outline-none ml-2">

      <XIcon class="h-5 w-5 text-gray-400"/>
    </button>
  </div>
</template>
