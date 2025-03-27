<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { differenceInYears, format } from 'date-fns';

    const props = defineProps({
        student: Object,
    });

    const formattedDob = (date) => {
        return format(new Date(date), 'dd/MM/yyyy');
    };

    const formattedAge = (date) => {
        let yearOld = differenceInYears(Date.now(), new Date(date));

        return yearOld > 1 ? yearOld + ' anos' : yearOld + ' ano';
    };
</script>

<template>
    <Head :title="student.name" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <div class="px-4 sm:px-0">
                                <h3 class="text-base/7 font-semibold text-gray-900">{{ student.name }}</h3>
                                <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Detalhes do(a) aluno(a)</p>
                            </div>
                            <div class="mt-6 border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Nome</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ student.name }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Data de Nascimento</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ formattedDob(student.dob) }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Idade</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ formattedAge(student.dob) }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Classe</dt>
                                        <dd
                                            class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0"
                                            :class="student.classroom ? 'text-gray-600' : 'text-red-400'">
                                            {{ student.classroom ? student.classroom.name : 'Sem classe definida' }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Aulas/Frequencia</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ student.classes + ' / ' + student.frequency }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Taxa de frequencia</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ student.frequency_ratio }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
