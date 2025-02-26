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
        class="rounded-md border-gray-300 shadow-sm py-1 sm:text-sm/6 focus:border-green-800 focus:ring-green-800"
        v-model="model"
        ref="textarea" />
</template>
