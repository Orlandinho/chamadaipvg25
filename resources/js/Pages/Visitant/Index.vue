<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline/index.js';
    import { useConfirm } from '@/Composables/useConfirm.js';
    import { format, differenceInYears } from 'date-fns';
    import Pagination from '@/Components/Pagination.vue';

    const props = defineProps({
        visitants: Object,
    });

    const { confirmation } = useConfirm();

    const deleteVisitant = async (visitant) => {
        if (
            !(await confirmation(
                'Você está prestes a excluir as informações do(a) visitante ' +
                    visitant.name +
                    '. Deseja prosseguir com essa ação?',
            ))
        ) {
            return false;
        }

        router.delete(route('visitants.destroy', visitant), {
            preserveScroll: true,
        });
    };
</script>

<template>
    <Head title="Visitantes" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold text-gray-900">Visitantes</h1>
                                <p v-if="visitants.data.length < 1" class="mt-2 text-sm text-gray-700">
                                    Ainda não há nenhum visitante registrado. Clique no botão ao lado para registrar um
                                    novo visitante
                                </p>
                                <p v-else class="mt-2 text-sm text-gray-700">Lista dos visitantes.</p>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <Link
                                    :href="route('visitants.create')"
                                    class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    Novo Visitante
                                </Link>
                            </div>
                        </div>
                        <div v-if="visitants.data.length > 0" class="mt-8 flow-root">
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
                                                        Data da visita
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
                                                <tr v-for="visitant in visitants.data" :key="visitant.id">
                                                    <td
                                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        <Link
                                                            :href="route('visitants.show', visitant)"
                                                            class="hover:underline">
                                                            {{ visitant.name }}
                                                        </Link>
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ visitant.visit }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ visitant.classroom.name }}
                                                    </td>
                                                    <td
                                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <div class="flex space-x-2">
                                                            <Link :href="route('visitants.edit', visitant)">
                                                                <PencilSquareIcon
                                                                    class="text-green-600 hover:text-green-400 w-5 h-5" />
                                                                <span class="sr-only">
                                                                    {{ 'Editar ' + visitant.name }}
                                                                </span>
                                                            </Link>
                                                            <button>
                                                                <TrashIcon
                                                                    class="text-red-600 hover:text-red-400 w-5 h-5"
                                                                    @click="deleteVisitant(visitant)" />
                                                                <span class="sr-only">
                                                                    {{ 'Excluir ' + visitant.name }}
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
                <Pagination
                    v-if="visitants.meta.links.length > 3"
                    class="mt-2"
                    :meta="visitants.meta"
                    :links="visitants.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
