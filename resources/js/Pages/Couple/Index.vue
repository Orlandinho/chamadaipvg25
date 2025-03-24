<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline/index.js';
    import { useConfirm } from '@/Composables/useConfirm.js';
    import { format, differenceInYears } from 'date-fns';
    import Pagination from '@/Components/Pagination.vue';
    import CoupleAvatar from '@/Components/CoupleAvatar.vue';

    const props = defineProps({
        couples: Object,
    });

    const { confirmation } = useConfirm();

    const formattedDate = (date) => {
        return format(new Date(date), 'dd/MM/yyyy');
    };

    const formattedAge = (date) => {
        let yearOld = differenceInYears(Date.now(), new Date(date));

        if (yearOld === 0) {
            return '';
        }

        return yearOld > 1 ? yearOld + ' anos: ' : yearOld + ' ano: ';
    };

    const deleteCouple = async (couple) => {
        if (
            !(await confirmation(
                'Você está prestes a excluir as informações do casal ' +
                    couple.husband.split(' ')[0] +
                    ' e ' +
                    couple.wife.split(' ')[0] +
                    '. Deseja prosseguir com essa ação?',
            ))
        ) {
            return false;
        }

        router.delete(route('couples.destroy', couple), {
            preserveScroll: true,
        });
    };
</script>

<template>
    <Head title="Casais" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold text-gray-900">Casais</h1>
                                <p v-if="couples.data.length < 1" class="mt-2 text-sm text-gray-700">
                                    Ainda não há nenhum casal registrado. Clique no botão ao lado para registrar um novo
                                    casal
                                </p>
                                <p v-else class="mt-2 text-sm text-gray-700">Lista dos casais.</p>
                            </div>
                            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                <Link
                                    :href="route('couples.create')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-3 py-1.5 text-sm font-semibold text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                                    Novo Casal
                                </Link>
                            </div>
                        </div>
                        <div v-if="couples.data.length > 0" class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="py-3.5 pl-4 pr-3 text-left text-sm min-w-[130px] font-semibold text-gray-900 sm:pl-6">
                                                        Esposo
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-center text-sm font-semibold min-w-[150px] text-gray-900">
                                                        <span class="sr-only">Fotos do casal</span>
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Esposa
                                                    </th>
                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Data de Casamento
                                                    </th>

                                                    <th
                                                        scope="col"
                                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                                                        Bodas
                                                    </th>

                                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                                        <span class="sr-only">Editar e Excluir</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="couple in couples.data" :key="couple.id">
                                                    <td
                                                        class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-700 sm:pl-6">
                                                        {{ couple.husband }}
                                                    </td>
                                                    <td class="whitespace-nowrap pr-3 py-4 text-sm text-gray-700">
                                                        <CoupleAvatar :couple="couple" />
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                                        {{ couple.wife }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ formattedDate(couple.marriage_date) }}
                                                    </td>
                                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                        {{ formattedAge(couple.marriage_date) + couple.bodas }}
                                                    </td>
                                                    <td
                                                        class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                        <div class="flex space-x-2">
                                                            <Link :href="route('couples.edit', couple)">
                                                                <PencilSquareIcon
                                                                    class="text-green-600 hover:text-green-400 w-5 h-5" />
                                                                <span class="sr-only">
                                                                    {{ 'Editar ' + couple.id }}
                                                                </span>
                                                            </Link>
                                                            <button>
                                                                <TrashIcon
                                                                    class="text-red-600 hover:text-red-400 w-5 h-5"
                                                                    @click="deleteCouple(couple)" />
                                                                <span class="sr-only">
                                                                    {{ 'Excluir ' + couple.id }}
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
                    v-if="couples.meta.links.length > 3"
                    class="mt-2"
                    :meta="couples.meta"
                    :links="couples.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
