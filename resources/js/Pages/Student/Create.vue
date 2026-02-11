<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link, router } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import { DocumentIcon, UserCircleIcon } from '@heroicons/vue/24/solid/index.js';
    import { vMaska } from 'maska/vue';
    import { ref } from 'vue';
    import imageCompression from 'browser-image-compression';

    defineProps({
        classrooms: Object,
    });

    const form = useForm({
        name: '',
        dob: '',
        avatar: null,
        contact: '',
        classroom_id: '',
    });

    const preview = ref('');

    const loaded = ref(null);

    const handleCSV = (e) => {
        loaded.value = e.target.files[0];
    };

    const sendCSV = () => {
        router.post(
            route('import.students'),
            { students_csv: loaded.value },
            {
                forceFormData: true,
                onSuccess: (data) => {
                    loaded.value = 'Dados inseridos';
                },
            },
        );
    };

    const handleImage = async (e) => {
        const file = e.target.files[0];
        const compressedFile = ref(null);

        const options = {
            maxSizeMB: 0.25, // (Max size in MB)
            maxWidthOrHeight: 400, // Resize width/height
            useWebWorker: true, // Improves performance
        };

        try {
            const compressedBlob = await imageCompression(file, options);
            compressedFile.value = new File([compressedBlob], file.name, {
                type: compressedBlob.type,
            });

            preview.value = URL.createObjectURL(compressedFile.value);
            form.avatar = compressedFile.value;
        } catch (error) {
            form.setError('avatar', 'Houve um problema ao carregar o arquivo');
        }
    };

    const submit = () => {
        form.post(route('students.store'));
    };
</script>

<template>
    <Head title="Novo Aluno" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="$page.props.auth.user.role_id === 1" class="mb-4 border-b border-gray-200 pb-6">
                            <div class="text-sm mb-4 text-gray-500">
                                {{ loaded ? loaded.name : 'Importar dados dos alunos' }}
                            </div>
                            <div class="flex items-center gap-x-3">
                                <DocumentIcon
                                    :class="loaded ? 'text-green-400' : 'text-gray-300'"
                                    class="size-8"
                                    aria-hidden="true" />
                                <input
                                    id="students_scv"
                                    @input="(e) => handleCSV(e)"
                                    type="file"
                                    accept=".csv"
                                    class="hidden" />
                                <button
                                    v-if="loaded"
                                    @click="sendCSV"
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
                        <form @submit.prevent="submit">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">Informações do(a) Aluno(a)</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Nome e data de nascimento são obrigatórios.</p>
                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="hidden sm:col-span-4">
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <UserCircleIcon
                                                v-if="!preview"
                                                class="size-14 text-gray-300"
                                                aria-hidden="true" />
                                            <img
                                                v-else
                                                class="inline-block size-14 rounded-full"
                                                :src="preview"
                                                alt="Avatar" />
                                            <input
                                                id="avatar"
                                                @input="(e) => handleImage(e)"
                                                type="file"
                                                accept=".png, .jpeg, .jpg"
                                                class="hidden" />
                                            <label
                                                for="avatar"
                                                class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                Selecionar Foto
                                            </label>
                                        </div>
                                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                            {{ form.progress.percentage }}%
                                        </progress>
                                        <InputError class="mt-2" :message="form.errors.avatar" />
                                    </div>
                                    <div class="sm:col-span-4">
                                        <InputLabel for="name" value="Nome" />

                                        <TextInput
                                            id="name"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.name"
                                            required
                                            autofocus />

                                        <InputError class="mt-2" :message="form.errors.name" />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel for="dob" value="Data de Nascimento" />

                                        <TextInput
                                            id="dob"
                                            type="date"
                                            class="mt-1 block w-full"
                                            v-model="form.dob"
                                            required />

                                        <InputError class="mt-2" :message="form.errors.dob" />
                                    </div>

                                    <div class="sm:col-span-4">
                                        <InputLabel for="classroom" value="Classe" />

                                        <SelectInput
                                            id="classroom"
                                            :items="classrooms"
                                            class="mt-1 block w-full"
                                            v-model="form.classroom_id" />

                                        <InputError class="mt-2" :message="form.errors.classroom_id" />
                                    </div>

                                    <div class="sm:col-span-2">
                                        <InputLabel for="contact" value="Contato" />

                                        <TextInput
                                            id="contact"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="(11) 91234-5678/9123-4567"
                                            v-maska="{ mask: ['(##) ####-####', '(##) #####-####'] }"
                                            maxlength="15"
                                            v-model="form.contact" />

                                        <InputError class="mt-2" :message="form.errors.contact" />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-4">
                                <Link
                                    as="button"
                                    :href="route('students.index')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                                    Cancelar
                                </Link>

                                <PrimaryButton
                                    type="submit"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Salvar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
