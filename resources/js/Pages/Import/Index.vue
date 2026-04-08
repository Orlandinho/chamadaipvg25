<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { DocumentIcon } from '@heroicons/vue/24/solid/index.js';
    import { ref } from 'vue';

    const csvFile = ref(null);

    const handleStudentsCSV = (e) => {
        csvFile.value = e.target.files[0];
    };

    const handleCouplesCSV = (e) => {
        csvFile.value = e.target.files[0];
    };

    const sendStudentsCSV = () => {
        router.post(
            route('import.students'),
            { students_csv: csvFile.value },
            {
                forceFormData: true,
                onSuccess: (data) => {
                    csvFile.value = 'Dados inseridos';
                },
            },
        );
    };

    const sendCouplesCSV = () => {
        router.post(
            route('import.couples'),
            { students_csv: csvFile.value },
            {
                forceFormData: true,
                onSuccess: (data) => {
                    csvFile.value = 'Dados inseridos';
                },
            },
        );
    };
</script>

<template>
    <Head title="Importar Dados" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="sm:items-center">
                            <div class="sm:flex-auto">
                                <h1 class="text-base font-semibold text-gray-900">Importar Arquivos</h1>
                                <p class="mt-2 text-sm text-gray-700">
                                    A importação através de um arquivo .csv para inserção massiva dos dados deve seguir
                                    uma ordem no arquivo para funcionar corretamente. Dependendo da quantidade de dados
                                    o processo pode demorar um pouco.
                                </p>
                                <p class="mt-4 text-sm text-gray-700">
                                    <span class="font-bold">Para os Alunos: </span>é necessário que as colunas sigam
                                    essa ordem:
                                    <span class="font-bold">Nome, Sala, Data de Nascimento e Contato/WhatsApp</span>.
                                    Nome e Data de Nascimento são obrigatórios! Se a sala não foi criada ainda ela será
                                    criada nesse processo, apenas certifique-se de que não há erros de gramática no nome
                                    da sala. Se em um aluno o nome da sala estiver
                                    <span class="text-red-600">Cordeirinhos</span> e em outro aluno estiver
                                    <span class="text-red-600">Cordeirinho</span> então será criada uma sala para cada
                                    nome. É importante que os nomes sejam iguais. Não se preocupe se os dados estiverem
                                    sendo inseridos todos em letras maiúsculas ou minúsculas, pois durante o processo de
                                    inserção isso será automaticamente normalizado. A Data de Nascimento deve estar
                                    formatada da seguinte maneira: DD/MM/AAAA. E o Contato/WhatsApp deve incluir DDD:
                                    (11) 91234-5678/8765-4321.
                                </p>
                                <p class="mt-4 text-sm text-gray-700">
                                    <span class="font-bold">Para os Casais: </span>mesma regra acima, porém com menos
                                    campos. Apenas
                                    <span class="font-bold">Nome do Esposo, Nome da Esposa e Data de Casamento</span>,
                                    seguindo essa ordem sempre. A Data de Casamento segue a mesma formatação que foi
                                    aplicada na Data de Nascimento acima. Todos os campos são obrigatórios!
                                </p>
                            </div>

                            <div class="mt-6">
                                <div class="text-lg mb-4 text-gray-800">
                                    {{ csvFile ? csvFile.name : 'Importar Dados dos Alunos' }}
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <DocumentIcon
                                        :class="csvFile ? 'text-green-400' : 'text-gray-300'"
                                        class="size-8"
                                        aria-hidden="true" />
                                    <input
                                        id="students_scv"
                                        @input="(e) => handleStudentsCSV(e)"
                                        type="file"
                                        accept=".csv"
                                        class="hidden" />
                                    <button
                                        v-if="csvFile"
                                        @click="sendStudentsCSV"
                                        class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Enviar
                                    </button>
                                    <label
                                        v-else
                                        for="students_scv"
                                        class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Selecionar Arquivo .csv
                                    </label>
                                </div>
                            </div>

                            <div class="mt-6">
                                <div class="text-lg mb-4 text-gray-800">
                                    {{ csvFile ? csvFile.name : 'Importar Dados dos Casais' }}
                                </div>
                                <div class="flex items-center gap-x-3">
                                    <DocumentIcon
                                        :class="csvFile ? 'text-green-400' : 'text-gray-300'"
                                        class="size-8"
                                        aria-hidden="true" />
                                    <input
                                        id="couples_scv"
                                        @input="(e) => handleCouplesCSV(e)"
                                        type="file"
                                        accept=".csv"
                                        class="hidden" />
                                    <button
                                        v-if="csvFile"
                                        @click="sendCouplesCSV"
                                        class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Enviar
                                    </button>
                                    <label
                                        v-else
                                        for="couples_scv"
                                        class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                        Selecionar Arquivo .csv
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
