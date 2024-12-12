<template>
  <BaseTable
      :title="`Branches`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`branches/create`"
      @search="emit('search', $event)"
      :items="branches"
      :data-length="4"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>S.No</BaseTableTh>

        <BaseTableTh>Name</BaseTableTh>

        <BaseTableTh>Assigned Students</BaseTableTh>

        <BaseTableTh>Action</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <BranchTableRow
          v-for="(branch, index) in branches.data"
          :key="branch.id"
          :index="index + 1"
          :branch="branch"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="branches"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import BranchTableRow from "@/Components/Branches/BranchTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search']);

let props = defineProps({
  branches: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
