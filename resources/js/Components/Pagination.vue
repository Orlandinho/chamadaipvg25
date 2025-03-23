<script setup>
    import { ArrowLongLeftIcon, ArrowLongRightIcon } from '@heroicons/vue/20/solid';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        meta: Object,
        links: Object,
    });
</script>

<template>
    <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
        <div class="-mt-px flex w-0 flex-1">
            <Link
                :href="links.prev || 'null'"
                :disabled="!links.prev"
                preserve-scroll
                :class="{
                    'text-gray-500 hover:border-gray-300 hover:text-gray-700': links.prev,
                    'text-gray-300 cursor-default': !links.prev,
                }"
                class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium">
                <ArrowLongLeftIcon
                    class="mr-3 size-5"
                    :class="links.prev ? 'text-gray-400' : 'text-gray-300'"
                    aria-hidden="true" />
                Anterior
            </Link>
        </div>
        <div class="hidden md:-mt-px md:flex">
            <template v-for="link in meta.links">
                <Link
                    v-if="!link.label.includes('aquo')"
                    :href="link.url"
                    v-html="link.label"
                    preserve-scroll
                    :class="{
                        'border-green-600 text-green-700': link.active,
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': !link.active,
                    }"
                    class="inline-flex items-center border-t-2 px-4 pt-4 text-sm font-medium" />
            </template>
        </div>
        <div class="-mt-px flex w-0 flex-1 justify-end">
            <Link
                :href="links.next ?? 'null'"
                :disabled="!links.next"
                preserve-scroll
                :class="{
                    'text-gray-500 hover:border-gray-300 hover:text-gray-700': links.next,
                    'text-gray-300 cursor-default': !links.next,
                }"
                class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium">
                Próximo
                <ArrowLongRightIcon
                    class="ml-3 size-5"
                    :class="links.next ? 'text-gray-400' : 'text-gray-300'"
                    aria-hidden="true" />
            </Link>
        </div>
    </nav>
</template>
