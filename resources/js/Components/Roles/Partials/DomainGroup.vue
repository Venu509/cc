<template>
  <div>
    <div class="flex items-center">
      <label class="my-4 font-semibold text-gray-900 capitalize">
        {{ name }}
      </label>
      <input
          :id="domainId"
          v-model="domainCheckAll"
          type="checkbox"
          :checked="checkIfAllPermissionsChecked"
          :disabled="disabledDomains"
          class="mx-3 w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 focus:ring-2 disabled:opacity-75 disabled:bg-gray-400 hover:disabled:bg-gray-400"
          @change="toggleAllPermission"
      />
      <label
          :for="domainId"
          class="my-4 font-semibold text-gray-500 text-xs"
      >
        Check All
      </label>
    </div>
    <div
        class="overflow-x-auto items-center w-full text-sm font-medium text-gray-900 bg-white rounded-lg border border-gray-200 sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white"
    >
      <Permission
          v-for="(permission, index) in domains"
          :key="index"
          :domain-check-all="domainCheckAll"
          :domain-check-all-state="domainCheckAllState"
          :permission="permission"
          :permissions="permissions"
          :update-permissions="permissions"
          @disabled-domains="disabledDomainsCheck"
      />
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from "vue";
import Permission from "@/Components/Roles/Partials/Permission.vue";
defineEmits(["update-permissions", "disabled-domains"]);

let props = defineProps({
  domains: {
    type: Object,
    required: true,
  },
  permissions: {
    type: Object,
    required: true,
  },
  name: {
    type: String,
    required: true,
  },
});

let domainCheckAll = ref(false);
let domainCheckAllState = ref(false);
let disabledDomains = ref(false);

const domainId = computed(() => {
  return "domain-" + props.name;
});

let checkIfAllPermissionsChecked = computed(() => {
  return (
      disabledDomains.value ||
      props.domains.length ===
      props.domains.filter((domain) => {
        return domain.checked === true;
      }).length
  );
});

function toggleAllPermission(event) {
  domainCheckAllState.value = true;
  domainCheckAll.value = event.target.checked;
}

function disabledDomainsCheck(value) {
  disabledDomains.value = value;
}
</script>