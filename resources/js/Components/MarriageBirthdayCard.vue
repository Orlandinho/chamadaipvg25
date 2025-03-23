<script setup>
    import { differenceInYears, format, parseISO } from 'date-fns';
    import { HeartIcon } from '@heroicons/vue/24/solid/index.js';

    const props = defineProps({
        marriage_birthdays: Object,
        sunday: Object,
    });

    const formattedAge = (date) => {
        let year = differenceInYears(Date.now(), parseISO(date));
        return year > 1 ? year + ' anos: ' : year + ' ano: ';
    };

    const formattedDob = (date) => {
        return format(parseISO(date), 'dd/MM');
    };
</script>

<template>
    <div class="overflow-hidden rounded-xl border border-gray-200">
        <div class="border-b border-gray-900/5 bg-green-50 px-6 py-2">
            <div class="text-sm/6 text-center font-medium text-gray-900">
                Aniversários de Casamento de {{ sunday.previous + ' a ' + sunday.next }}
            </div>
        </div>
        <div class="-my-3 divide-y divide-gray-100 px-6 py-4 text-sm/6">
            <div v-if="marriage_birthdays.length < 1" class="py-2">
                <div class="text-gray-700 text-center">Não há aniversários de casamento nessa semana</div>
            </div>
            <div
                v-else
                v-for="birthday in marriage_birthdays"
                :key="birthday.id"
                class="flex justify-between gap-x-4 py-2">
                <div class="text-gray-500 flex items-center gap-2">
                    {{ birthday.husband.split(' ')[0] + ' ' }}
                    <HeartIcon class="size-3 text-red-600" />
                    {{ ' ' + birthday.wife.split(' ')[0] }}
                </div>
                <div class="text-gray-700">{{ formattedDob(birthday.marriage_date) }}</div>
                <div class="text-gray-700">{{ formattedAge(birthday.marriage_date) + birthday.bodas }}</div>
            </div>
        </div>
    </div>
</template>
