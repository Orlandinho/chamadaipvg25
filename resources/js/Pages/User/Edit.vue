<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link, router } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { ref } from 'vue';
    import { UserCircleIcon } from '@heroicons/vue/24/solid/index.js';
    import imageCompression from 'browser-image-compression';

    const props = defineProps({
        user: Object,
        roles: Object,
        classrooms: Object,
    });

    const form = useForm({
        name: props.user.name,
        email: props.user.email,
        avatar: props.user.avatar,
        role_id: props.user.role_id,
        classroom_id: props.user.classroom?.id ?? '',
    });

    const preview = ref(props.user.avatar ?? '');

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
            form.setError('avatar', 'Houve um problema ao carregar a imagem');
        }
    };

    const submit = () => {
        //form.patch(route('users.update', props.user));

        router.post(route('users.update', props.user), {
            _method: 'patch',
            name: form.name,
            email: form.email,
            avatar: form.avatar,
            role_id: form.role_id,
            classroom_id: form.classroom?.id ?? '',
        });
    };
</script>

<template>
    <Head title="Editando Usuário" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">Colaborador {{ user.name }}</h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Atualização de dados</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-4">
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <UserCircleIcon
                                                v-if="!form.avatar"
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
                                                class="hidden" />
                                            <div class="flex items-center gap-x-3">
                                                <label
                                                    for="avatar"
                                                    class="cursor-pointer rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                                    Selecionar Foto
                                                </label>
                                                <span class="text-gray-500 text-xs">Limite de 250kb</span>
                                            </div>
                                        </div>

                                        <InputError class="mt-2" :message="form.errors.avatar" />
                                    </div>

                                    <div class="col-span-1 sm:col-span-3">
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

                                    <div class="col-span-1 sm:col-span-3">
                                        <InputLabel for="email" value="E-mail" />

                                        <TextInput
                                            id="email"
                                            type="email"
                                            class="mt-1 block w-full"
                                            v-model="form.email"
                                            required />

                                        <InputError class="mt-2" :message="form.errors.email" />
                                    </div>

                                    <div class="col-span-1 sm:col-span-3">
                                        <InputLabel for="role" value="Função" />

                                        <SelectInput
                                            id="role"
                                            :items="roles"
                                            class="mt-1 block w-full"
                                            v-model="form.role_id"
                                            required />

                                        <InputError class="mt-2" :message="form.errors.role_id" />
                                    </div>

                                    <div v-if="form.role_id === 3" class="col-span-1 sm:col-span-3">
                                        <InputLabel for="classroom" value="Classe" />

                                        <SelectInput
                                            id="classroom"
                                            :items="classrooms"
                                            class="mt-1 block w-full"
                                            v-model="form.classroom_id" />

                                        <InputError class="mt-2" :message="form.errors.classroom_id" />
                                    </div>
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-4">
                                <Link
                                    as="button"
                                    :href="route('users.index')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-sm font-semibold text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                                    Cancelar
                                </Link>

                                <PrimaryButton
                                    type="submit"
                                    :class="{ 'opacity-25': form.processing }"
                                    :disabled="form.processing">
                                    Atualizar
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
