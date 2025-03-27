<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import BirthdaysCard from '@/Components/BirthdaysCard.vue';
    import StatsCard from '@/Components/StatsCard.vue';
    import AbsentStudents from '@/Components/AbsentStudents.vue';
    import MarriageBirthdayCard from '@/Components/MarriageBirthdayCard.vue';

    defineProps({
        birthdays: Object,
        marriage_birthdays: Object,
        sunday: Object,
        stats: Object,
    });
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 grid grid-cols-1 sm:grid-cols-6">
                        <BirthdaysCard class="col-span-1 sm:col-span-2 mr-0 sm:mr-4" :birthdays :sunday />
                        <StatsCard class="col-span-1 sm:col-span-2 mt-4 sm:mt-0 mr-0 sm:mr-4" :stats />
                        <AbsentStudents class="col-span-1 sm:col-span-2 mt-4 sm:mt-0" :stats />
                        <MarriageBirthdayCard class="col-span-1 sm:col-span-3 mt-4" :marriage_birthdays :sunday />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
