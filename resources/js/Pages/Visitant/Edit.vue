<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import SelectInput from '@/Components/SelectInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { format } from 'date-fns';

    const props = defineProps({
        visitant: Object,
        classrooms: Object,
    });

    const form = useForm({
        name: props.visitant.name,
        classroom_id: props.visitant.classroom_id,
    });

    const submit = () => {
        form.patch(route('visitants.update', props.visitant));
    };
</script>

<template>
    <Head title="Editando Visitante" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form @submit.prevent="submit">
                            <div class="border-b border-gray-900/10 pb-12">
                                <h2 class="text-base/7 font-semibold text-gray-900">
                                    {{ visitant.name }}
                                </h2>
                                <p class="mt-1 text-sm/6 text-gray-600">Atualização de dados.</p>

                                <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                    <div class="sm:col-span-6">
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

                                    <div class="sm:col-span-6">
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
                                    :href="route('visitants.index')"
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
