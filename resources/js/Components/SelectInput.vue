<script setup>
    const model = defineModel({
        type: Number,
        required: true,
    });

    const props = defineProps({
        items: {
            type: Object,
            required: true,
        },
        check: {
            type: Boolean,
        },
        newLine: {
            type: String,
        },
    });
</script>

<template>
    <select
        v-model="model"
        class="rounded-md py-1 pl-3 pr-8 border-gray-300 shadow-sm text-sm/6 focus:border-green-800 focus:ring-green-800">
        <option disabled value="">Selecione uma opção</option>
        <option v-for="item in items" :key="item.id" :selected="model === item.id" :value="item.id">
            {{ item.name }}
        </option>
    </select>
</template>
