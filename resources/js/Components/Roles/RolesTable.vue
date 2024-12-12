<template>
  <BaseTable
      :title="`Roles`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`roles/create`"
      @search="emit('search', $event)"
      :items="roles"
      :data-length="5"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>ID</BaseTableTh>
        <BaseTableTh>Display Name</BaseTableTh>
        <BaseTableTh>Name</BaseTableTh>
        <BaseTableTh>Actions</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <RoleTableRow
          v-for="(role, index) in roles.data"
          :key="role.id"
          :role="role"
          :index="index + 1"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="roles"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import RoleTableRow from "@/Components/Roles/RoleTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";

const emit = defineEmits(["click-delete", "update-status", 'search']);

let props = defineProps({
  roles: {
    type: Object,
    required: true,
  },
});

</script>

<style scoped>

</style>
