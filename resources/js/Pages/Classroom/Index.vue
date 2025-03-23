<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline/index.js';
    import { useConfirm } from '@/Composables/useConfirm.js';

    const props = defineProps({
        classrooms: Object,
    });

    const { confirmation } = useConfirm();

    const deleteClassroom = async (classroom) => {
        if (
            !(await confirmation(
                'Você está prestes a excluir a classe dos ' + classroom.name + '. Deseja prosseguir com essa ação?',
            ))
        ) {
            return false;
        }

        router.delete(route('classrooms.destroy', classroom), {
            preserveScroll: true,
        });
    };

    const limitDescription = (description) => {
        return description.length > 50 ? description.substring(0, 40).trim() + '...' : description;
    };
</script>

<template>
    <Head title="Classes" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold text-gray-900">Classes</h1>
                                <p v-if="classrooms.length < 1" class="mt-2 text-sm text-gray-700">
                                    Ainda não há nenhuma classe registrada. Clique no botão ao lado para criar uma nova
                                    classe.
                                </p>
                                <p v-else class="mt-2 text-sm text-gray-700">Lista das classes e suas descrições.</p>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <Link
                                    :href="route('classrooms.create')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-3 py-1.5 text-sm font-semibold text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                                    Nova Classe
                                </Link>
                            </div>
                        </div>
                        <div v-if="classrooms.length > 0" class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Nome
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Descrição
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Total de Alunos
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Frequencia
                                                    </th>

                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Editar e Excluir</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="classroom in classrooms" :key="classroom.id">
                                                    <td
                                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        <Link
                                                            :href="route('classrooms.show', classroom)"
                                                            class="hover:underline">
                                                            {{ classroom.name }}
                                                        </Link>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ limitDescription(classroom.description) }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ classroom.students_count }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ classroom.frequency_ratio }}
                                                    </td>
                                                    <td
                                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <div class="flex space-x-2">
                                                            <Link :href="route('classrooms.edit', classroom)">
                                                                <PencilSquareIcon
                                                                    class="text-green-600 hover:text-green-400 w-5 h-5" />
                                                                <span class="sr-only">
                                                                    {{ 'Editar ' + classroom.name }}
                                                                </span>
                                                            </Link>
                                                            <button v-if="$page.props.auth.user.role_id === 1">
                                                                <TrashIcon
                                                                    class="text-red-600 hover:text-red-400 w-5 h-5"
                                                                    @click="deleteClassroom(classroom)" />
                                                                <span class="sr-only">
                                                                    {{ 'Excluir ' + classroom.name }}
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
