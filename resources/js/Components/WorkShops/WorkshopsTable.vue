<template>
  <BaseTable
      :title="`Workshops`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`workshops/create`"
      @search="emit('search', $event)"
      :items="workshops"
      :data-length="4"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Branch</BaseTableTh>
        <BaseTableTh>Semester</BaseTableTh>

        <BaseTableTh>Start date</BaseTableTh>

        <BaseTableTh>End date</BaseTableTh>

        <BaseTableTh>Actions</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <WorkshopTableRow
          v-for="workshop in workshops.data"
          :key="workshop.id"
          :workshop="workshop"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="workshops"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import WorkshopTableRow from "@/Components/Workshops/WorkshopTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search']);

let props = defineProps({
  workshops: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
