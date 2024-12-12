<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ employee.data.role }}
    </BaseTableTd>

    <BaseTableTd>
      {{ employee.data.name }}
    </BaseTableTd>

    <BaseTableTd>
      {{ employee.data.email }}
    </BaseTableTd>

    <BaseTableTd>
      {{ employee.data.phone }}
    </BaseTableTd>

    <BaseTableTd>
      <img
          :src="employee.data.avatar"
          class="w-10"
          style="background-color: black"
      />
    </BaseTableTd>
    <BaseTableTd>
      <Toggle
          :data="employee.data"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTd>

    <BaseTableTd> {{ employee.data.lastAccessedBy }}</BaseTableTd>
    <BaseTableTd>
      <ActionDropDown :actions="actions"/>
    </BaseTableTd>

  </BaseTableTr>
</template>

<script setup>
import {defineEmits} from "vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr";
import BaseTableTd from "@/Components/BaseTable/BaseTableTd";
import {Inertia} from "@inertiajs/inertia";
import {PencilIcon, TrashIcon} from "@heroicons/vue/solid";
import Toggle from "@/Components/Toggle.vue";
import ActionDropDown from "@/Components/ActionDropDown.vue";

let props = defineProps({
  employee: {
    type: Object,
    required: true,
  }
});

const emit = defineEmits(["select-row", "click-delete", "update-status"]);

const actions = [
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("users.show", {id: props.employee.data.id}));
    },
    icon: PencilIcon,
  },
  // {
  //   active: true,
  //   name: "Delete",
  //   callback: () => {
  //     clickDeleteAction();
  //   },
  //   icon: TrashIcon,
  // },
];

function clickDeleteAction() {
  return emit("click-delete", props.employee);
}
</script>

<style scoped></style>
