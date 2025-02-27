<script setup>
    import { onUnmounted, ref, watchEffect } from 'vue';
    import { CheckCircleIcon, XCircleIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/outline';
    import { XMarkIcon } from '@heroicons/vue/20/solid';
    import { usePage } from '@inertiajs/vue3';

    const show = ref(false);

    const page = usePage();

    const message = ref('');

    const type = ref('');

    const title = ref('');

    const timeout = ref(null);

    watchEffect(async () => {
        title.value = page.props.alert?.title;
        type.value = page.props.alert?.type;
        message.value = page.props.alert?.message || '';
        show.value = true;

        clearTimeout(timeout.value);
        timeout.value = setTimeout(() => (show.value = false), 5000);
    });

    const iconList = {
        success: CheckCircleIcon,
        failure: XCircleIcon,
        attention: ExclamationTriangleIcon,
    };

    const iconColor = {
        success: 'text-green-500',
        failure: 'text-red-500',
        attention: 'text-yellow-500',
    };

    onUnmounted(() => clearTimeout(timeout.value));
</script>

<template>
    <!-- Global notification live region, render this permanently at the end of the document -->
    <div aria-live="assertive" class="pointer-events-none fixed inset-0 flex items-end px-4 py-6 sm:items-start sm:p-6">
        <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
            <!-- Notification panel, dynamically insert this into the live region when it needs to be displayed -->
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0">
                <div
                    v-if="show && message"
                    class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="shrink-0">
                                <component
                                    :is="iconList[type]"
                                    :class="iconColor[type]"
                                    class="size-6"
                                    aria-hidden="true" />
                            </div>
                            <div class="ml-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900">{{ title }}</p>
                                <p class="mt-1 text-sm text-gray-500">{{ message }}</p>
                            </div>
                            <div class="ml-4 flex shrink-0">
                                <button
                                    type="button"
                                    @click="show = false"
                                    class="inline-flex rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <span class="sr-only">Close</span>
                                    <XMarkIcon class="size-5" aria-hidden="true" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
