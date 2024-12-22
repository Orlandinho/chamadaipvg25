<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline/index.js';
    import { useConfirm } from '@/Composables/useConfirm.js';
    import { format, differenceInYears } from 'date-fns';

    const props = defineProps({
        students: Object,
    });

    const { confirmation } = useConfirm();

    const formattedDob = (date) => {
        return format(new Date(date), 'dd/MM/yyyy');
    };

    const formattedAge = (date) => {
        let yearOld = differenceInYears(Date.now(), new Date(date));

        return yearOld > 1 ? yearOld + ' anos' : yearOld + ' ano';
    };

    const deleteStudent = async (student) => {
        if (
            !(await confirmation(
                'Você está prestes a excluir as informações do(a) aluno(a) ' +
                    student.name +
                    '. Deseja prosseguir com essa ação?',
            ))
        ) {
            return false;
        }

        router.delete(route('students.destroy', student), {
            preserveScroll: true,
        });
    };
</script>

<template>
    <Head title="Alunos" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold text-gray-900">Alunos</h1>
                                <p v-if="students.length < 1" class="mt-2 text-sm text-gray-700">
                                    Ainda não há nenhum aluno registrado. Clique no botão ao lado para registrar um novo
                                    aluno
                                </p>
                                <p v-else class="mt-2 text-sm text-gray-700">Lista dos alunos.</p>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <Link
                                    :href="route('students.create')"
                                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Novo Aluno
                                </Link>
                            </div>
                        </div>
                        <div v-if="students.length > 0" class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Nome
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Data Nasc
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Idade
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Classe
                                                    </th>

                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Editar e Excluir</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="student in students" :key="student.id">
                                                    <td
                                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        <Link
                                                            :href="route('students.show', student)"
                                                            class="hover:underline">
                                                            {{ student.name }}
                                                        </Link>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ formattedDob(student.dob) }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ formattedAge(student.dob) }}
                                                    </td>
                                                    <td
                                                        class="whitespace-nowrap px-3 py-4 text-sm"
                                                        :class="student.classroom ? 'text-gray-500' : 'text-red-400'">
                                                        {{
                                                            student.classroom
                                                                ? student.classroom.name
                                                                : 'Sem classe definida'
                                                        }}
                                                    </td>
                                                    <td
                                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <div class="flex space-x-2">
                                                            <Link :href="route('students.edit', student)">
                                                                <PencilSquareIcon
                                                                    class="text-green-600 hover:text-green-400 w-5 h-5" />
                                                                <span class="sr-only">
                                                                    {{ 'Editar ' + student.name }}
                                                                </span>
                                                            </Link>
                                                            <button>
                                                                <TrashIcon
                                                                    class="text-red-600 hover:text-red-400 w-5 h-5"
                                                                    @click="deleteStudent(student)" />
                                                                <span class="sr-only">
                                                                    {{ 'Excluir ' + student.name }}
                                                                </span>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
