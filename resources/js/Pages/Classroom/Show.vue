<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';

    const props = defineProps({
        classroom: Object,
    });
</script>

<template>
    <Head :title="classroom.name" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <div class="px-4 sm:px-0">
                                <h3 class="text-base/7 font-semibold text-gray-900">Classe dos {{ classroom.name }}</h3>
                                <p class="mt-1 max-w-2xl text-sm/6 text-gray-500">Detalhes da classe</p>
                            </div>
                            <div class="mt-6 border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Descrição</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ classroom.description }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Total de alunos</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ classroom.students_count }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="classroom.teachers.length > 0"
                                        class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">
                                            {{ classroom.teachers.length > 1 ? 'Professores(as)' : 'Professor(a)' }}
                                        </dt>
                                        <dd
                                            v-for="teacher in classroom.teachers"
                                            class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ teacher.name }}
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
