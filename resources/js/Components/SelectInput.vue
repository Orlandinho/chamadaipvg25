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
    });
</script>

<template>
    <select
        v-model="model"
        class="rounded-md py-1 pl-3 pr-8 border-gray-300 shadow-sm text-sm/6 focus:border-indigo-500 focus:ring-indigo-500">
        <option disabled value="0">Selecione a função</option>
        <option v-for="item in items" :key="item.id" :selected="model === item.id" :value="item.id">
            {{ item.name }}
        </option>
    </select>
</template>
