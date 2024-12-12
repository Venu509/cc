<template>
  <BaseTableTr>
    <BaseTableTd>
      <CheckOne
          :record="student.data"
          :selected="selected"
          value="id"
          @update-selected="handleSelection"
      />
    </BaseTableTd>
    <BaseTableTd>
      {{ student.data.studentId }}
    </BaseTableTd>
    <BaseTableTd>
      {{ student.data.firstName }} {{ student.data.lastName }}
    </BaseTableTd>
    <BaseTableTd>
        <span
            class="inline-flex items-center rounded-md bg-secondary-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-700/10 capitalize">
           {{ student.data.gender }}
        </span>
    </BaseTableTd>
    <BaseTableTd>
         <span
             class="inline-flex items-center rounded-md bg-secondary-50 px-2 py-1 text-xs font-medium text-secondary-700 ring-1 ring-inset ring-secondary-700/10 capitalize">
                 {{ student.data.maritalStatus }}
        </span>
    </BaseTableTd>
    <BaseTableTd>
      {{ student.data.email }}
    </BaseTableTd>
    <BaseTableTd>
      {{ student.data.mobileNumber }}
    </BaseTableTd>
    <BaseTableTd>
      {{ student.data.branch.name }}
    </BaseTableTd>
    <BaseTableTd>
      <img :src="student.data.profilePicture" class="w-auto h-12"/>
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
import ActionDropDown from "@/Components/ActionDropDown.vue";
import CheckOne from "@/Components/BaseTable/CheckOne.vue";

let props = defineProps({
  student: {
    type: Object,
    required: true,
  },
  selected: {
    type: Array,
    required: true,
  },
});

const emit = defineEmits(["select-row", "click-delete", "update-status", "update-selected"]);

const actions = [
  {
    active: true,
    name: "Edit",
    callback: () => {
      Inertia.visit(route("admin.students.show", {id: props.student.data.id}));
    },
    icon: PencilIcon,
  },
  {
    active: true,
    name: "Delete",
    callback: () => {
      clickDeleteAction();
    },
    icon: TrashIcon,
  },
];

function clickDeleteAction() {
  return emit("click-delete", props.student);
}

function handleSelection(id) {
  emit("update-selected", id); // Emit selection event to parent
}
</script>

<style scoped></style>
