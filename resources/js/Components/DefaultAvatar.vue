<script setup>
    import { computed } from 'vue';

    const props = defineProps({
        person: {
            type: Object,
            required: true,
        },
        size: {
            type: String,
            default: 'size-8',
        },
        textSize: {
            type: String,
            default: 'text-sm',
        },
    });

    const getInitials = computed(() => {
        let initials = props.person.name.split(' ');

        return initials.length > 1 ? initials[0].charAt() + initials[1].charAt() : initials[0].charAt();
    });
</script>

<template>
    <img
        v-if="props.person.avatar"
        class="inline-block rounded-full"
        :class="size"
        :src="props.person.avatar"
        :alt="props.person.name" />
    <span
        v-else
        class="inline-flex items-center justify-center rounded-full border border-green-700 bg-white"
        :class="size">
        <span class="font-medium tracking-wide text-green-700" :class="textSize">{{ getInitials }}</span>
    </span>
</template>
