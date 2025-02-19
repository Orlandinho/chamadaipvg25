<script setup>
    const props = defineProps({
        stats: Object,
    });
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="border-b border-gray-900/5 bg-gray-50 px-6 py-2">
            <div class="text-sm/6 font-medium text-center text-gray-900">Números totais da Escola Dominical</div>
        </div>
        <div class="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm/6">
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Total de alunos</div>
                <div class="text-gray-700">{{ stats.total_students }}</div>
            </div>
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Frequencia Geral</div>
                <div class="text-gray-700">{{ stats.frequency }}</div>
            </div>
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Visitas Totais</div>
                <div class="text-gray-700">{{ stats.total_visits }}</div>
            </div>
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Visitantes Únicos</div>
                <div class="text-gray-700">{{ stats.total_unique_visits }}</div>
            </div>
        </div>
    </div>
</template>
