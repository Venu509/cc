<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ index }}
    </BaseTableTd>

    <BaseTableTd>
      {{ role.data.displayName }}
    </BaseTableTd>

    <BaseTableTd>
      {{ role.data.name }}
    </BaseTableTd>
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
  role: {
    type: Object,
    required: true,
  },
  index: {
    type: Number,
    required: true,
  }
});

const emit = defineEmits(["select-row", "click-delete", "update-status"]);

const actions = [
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("admin.roles.show", {id: props.role.data.id}));
    },
    icon: PencilIcon,
  },
];

function clickDeleteAction() {
  return emit("click-delete", props.role);
}
</script>

<style scoped></style>
