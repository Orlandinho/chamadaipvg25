import { reactive, readonly } from 'vue';

const globalState = reactive({
    show: false,
    title: '',
    message: '',
    resolver: null,
});

export function useConfirm() {
    const resetModal = () => {
        globalState.show = false;

        setTimeout(() => {
            globalState.title = '';
            globalState.message = '';
            globalState.resolver = null;
        }, 400);
    };

    return {
        state: readonly(globalState),
        confirmation: (message, title = 'Atenção!') => {
            globalState.title = title;
            globalState.message = message;
            globalState.show = true;

            return new Promise((resolver) => {
                globalState.resolver = resolver;
            });
        },
        confirm: () => {
            if (globalState.resolver) {
                globalState.resolver(true);
            }

            resetModal();
        },
        cancel: () => {
            if (globalState.resolver) {
                globalState.resolver(false);
            }

            resetModal();
        },
    };
}
