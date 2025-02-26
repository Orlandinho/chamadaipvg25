<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Página Não Encontrada" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        <img
                            src="/storage/images/error_small.png"
                            alt="page not found"
                            class="mb-6 text-center mx-auto" />
                        <p class="text-base font-semibold">404</p>
                        <p class="mt-6 text-base leading-7">
                            Oops! Parece que a página que você está tentando acessar não existe.
                        </p>
                        <div class="mt-10">
                            <Link
                                :href="route('dashboard')"
                                class="text-md font-semibold text-blue-500 hover:underline">
                                Voltar à Página Inicial
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
