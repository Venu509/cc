<template>
  <div class="flex items-center pl-3">
    <input
        :id="getDomainAndPermission"
        type="checkbox"
        :checked="checkPermissions"
        :value="permission.id"
        :disabled="
                permission.domain === 'dashboard' ||
                permission.domain === 'roles'
            "
        class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500 disabled:opacity-75 disabled:bg-gray-400 hover:disabled:bg-gray-400"
        @change="addPermission(permission.id, $event)"
    />
    <label
        :for="getDomainAndPermission"
        class="py-3 ml-2 w-full text-sm font-medium text-gray-900 dark:text-gray-300"
    >{{
        permission.ability === "Index" ? "List" : permission.ability
      }}</label
    >
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";

let props = defineProps({
  permission: {
    type: Object,
    required: true,
  },
  permissions: {
    type: Object,
    required: true,
  },
  domainCheckAll: {
    type: Boolean,
    default: false,
  },
  domainCheckAllState: {
    type: Boolean,
    default: false,
  },
});

let disabledDomainsState = ref(false);

const emit = defineEmits(["update-permissions", "disabled-domains"]);

let permissions = ref(props.permissions);

const getDomainAndPermission = computed(() => {
  return (
      "vue-checkbox-list-" +
      props.permission.name +
      "." +
      props.permission.domain
  );
});

function addPermission(id, event) {
  if (event.target.checked) {
    props.permissions.push({ id: id });
  } else {
    props.permissions.splice(
        props.permissions.findIndex((item) => item.id === id),
        1
    );
  }
  emit("update-permissions", { ...props.permissions });
}

let checkPermissions = computed(() => {
  if (props.domainCheckAllState) {
    return props.domainCheckAll;
  }
  return (
      props.permission.checked ||
      props.permission.domain === "dashboard" ||
      props.permission.domain === "roles"
  );
});

onMounted(() => {
  disabledDomains();
});

function disabledDomains() {
  disabledDomainsState.value =
      props.permission.domain === "dashboard" ||
      props.permission.domain === "roles";

  emit("disabled-domains", disabledDomainsState.value);
}
</script>