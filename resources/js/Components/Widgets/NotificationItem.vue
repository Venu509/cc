<script setup>

import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
  notification: {
    type: Object,
    required: true
  }
})

let emit = defineEmits(['read-notification'])

let notificationData = props.notification.data

function edit() {
  Inertia.visit(notificationData.additionalInfo?.route)
}

function readNotification() {
  emit('read-notification')
}
</script>

<template>
  <div>
    <div @click="readNotification" class="grid lg:grid-cols-5 gap-1 items-center px-4 py-3 border-b hover:bg-gray-100 -mx-2">
      <div class="col-span-4 flex">
        <div class="h-8 w-8 rounded-full object-cover">
          <img class="h-8 w-8 rounded-full object-cover mx-1" :src="notificationData.user.avatar" alt="avatar">
        </div>
        <p class="text-gray-600 text-sm mx-2">
          {{ notificationData.message  }} <span v-if="notificationData.additionalInfo?.hasRoute" class="text-xs font-bold text-blue-500" @click="edit">{{ notificationData.additionalInfo?.routeName }}</span>  .
        </p>
      </div>
      <div class="col-span-1">
        <span class="text-xs text-primary-300 font-light">{{ notificationData.createdAt }}</span>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>