<template>
  <BaseTable
      :title="`Vacancies`"
      :has-button="true"
      :button-text="`Create`"
      :button-route="`vacancies/create`"
      @search="emit('search', $event)"
      :items="vacancies"
      :data-length="9"
  >
    <BaseTableThead>
      <BaseTableTr>
        <BaseTableTh>Title</BaseTableTh>
        <BaseTableTh v-if="role.name !== 'company'">Company</BaseTableTh>
        <BaseTableTh>Work Modes</BaseTableTh>
        <BaseTableTh>Category</BaseTableTh>
        <BaseTableTh>Salary</BaseTableTh>
        <BaseTableTh>Expire Date</BaseTableTh>
        <BaseTableTh>Actions</BaseTableTh>
      </BaseTableTr>
    </BaseTableThead>

    <BaseTableTbody>
      <VacancyTableRow
          v-for="vacancy in vacancies.data"
          :key="vacancy.id"
          :vacancy="vacancy"
          @click-delete="emit('click-delete', $event)"
          @update-status="emit('update-status', $event)"
      />
    </BaseTableTbody>
  </BaseTable>
  <Pagination :items="vacancies"/>
</template>

<script setup>

import BaseTable from "@/Components/BaseTable/BaseTable.vue";
import BaseTableThead from "@/Components/BaseTable/BaseTableThead.vue";
import BaseTableTr from "@/Components/BaseTable/BaseTableTr.vue";
import BaseTableTh from "@/Components/BaseTable/BaseTableTh.vue";
import BaseTableTbody from "@/Components/BaseTable/BaseTableTbody.vue";
import VacancyTableRow from "@/Components/Vacancies/VacancyTableRow.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineEmits} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

const emit = defineEmits(["click-delete", "update-status", 'search']);

let props = defineProps({
  vacancies: {
    type: Object,
    required: true,
  },
});

let page = usePage().props.value;
let role = page.role;

</script>

<style scoped>

</style>
