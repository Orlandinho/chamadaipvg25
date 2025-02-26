<script setup>
    import { computed } from 'vue';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({ status: Number });

    const error = computed(() => {
        return {
            503: '503',
            500: '500',
            404: '404',
            403: '403',
        }[props.status];
    });

    const title = computed(() => {
        return {
            503: 'Serviço Indisponível',
            500: 'Erro de Servidor',
            404: 'Página Não Encontrada',
            403: 'Não Autorizado',
        }[props.status];
    });

    const description = computed(() => {
        return {
            503: 'Desculpe, no momento estamos em manutenção. Retorne em alguns instantes',
            500: 'Oops! Algo deu errado nos nossos servidores',
            404: 'Desculpe, não conseguimos encontrar a página que você está procurando',
            403: 'Desculpe, você não tem autorização para acessar essa página',
        }[props.status];
    });
</script>

<template>
    <Head :title="title" />

    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-neutral-100">
        <div class="py-12">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-neutral-900">
                        <div class="text-center">
                            <img src="/storage/images/error_small.png" :alt="title" class="mb-6 text-center mx-auto" />
                            <p class="text-base font-semibold">{{ error }}</p>
                            <p class="text-base font-semibold">{{ title }}</p>
                            <p class="mt-6 text-base leading-7">
                                {{ description }}
                            </p>
                            <div class="mt-10 flex items-center justify-center">
                                <Link
                                    :href="route('dashboard')"
                                    class="text-md font-semibold text-blue-500 hover:underline">
                                    Voltar ao Dashboard
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
