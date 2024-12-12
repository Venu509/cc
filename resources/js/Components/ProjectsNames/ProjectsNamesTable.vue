<template>
  <BaseTable
      :title="`Projects Names`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`projects-names/create`"
      @search="emit('search', $event)"
      :items="projectsNames"
      :data-length="5"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Type</BaseTableTh>
        <BaseTableTh>Branch</BaseTableTh>
        <BaseTableTh>Semester</BaseTableTh>

        <BaseTableTh>Date</BaseTableTh>

        <BaseTableTh>Actions</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <ProjectNameTableRow
          v-for="projectName in projectsNames.data"
          :key="projectName.id"
          :projectName="projectName"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="projectsNames"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import ProjectNameTableRow from "@/Components/ProjectsNames/ProjectNameTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search']);

let props = defineProps({
  projectsNames: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
