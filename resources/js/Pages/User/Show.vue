<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head } from '@inertiajs/vue3';
    import DefaultAvatar from '@/Components/DefaultAvatar.vue';

    const props = defineProps({
        user: Object,
    });
</script>

<template>
    <Head :title="user.name" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div>
                            <div class="px-4 sm:px-0">
                                <div class="flex items-center space-x-4">
                                    <DefaultAvatar :person="user" size="size-12" text-size="text-2xl" />
                                    <span class="text-base/7 font-semibold text-gray-900">
                                        {{ user.name }}
                                    </span>
                                </div>
                                <p class="mt-4 max-w-2xl text-sm/6 text-gray-500">Detalhes pessoais.</p>
                            </div>
                            <div class="mt-6 border-t border-gray-100">
                                <dl class="divide-y divide-gray-100">
                                    <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">E-mail</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ user.email }}
                                        </dd>
                                    </div>
                                    <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Função</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ user.role }}
                                        </dd>
                                    </div>
                                    <div
                                        v-if="user.classroom"
                                        class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-3">
                                        <dt class="text-sm/6 font-medium text-gray-900">Classe</dt>
                                        <dd class="mt-1 text-sm/6 text-gray-600 sm:col-span-2 sm:mt-0">
                                            {{ user.classroom.name }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
