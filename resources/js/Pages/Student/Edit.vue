<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link, router } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { format } from 'date-fns';
    import Checkbox from '@/Components/Checkbox.vue';
    import { UserCircleIcon } from '@heroicons/vue/24/solid/index.js';
    import { ref } from 'vue';
    import imageCompression from 'browser-image-compression';
    import { vMaska } from 'maska/vue';

    const props = defineProps({
        student: Object,
        classrooms: Object,
    });

    const form = useForm({
        name: props.student.name,
        dob: format(new Date(props.student.dob), 'yyyy-MM-dd'),
        avatar: null,
        contact: props.student.contact,
        classroom_id: props.student.classroom?.id ?? '',
        inactive: props.student.inactive,
    });

    const preview = ref(props.student.avatar ?? '');

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
        //form.patch(route('students.update', props.student));

        router.post(route('students.update', props.student), {
            _method: 'patch',
            name: form.name,
            dob: form.dob,
            avatar: null,
            classroom_id: form.classroom_id ?? '',
            inactive: form.inactive,
        });
    };
</script>

<template>
    <Head title="Editando Aluno" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">
                                    {{ student.name }}
                                </h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Atualização de dados.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="hidden sm:col-span-4">
                                        <div class="mt-2 flex items-center gap-x-3">
                                            <UserCircleIcon
                                                v-if="!student.avatar"
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

                                    <div class="sm:col-span-3">
                                        <label class="flex items-center">
                                            <Checkbox name="inactive" v-model:checked="form.inactive" />
                                            <span class="ms-2 text-sm font-semibold text-gray-700">
                                                Aluno Inativo
                                            </span>
                                        </label>
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
