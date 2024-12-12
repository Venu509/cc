<template>
  <BaseTableTr>
    <BaseTableTd> {{ banner.data.title }}</BaseTableTd>

    <BaseTableTd>
      <img
          :src="banner.data.image"
          class="w-16"
          style="background-color: black"
      />
    </BaseTableTd>

    <BaseTableTd>
      <Toggle
          :data="banner.data"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTd>

    <BaseTableTd> {{ banner.data.lastAccessedBy }}</BaseTableTd>

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
import Toggle from "@/Components/Toggle.vue";

let props = defineProps({
  banner: {
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
      Inertia.visit(route("admin.banners.show", {id: props.banner.data.id}));
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

  return colorMap[props.banner.data.type.color] || 'bg-gray-100 text-gray-800 border-gray-400';
});

function clickDeleteAction() {
  return emit("click-delete", props.banner);
}
</script>

<style scoped></style>
