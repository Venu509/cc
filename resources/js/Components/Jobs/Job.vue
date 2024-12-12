<template>
    <!--  <div class="mx-auto bg-white shadow-md rounded-lg overflow-hidden mt-4 mb-1 w-full lg:w-full relative">-->
    <!--    <div class="p-4 flex flex-col sm:flex-row justify-between">-->
    <!--      <div class="mr-0 sm:mr-4 flex-shrink-0">-->
    <!--        <img class="h-12 w-12 sm:h-16 sm:w-16 object-cover mx-auto sm:mx-0" :src="job.company.avatar" alt="Logo">-->
    <!--      </div>-->
    <!--      <div class="flex-1 mt-4 sm:mt-0">-->
    <!--        <h3 class="text-lg font-semibold text-gray-800 text-center sm:text-left">{{ job.title }}</h3>-->
    <!--        <p class="text-sm text-green-500 flex justify-center sm:justify-start items-center mt-1">-->
    <!--          {{ job.company.name }}-->
    <!--          <BadgeCheckIcon v-if="job.company.emailVerified" class="w-4 h-4 text-green-400 ml-1" />-->
    <!--        </p>-->
    <!--        <p class="text-sm text-gray-600 text-center sm:text-left mt-1">{{ job.category.parent.title }} > {{ job.category.title }}</p>-->

    <!--        &lt;!&ndash; Work modes, location, salary, and posted on date &ndash;&gt;-->
    <!--        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0 sm:space-x-2 mt-2 text-sm text-gray-500">-->
    <!--          <div class="flex flex-col sm:flex-row sm:space-x-2 items-center sm:items-start justify-center sm:justify-start">-->
    <!--                <span class="flex items-center">-->
    <!--                    <BriefcaseIcon class="w-4 h-4 text-blue-400 mr-0.5" />-->
    <!--                    <template v-for="(workMode, index) in job.workModes">-->
    <!--                        <span>-->
    <!--                            {{ workMode }} <span v-if="index < job.workModes.length - 1"> | &nbsp; </span>-->
    <!--                        </span>-->
    <!--                    </template>-->
    <!--                </span>-->
    <!--            <span class="flex items-center mt-1 sm:mt-0">-->
    <!--                <LocationMarkerIcon class="w-4 h-4 text-red-400 mr-0.5" /> {{ job.location }}-->
    <!--            </span>-->
    <!--            <span class="flex items-center mt-1 sm:mt-0">-->
    <!--                <CurrencyDollarIcon class="w-4 h-4 text-yellow-400 mr-0.5" /> {{ job.salary }}-->
    <!--            </span>-->
    <!--          </div>-->

    <!--          <div class="text-sm text-gray-400 text-center sm:text-right sm:absolute sm:right-5">-->
    <!--            <span>Posted on {{ job.createdAt }}</span>-->
    <!--          </div>-->
    <!--        </div>-->
    <!--      </div>-->

    <!--      <div class="mt-4 flex flex-row items-center justify-center space-x-2">-->
    <!--        <Button-->
    <!--                type="button"-->
    <!--                btn-size="xm"-->
    <!--                btn-color="light"-->
    <!--                @click="view"-->
    <!--        >-->
    <!--          <span>View</span>-->
    <!--        </Button>-->

    <!--        <Button :disabled="job.isApplied"-->
    <!--                type="button"-->
    <!--                btn-size="xm"-->
    <!--                btn-color="blue"-->
    <!--                class="flex"-->
    <!--                @click="clickSaveAction(job)">-->
    <!--          <span>{{ job.isApplied ? 'Applied' : 'Apply' }}</span>-->
    <!--          <ExternalLinkIcon v-if="job.applicationMethod === 'external' && !job.isApplied" class="h-4 w-4" />-->
    <!--        </Button>-->

    <!--        <BookmarkIcon v-if="!job.isSaved" class="w-8 h-8 sm:ml-1.5 cursor-pointer hover:text-purple-500" @click="addToSave" />-->
    <!--        <filledBookmarkIcon v-if="job.isSaved" class="w-8 h-8 sm:ml-1.5 cursor-pointer text-green-400 hover:text-green-500" @click="addToSave" />-->
    <!--      </div>-->

    <!--    </div>-->
    <!--  </div>-->
  <div class="bg-white rounded-lg shadow-md p-4 flex flex-col justify-between relative mb-5">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
      <div class="flex items-center space-x-4">
        <img class="h-12 w-12 sm:h-16 sm:w-16 object-cover mx-auto sm:mx-0" :src="job.company.avatar" alt="Logo">
        <div>
          <h3 class="text-lg font-semibold capitalize">{{ job.title }}</h3>
          <p class="text-gray-500 capitalize">{{ job.company.name }}</p>

          <div class="text-gray-400 text-sm mt-1 sm:hidden">
            {{ job.readableDate }}
          </div>
        </div>
      </div>

      <div class="hidden sm:block absolute sm:static top-4 right-4 text-gray-400 text-sm">
        {{ job.readableDate }}
      </div>
    </div>

    <div class="flex justify-between items-center mt-1 flex-wrap">
      <div class="text-gray-400 flex items-center">
        <LocationMarkerIcon class="w-4 h-4 text-gray-400 mr-0.5" />
        <div class="capitalize" v-for="location in job.locations" :key="location">{{ location }}, &nbsp;</div>
      </div>

      <div class="flex space-x-2 mt-2 sm:mt-0">
        <Button
            type="button"
            btn-size="xm"
            btn-color="light"
            @click="view"
        >
          <span>View</span>
        </Button>

        <Button :disabled="job.isApplied"
                type="button"
                btn-size="xm"
                btn-color="blue"
                class="flex"
                @click="clickSaveAction(job)">
          <span>{{ job.isApplied ? 'Applied' : 'Apply' }}</span>
          <ExternalLinkIcon v-if="job.applicationMethod === 'external' && !job.isApplied" class="h-4 w-4" />
        </Button>
      </div>
    </div>
  </div>

</template>
<script setup>
import {
    LocationMarkerIcon,
    BadgeCheckIcon,
    BriefcaseIcon,
    CurrencyDollarIcon,
    ExternalLinkIcon,
    BookmarkIcon as filledBookmarkIcon
} from "@heroicons/vue/solid";
import {
    BookmarkIcon
} from "@heroicons/vue/outline";
import {defineEmits} from "vue";
import Button from "@/Components/Button.vue";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    job: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(["click-save", "add-to-save"]);

function clickSaveAction(job) {
    if (job.applicationMethod === 'internal') {
        return emit("click-save", props.job);
    } else {
        window.open(job.externalLink, '_blank');
    }
}

function addToSave() {
    return emit("add-to-save", props.job);
}

function view() {
    Inertia.visit(route('admin.jobs.show', props.job.id));
}

</script>

<style scoped>
</style>
