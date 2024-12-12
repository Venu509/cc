<template>
  <BaseTableTr>
    <BaseTableTd> {{ lead.data.title }}</BaseTableTd>

    <BaseTableTd class="capitalize">
      <RoleWidget :title="lead.data.type.label" :color="lead.data.type.color"/>
    </BaseTableTd>

    <BaseTableTd> {{ lead.data.status }}</BaseTableTd>

    <BaseTableTd> {{ lead.data.description }}</BaseTableTd>

    <BaseTableTd> {{ lead.data.lastAccessedBy }}</BaseTableTd>

    <BaseTableTd>
      <ActionDropDown :actions="actions"/>
    </BaseTableTd>
  </BaseTableTr>
</template>

<script setup>
import {computed, defineEmits} from "vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr";
import BaseTableTd from "@/Components/BaseTable/BaseTableTd";
import ActionDropDown from "@/Components/ActionDropDown";
import {Inertia} from "@inertiajs/inertia";
import {PencilIcon} from "@heroicons/vue/solid";
import RoleWidget from "@/Components/Widgets/RoleWidget.vue";

let props = defineProps({
  lead: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits([
  "select-row",
  "click-delete",
  "update-status",
]);

const actions = [
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("admin.leads.show", {id: props.lead.data.id}));
    },
    icon: PencilIcon,
  },
];

const colorClass = computed(() => {
  const colorMap = {
    green: 'bg-green-100 text-green-800 border-green-400',
    yellow: 'bg-yellow-100 text-yellow-800 border-yellow-400',
    indigo: 'bg-indigo-100 text-indigo-800 border-indigo-400',
    purple: 'bg-purple-100 text-purple-800 border-purple-400',
  };

  return colorMap[props.lead.data.type.color] || 'bg-gray-100 text-gray-800 border-gray-400';
});

function clickDeleteAction() {
  return emit("click-delete", props.lead);
}
</script>

<style scoped></style>
