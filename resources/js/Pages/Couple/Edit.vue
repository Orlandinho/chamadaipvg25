<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, useForm, Link } from '@inertiajs/vue3';
    import InputLabel from '@/Components/InputLabel.vue';
    import InputError from '@/Components/InputError.vue';
    import TextInput from '@/Components/TextInput.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import { format } from 'date-fns';

    const props = defineProps({
        couple: Object,
    });

    const form = useForm({
        husband: props.couple.husband,
        wife: props.couple.wife,
        marriage_date: format(new Date(props.couple.marriage_date), 'yyyy-MM-dd'),
    });

    const submit = () => {
        form.patch(route('couples.update', props.couple));
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
