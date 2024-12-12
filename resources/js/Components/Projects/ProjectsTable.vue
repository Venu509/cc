<template>
  <BaseTable
      :title="`Projects`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`projects/create`"
      @search="emit('search', $event)"
      :items="projects"
      :data-length="5"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Type</BaseTableTh>
        <BaseTableTh>Branch</BaseTableTh>
        <BaseTableTh>Semester</BaseTableTh>
        <BaseTableTh>Start date</BaseTableTh>
        <BaseTableTh>End date</BaseTableTh>
        <BaseTableTh>Actions</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <ProjectTableRow
          v-for="project in projects.data"
          :key="project.id"
          :project="project"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="projects"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import ProjectTableRow from "@/Components/Projects/ProjectTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search']);

let props = defineProps({
  projects: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
