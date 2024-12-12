<template>
    <div
        v-if="title"
        aria-live="assertive"
        class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start z-50"
    >
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="show"
                    class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <component
                                    :is="typeIcon"
                                    :class="typeColor"
                                    aria-hidden="true"
                                    class="h-6 w-6"
                                />
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ title }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    {{ message }}
                                </p>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-300"
                                    type="button"
                                    @click="show = false"
                                >
                                    <span class="sr-only">Close</span>
                                    <XIcon aria-hidden="true" class="h-5 w-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import { XIcon } from "@heroicons/vue/solid";
import {
    CheckCircleIcon,
    XCircleIcon,
    ExclamationCircleIcon,
} from "@heroicons/vue/outline";

const TYPE_TO_ICON = {
    error: "XCircleIcon",
    success: "CheckCircleIcon",
    warning: "ExclamationCircleIcon",
};

const TYPE_TO_COLOR = {
    error: "text-red-500",
    success: "text-green-500",
    warning: "text-yellow-500",
};
export default {
    components: {
        XIcon,
        XCircleIcon,
        CheckCircleIcon,
        ExclamationCircleIcon,
    },
    data() {
        return {
            show: false,
        };
    },
    computed: {
        typeIcon() {
            return TYPE_TO_ICON[this.type];
        },
        typeColor() {
            return TYPE_TO_COLOR[this.type];
        },
        type() {
            return this.$page.props.jetstream.flash?.type;
        },
        title() {
            return this.$page.props.jetstream.flash?.title;
        },
        message() {
            return this.$page.props.jetstream.flash?.message;
        },
    },
    watch: {
        "$page.props.jetstream.flash.message": {
            handler(flash) {
                this.show = !!flash;

                if (flash) {
                    this.timeout = setTimeout(() => (this.show = false), 3500);
                }
            },
            deep: true,
            immediate: true,
        },
    },
    beforeUnmount() {
        clearTimeout(this.timeout);
    },
};
</script>
