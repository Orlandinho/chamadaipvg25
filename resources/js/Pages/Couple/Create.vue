<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link, router } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { DocumentIcon, UserCircleIcon } from '@heroicons/vue/24/solid/index.js';
    import { ref } from 'vue';
    import imageCompression from 'browser-image-compression';

    const form = useForm({
        husband: '',
        husband_avatar: null,
        wife: '',
        wife_avatar: null,
        marriage_date: '',
    });

    const preview_husband = ref('');
    const preview_wife = ref('');

    const loaded = ref(null);

    const handleCSV = (e) => {
        loaded.value = e.target.files[0];
    };

    const sendCSV = () => {
        router.post(
            route('import.couples'),
            { couples_csv: loaded.value },
            {
                forceFormData: true,
                onSuccess: (data) => {
                    loaded.value = 'Dados inseridos';
                },
            },
        );
    };

    const handleHusbandImage = async (e) => {
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

            preview_husband.value = URL.createObjectURL(compressedFile.value);
            form.husband_avatar = compressedFile.value;
        } catch (error) {
            form.setError('husband_avatar', 'Houve um problema ao carregar a imagem');
        }
    };

    const handleWifeImage = async (e) => {
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

            preview_wife.value = URL.createObjectURL(compressedFile.value);
            form.wife_avatar = compressedFile.value;
        } catch (error) {
            form.setError('wife_avatar', 'Houve um problema ao carregar a imagem');
        }
    };

    const submit = () => {
        form.post(route('couples.store'));
    };
</script>

<template>
    <Head title="Novo Casal" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="$page.props.auth.user.role_id === 1" class="mb-4 border-b border-gray-200 pb-6">
                            <div class="text-sm mb-4 text-gray-500">
                                {{ loaded ? loaded.name : 'Importar dados dos casais' }}
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
                                <h2 class="text-base/7 font-semibold text-gray-900">Informações do Casal</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Nomes e data de casamento são obrigatórios!</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="hidden sm:col-span-3">
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <UserCircleIcon
                                                v-if="!preview_husband"
                                                class="size-14 text-gray-300"
                                                aria-hidden="true" />
                                            <img
                                                v-else
                                                class="inline-block size-14 rounded-full"
                                                :src="preview_husband"
                                                alt="Avatar" />
                                            <input
                                                id="husband_avatar"
                                                @input="(e) => handleHusbandImage(e)"
                                                accept=".png, .jpeg, .jpg"
                                                type="file"
                                                class="hidden" />
                                            <label
                                                for="husband_avatar"
                                                class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                Foto do Esposo
                                            </label>
                                        </div>
                                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                            {{ form.progress.percentage }}%
                                        </progress>
                                        <InputError class="mt-2" :message="form.errors.husband_avatar" />
                                    </div>

                                    <div class="hidden sm:col-span-3">
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <UserCircleIcon
                                                v-if="!preview_wife"
                                                class="size-14 text-gray-300"
                                                aria-hidden="true" />
                                            <img
                                                v-else
                                                class="inline-block size-14 rounded-full"
                                                :src="preview_wife"
                                                alt="Avatar" />
                                            <input
                                                id="wife_avatar"
                                                @input="(e) => handleWifeImage(e)"
                                                accept=".png, .jpeg, .jpg"
                                                type="file"
                                                class="hidden" />
                                            <label
                                                for="wife_avatar"
                                                class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                Foto da Esposa
                                            </label>
                                        </div>
                                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                                            {{ form.progress.percentage }}%
                                        </progress>
                                        <InputError class="mt-2" :message="form.errors.wife_avatar" />
                                    </div>
                                    <div class="sm:col-span-3">
                                        <InputLabel for="husband" value="Esposo" />

                                        <TextInput
                                            id="husband"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.husband"
                                            required
                                            autofocus />

                                        <InputError class="mt-2" :message="form.errors.husband" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <InputLabel for="wife" value="Esposa" />

                                        <TextInput
                                            id="wife"
                                            type="text"
                                            class="mt-1 block w-full"
                                            v-model="form.wife"
                                            required />

                                        <InputError class="mt-2" :message="form.errors.wife" />
                                    </div>

                                    <div class="sm:col-span-3">
                                        <InputLabel for="marriage_date" value="Data de Casamento" />

                                        <TextInput
                                            id="marriage_date"
                                            type="date"
                                            class="mt-1 block w-full"
                                            v-model="form.marriage_date"
                                            required />

                                        <InputError class="mt-2" :message="form.errors.marriage_date" />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-4">
                                <Link
                                    as="button"
                                    :href="route('couples.index')"
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
