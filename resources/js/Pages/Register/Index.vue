<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router, usePage } from '@inertiajs/vue3';
    import {
        XMarkIcon,
        CheckIcon,
        MinusIcon,
        ChevronDoubleLeftIcon,
        ChevronDoubleRightIcon,
    } from '@heroicons/vue/24/outline/index.js';
    import { format, subWeeks, subMonths, isSunday, eachWeekendOfInterval } from 'date-fns';
    import { computed, ref, watch } from 'vue';
    import DefaultAvatar from '@/Components/DefaultAvatar.vue';

    const props = defineProps({
        students: Object,
        classrooms: Object,
        visitants: Object,
        selectedClass: Number,
    });

    const filter = ref(props.selectedClass);
    let counter = ref(0);

    function inc() {
        counter.value++;
    }

    function dec() {
        counter.value--;
    }

    watch(filter, (value) => {
        router.get(
            '/chamada',
            { filter: value },
            {
                preserveState: true,
                replace: true,
            },
        );
    });

    const sundays = computed(() => {
        let currentDate = subMonths(Date.now(), counter.value);
        let startDate = subWeeks(currentDate, 4);
        let results = [];

        let days = eachWeekendOfInterval({
            start: startDate,
            end: currentDate,
        });

        days.forEach((day) => {
            if (isSunday(day)) {
                results.push({
                    formatted: format(day, 'dd/MM/yy'),
                    database: format(day, 'yyyy-MM-dd'),
                });
            }
        });

        return results;
    });

    const setIcon = (check) => {
        if (check) {
            return CheckIcon;
        }

        return XMarkIcon;
    };

    const setColor = (check) => {
        if (check) {
            return 'text-green-500';
        }

        return 'text-red-500';
    };

    const classroomName = computed(() => {
        if (usePage().props.auth.user.role_id === 3) {
            let classroom = props.classrooms.filter(
                (classroom) => classroom.id === usePage().props.auth.user.classroom_id,
            );
            return 'Chamada ' + classroom[0].name;
        }

        return 'Chamada Geral';
    });
</script>

<template>
    <Head :title="classroomName" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="sm:flex sm:items-center">
                            <div class="sm:flex-auto">
                                <div v-if="students.length < 1">
                                    <h1 class="text-base font-semibold text-gray-900">Registro de Chamada</h1>
                                    <p class="mt-2 text-sm text-gray-700">
                                        Ainda não há nenhum aluno ativo que esteja registrado em alguma classe. Clique
                                        no botão ao lado para registrar um novo aluno
                                    </p>
                                </div>
                                <div v-else>
                                    <h2 v-if="visitants.length < 1" class="text-base font-semibold mb-1 text-gray-900">
                                        Não há visitantes
                                    </h2>
                                    <div v-else>
                                        <h2 class="text-base font-semibold mb-1 text-gray-900">Visitantes</h2>
                                        <ul role="list" class="text-xs text-gray-600">
                                            <li v-for="visit in visitants" :key="visit.id" class="py-.5">
                                                {{ visit.name }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div v-if="$page.props.auth.user.role_id < 3 && students.length > 0">
                                <select
                                    v-model="filter"
                                    class="rounded-md py-1 pl-3 pr-8 border-gray-300 shadow-sm text-sm/6 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="0" :selected="filter < 1">Todos os alunos</option>
                                    <option
                                        v-for="item in classrooms"
                                        :key="item.id"
                                        :selected="filter === item.id"
                                        :value="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="mt-4 sm:ml-8 sm:mt-0 sm:flex-none">
                                <Link
                                    :href="route('students.create')"
                                    class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-3 py-1.5 text-sm font-semibold text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900">
                                    Novo Aluno(a)
                                </Link>
                            </div>
                        </div>
                        <div v-if="students.length > 0" class="mt-8 flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <div class="mb-3 px-2 flex justify-between">
                                        <button class="flex text-gray-600 justify-center" @click="inc">
                                            <ChevronDoubleLeftIcon class="h-5 mr-2" />
                                            <span class="text-sm">-1 Mês</span>
                                        </button>
                                        <button
                                            class="flex text-gray-600 justify-center"
                                            v-show="counter > 0"
                                            @click="dec">
                                            <span class="text-sm">+1 Mês</span>
                                            <ChevronDoubleRightIcon class="h-5 ml-2" />
                                        </button>
                                    </div>
                                    <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-green-50">
                                                <tr>
                                                    <th
                                                        scope="col"
                                                        class="py-3 pl-4 pr-3 min-w-[250px] sm:min-w-[0] text-left text-sm font-semibold text-gray-900 sm:pl-6">
                                                        Nome
                                                    </th>

                                                    <th
                                                        v-for="sunday in sundays"
                                                        :key="sunday.formatted"
                                                        scope="col"
                                                        class="px-3 py-3 text-center text-sm font-semibold text-gray-900">
                                                        {{ sunday.formatted }}
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                <tr v-for="student in students" :key="student.id">
                                                    <td
                                                        class="whitespace-nowrap py-3 pl-2 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                                        <Link
                                                            :href="route('students.show', student)"
                                                            class="flex-inline hover:underline items-center">
                                                            <DefaultAvatar :person="student" size="size-8" />
                                                            <span class="ml-4">{{ student.name }}</span>
                                                        </Link>
                                                    </td>

                                                    <td
                                                        v-for="sunday in sundays"
                                                        :key="sunday.database"
                                                        class="whitespace-nowrap text-center px-3 py-3 font text-sm text-gray-500">
                                                        <Link
                                                            :href="
                                                                route('registers.update', {
                                                                    student: student,
                                                                    sunday: sunday.database,
                                                                    filter: filter,
                                                                })
                                                            "
                                                            method="patch"
                                                            as="button"
                                                            class="p-2"
                                                            preserve-scroll>
                                                            <component
                                                                :is="
                                                                    student.registers.find(function (el) {
                                                                        return el.sunday === sunday.database;
                                                                    })
                                                                        ? setIcon(
                                                                              student.registers.find(function (el) {
                                                                                  if (el.sunday === sunday.database) {
                                                                                      return el.status;
                                                                                  }
                                                                              }),
                                                                          )
                                                                        : MinusIcon
                                                                "
                                                                class="size-5"
                                                                :class="
                                                                    student.registers.find(function (el) {
                                                                        return el.sunday === sunday.database;
                                                                    })
                                                                        ? setColor(
                                                                              student.registers.find(function (el) {
                                                                                  if (el.sunday === sunday.database) {
                                                                                      return el.status;
                                                                                  }
                                                                              }),
                                                                          )
                                                                        : MinusIcon
                                                                " />
                                                        </Link>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
