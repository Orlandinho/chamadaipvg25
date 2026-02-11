<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import { differenceInYears, format, parseISO } from 'date-fns';
    import DefaultAvatar from '@/Components/DefaultAvatar.vue';

    const props = defineProps({
        student: Object,
    });

    const formattedDob = (date) => {
        return format(parseISO(date), 'dd/MM/yyyy');
    };

    const formattedAge = (date) => {
        let yearOld = differenceInYears(Date.now(), parseISO(date));

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
                                <div class="flex items-center space-x-4">
                                    <DefaultAvatar :person="student" size="size-12" text-size="text-2xl" />
                                    <span class="text-base/7 font-semibold text-gray-900">
                                        {{ student.name }}
                                    </span>
                                </div>
                                <p class="mt-6 max-w-2xl text-sm/6 text-gray-500">Detalhes do(a) aluno(a)</p>
                            </div>
                            <div class="mt-6 border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
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
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Classe</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ student.classroom ? student.classroom.name : 'Sem classe definida' }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Contato</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ student.contact ? student.contact : 'Sem contato definido' }}
                                        </dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
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
