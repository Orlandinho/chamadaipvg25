<script setup>
    import { onMounted, ref } from 'vue';

    const model = defineModel({
        type: String,
        required: true,
    });

    const textarea = ref(null);

    onMounted(() => {
        if (textarea.value.hasAttribute('autofocus')) {
            textarea.value.focus();
        }
    });

    defineExpose({ focus: () => textarea.value.focus() });
</script>

<template>
    <textarea
        class="rounded-md border-gray-300 shadow-sm py-1 sm:text-sm/6 focus:border-indigo-500 focus:ring-indigo-500"
        v-model="model"
        ref="textarea" />
</template>
