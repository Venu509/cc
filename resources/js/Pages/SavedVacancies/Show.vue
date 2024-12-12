<template>
  <AppLayout title="Saved Job">
      <div class=" mx-auto bg-white shadow-md rounded-md p-6">
          <div class="flex flex-col md:flex-row justify-between items-center">
              <div class="flex items-center space-x-4">
                  <img :src="vacancy.company.avatar" alt="Company Logo" class="w-12 h-12 rounded-full">
                  <div>
                      <h2 class="text-xl font-bold">{{ vacancy.title }}</h2>
                      <p class="text-sm text-green-500 flex items-center">
                          {{ vacancy.company.name }}
                          <BadgeCheckIcon v-if="vacancy.company.emailVerified" class="w-4 h-4 text-green-400 ml-1"/>
                      </p>
                      <p class="text-sm text-gray-600">{{ vacancy.parent.label}} > {{ vacancy.child.label }}</p>
                      <div class="flex items-center space-x-2 mt-1 text-sm text-gray-500">
                            <span class="flex items-center"><BriefcaseIcon class="w-4 h-4 text-blue-400 mr-0.5" />
                                  <template v-for="(workMode, index) in vacancy.workModes">
                                        <span >
                                          {{ workMode }} <span v-if="index < vacancy.workModes.length - 1"> | &nbsp; </span>
                                        </span>
                                      </template>
                            </span>
                          <span class="flex items-center"><LocationMarkerIcon class="w-4 h-4 text-red-400 mr-0.5" /> {{ vacancy.location }} </span>
                          <span class="flex items-center"><CurrencyDollarIcon class="w-4 h-4 text-yellow-400 mr-0.5" /> {{ vacancy.salary }} </span>
                      </div>
                  </div>
              </div>
              <div class="mt-4 md:mt-0 text-center md:text-right">
                  <div class="mt-6 text-center">
                      <Button>Apply for this job</Button>
                  </div>
              </div>
          </div>

          <div class="mt-6">
              <div class="mt-2">
                  <div v-html="vacancy.description"></div>
              </div>
          </div>
          <div class="mt-6">
              <h3 class="text-lg font-bold">Qualifications:</h3>
              <ul class="list-disc list-inside text-gray-700 mt-2 ml-3">
                  <li v-for="qualification in vacancy.qualifications">{{ qualification }}</li>
              </ul>
          </div>

          <div class="mt-6">
              <h3 class="text-lg font-bold">Benefits:</h3>
              <ul class="list-disc list-inside text-gray-700 mt-2 ml-3">
                  <li v-for="benefit in vacancy.benefits">{{ benefit }}</li>
              </ul>
          </div>

      </div>
  </AppLayout>
</template>

<script setup>
import {computed, onMounted, onUnmounted, ref} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import Button from "@/Components/Button.vue";
import {BadgeCheckIcon, BriefcaseIcon, LocationMarkerIcon, CurrencyDollarIcon} from "@heroicons/vue/solid";

const props = defineProps({
  vacancy: {
    type: Object,
    required: true
  }
})
</script>

<style scoped>
/* Add any custom styles if necessary */
</style>
