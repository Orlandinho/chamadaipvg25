<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link, router } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { format } from 'date-fns';
    import { ref } from 'vue';
    import { UserCircleIcon } from '@heroicons/vue/24/solid/index.js';
    import imageCompression from 'browser-image-compression';

    const props = defineProps({
        couple: Object,
    });

    const form = useForm({
        husband: props.couple.husband,
        husband_avatar: props.couple.husband_avatar,
        wife: props.couple.wife,
        wife_avatar: props.couple.wife_avatar,
        marriage_date: format(new Date(props.couple.marriage_date), 'yyyy-MM-dd'),
    });

    const preview_husband = ref(props.couple.husband_avatar ?? '');
    const preview_wife = ref(props.couple.wife_avatar ?? '');

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
        //form.patch(route('couples.update', props.couple));

        router.post(route('couples.update', props.couple), {
            _method: 'patch',
            husband: form.husband,
            husband_avatar: form.husband_avatar,
            wife: form.wife,
            wife_avatar: form.wife_avatar,
            marriage_date: form.marriage_date,
        });
    };
</script>

<template>
    <Head title="Editando Casal" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">
                                    {{ couple.husband.split(' ')[0] + ' e ' + couple.wife.split(' ')[0] }}
                                </h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Atualização de dados.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-3">
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <UserCircleIcon
                                                v-if="!form.husband_avatar"
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
                                                type="file"
                                                accept=".png, .jpeg, .jpg"
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

                                    <div class="sm:col-span-3">
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
