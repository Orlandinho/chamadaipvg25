<script setup>
    import { onMounted, ref } from 'vue';

    const model = defineModel({
        type: String,
        required: true,
    });

    const input = ref(null);

    onMounted(() => {
        if (input.value.hasAttribute('autofocus')) {
            input.value.focus();
        }
    });

    defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-md border-gray-300 shadow-sm py-1 sm:text-sm/6 focus:border-green-800 focus:ring-green-800"
        v-model="model"
        ref="input" />
</template>
