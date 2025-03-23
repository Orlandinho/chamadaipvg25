<script setup>
    const props = defineProps({
        stats: Object,
    });
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="border-b border-gray-900/5 bg-green-50 px-6 py-2">
            <div class="text-sm/6 font-medium text-center text-gray-900">Alunos ausentes há +1 mês</div>
        </div>
        <div class="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm/6">
            <div v-if="stats.absent_students.length < 1" class="mt-3">
                <div class="text-gray-700 text-center">Não há alunos nessas condições</div>
            </div>
            <div v-else v-for="student in stats.absent_students" class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">{{ student.name }}</div>
                <div class="text-gray-700">{{ student.classroom.name }}</div>
            </div>
        </div>
    </div>
</template>
