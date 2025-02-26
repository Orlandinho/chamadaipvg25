<script setup>
    import { format, parseISO } from 'date-fns';

    const props = defineProps({
        birthdays: Object,
        sunday: Object,
    });

    const formattedDob = (date) => {
        return format(parseISO(date), 'dd/MM');
    };
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="border-b border-gray-900/5 bg-green-100 px-6 py-2">
            <div class="text-sm/6 font-medium text-center text-gray-900">
                Aniversariantes de {{ sunday.previous + ' a ' + sunday.next }}
            </div>
        </div>
        <div class="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm/6">
            <div v-if="birthdays.length < 1" class="mt-3">
                <div class="text-gray-700 text-center">Não há aniversariantes nessa semana</div>
            </div>
            <div v-else v-for="birthday in birthdays" :key="birthday.id" class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500">{{ birthday.name }}</div>
                <div class="text-gray-700">{{ formattedDob(birthday.dob) }}</div>
            </div>
        </div>
    </div>
</template>
