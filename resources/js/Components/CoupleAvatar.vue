<script setup>
    import { computed } from 'vue';
    import { HeartIcon } from '@heroicons/vue/24/solid/index.js';

    const props = defineProps({
        couple: {
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

    const husbandInitials = computed(() => {
        let initials = props.couple.husband.split(' ');

        return initials.length > 1 ? initials[0].charAt() + initials[1].charAt() : initials[0].charAt();
    });

    const wifeInitials = computed(() => {
        let initials = props.couple.wife.split(' ');

        return initials.length > 1 ? initials[0].charAt() + initials[1].charAt() : initials[0].charAt();
    });
</script>

<template>
    <div class="flex items-center justify-center space-x-3">
        <img
            v-if="props.couple.husband_avatar"
            class="inline-block rounded-full"
            :class="size"
            :src="props.couple.husband_avatar"
            :alt="props.couple.husband" />
        <span
            v-else
            class="inline-flex items-center justify-center rounded-full border border-green-700 bg-white"
            :class="size">
            <span class="font-medium text-green-700" :class="textSize">{{ husbandInitials }}</span>
        </span>
        <HeartIcon class="size-3 text-red-600" />
        <img
            v-if="props.couple.wife_avatar"
            class="inline-block rounded-full"
            :class="size"
            :src="props.couple.wife_avatar"
            :alt="props.couple.wife" />
        <span
            v-else
            class="inline-flex items-center justify-center rounded-full border border-green-700 bg-white"
            :class="size">
            <span class="font-medium text-green-700" :class="textSize">{{ wifeInitials }}</span>
        </span>
    </div>
</template>
