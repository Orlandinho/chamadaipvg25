<script setup>
    import { format, parseISO } from 'date-fns';

    const props = defineProps({
        classroom: Object,
    });

    const formattedDob = (date) => {
        return format(parseISO(date), 'dd/MM');
    };
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="border-b border-gray-900/5 bg-green-50 px-6 py-2">
            <div class="text-sm/6 font-medium text-center text-gray-900">
                {{ classroom.name }}
            </div>
        </div>
        <div class="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm/6">
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Descrição:</div>
                <div class="text-gray-700 line-clamp-1 hover:line-clamp-none">{{ classroom.description }}</div>
            </div>
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Alunos ativos:</div>
                <div class="text-gray-700">{{ classroom.students_count ?? '0' }}</div>
            </div>
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Visitas totais:</div>
                <div class="text-gray-700">{{ classroom.visitants_count ?? '0' }}</div>
            </div>
            <div class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">Frequência:</div>
                <div class="text-gray-700">{{ classroom.frequency_ratio }}</div>
            </div>
        </div>
    </div>
</template>
