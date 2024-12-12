<template>
  <BaseTable
      :title="`Students`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`students/create`"
      @search="emit('search', $event)"
      :items="students"
      :data-length="9"
      :selected-ids="selectedIds"
      @bulk-delete="$emit('bulk-delete', $event)"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>
          <CheckAll :records="students.data" @update-all-selection="handleAllSelection"/>
        </BaseTableTh>
        <BaseTableTh>ID</BaseTableTh>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Gender</BaseTableTh>
        <BaseTableTh>Marital Status</BaseTableTh>
        <BaseTableTh>Email</BaseTableTh>
        <BaseTableTh>Mobile Number</BaseTableTh>
        <BaseTableTh>Branch</BaseTableTh>
        <BaseTableTh>Profile Picture</BaseTableTh>
        <BaseTableTh>Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <StudentTableRow
          v-for="student in students.data"
          :key="student.id"
          :student="student"
          :selected="selectedIds"
          @update-selected="handleSingleSelection"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="students"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import StudentTableRow from "@/Components/Students/StudentTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits, onMounted, ref} from "vue";
import CheckAll from "@/Components/BaseTable/CheckAll.vue";

const emit = defineEmits(["click-delete", "update-status", 'search', "bulk-delete"]);

let props = defineProps({
  students: {
    type: Object,
    required: true,
  },
});

const selectedIds = ref([]);

function handleSingleSelection({ id, checked }) {
  if (checked) {
    if (!selectedIds.value.includes(id)) {
      selectedIds.value.push(id);
    }
  } else {
    selectedIds.value = selectedIds.value.filter(selectedId => selectedId !== id);
  }
}

function handleAllSelection(ids) {
  selectedIds.value = ids;
}

// onMounted(() => {
//   selectedIds.value = null
// });
</script>

<style scoped>

</style>
