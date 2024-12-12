<template>
  <BaseTableTr>
    <BaseTableTd>
      {{ vacancy.data.title }}
    </BaseTableTd>

    <BaseTableTd v-if="role.name !== 'company'">
      {{ vacancy.data.company?.name }}
    </BaseTableTd>

    <BaseTableTd>
      <RoleWidget
          v-if="vacancy.data.workModes"
          v-for="(workMode, index) in vacancy.data.workModes"
          :key="index"
          :title="workMode" color="green" class="mt-2"/>
    </BaseTableTd>

    <BaseTableTd>
      {{ vacancy.data.category?.title }}
    </BaseTableTd>

    <BaseTableTd>
      {{ vacancy.data.salary }}
    </BaseTableTd>

    <BaseTableTd>
      {{ vacancy.data.expireDate }}
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
import {PencilIcon, DatabaseIcon} from "@heroicons/vue/solid";
import Toggle from "@/Components/Toggle.vue";
import ActionDropDown from "@/Components/ActionDropDown.vue";
import RoleWidget from "@/Components/Widgets/RoleWidget.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";

let props = defineProps({
  vacancy: {
    type: Object,
    required: true,
  }
});

let page = usePage().props.value;
let role = page.role;

const emit = defineEmits(["select-row", "click-delete", "update-status"]);

const actions = [
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("admin.vacancies.show", {id: props.vacancy.data.id}));
    },
    icon: PencilIcon,
  },
  {
    active: true,
    name: "Applied Candidates",
    callback: () => {
      Inertia.visit(`${route("admin.applications.show", { id: props.vacancy.data.id })}?tab=pending`, {
        preserveState: true,
        replace: true
      });
    },
    icon: DatabaseIcon,
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
  return emit("click-delete", props.vacancy);
}
</script>

<style scoped></style>
