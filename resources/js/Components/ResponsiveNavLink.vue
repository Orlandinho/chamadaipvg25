<script setup>
    import { computed } from 'vue';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        href: {
            type: String,
            required: true,
        },
        active: {
            type: Boolean,
        },
    });

    const classes = computed(() =>
        props.active
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-green-600 text-start text-base font-medium text-green-700 bg-green-50 focus:outline-none focus:text-green-800 focus:bg-green-100 focus:border-green-700 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-green-50 hover:border-green-300 focus:outline-none focus:text-gray-800 focus:bg-green-50 focus:border-green-300 transition duration-150 ease-in-out',
    );
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
