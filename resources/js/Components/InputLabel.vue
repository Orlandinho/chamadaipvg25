<script setup>
    defineProps({
        value: {
            type: String,
        },
    });
</script>

<template>
    <label class="block text-sm font-medium mb-2 text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
