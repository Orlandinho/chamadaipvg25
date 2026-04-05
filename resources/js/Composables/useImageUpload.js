import { ref } from 'vue';
import imageCompression from 'browser-image-compression';

export function useImageUpload(form, field = 'avatar', customOptions = {}) {
    const file = ref(null);
    const preview = ref(null);
    const loading = ref(false);

    const defaultOptions = {
        maxSizeMB: 0.25,
        maxWidthOrHeight: 400,
        useWebWorker: true,
    };

    const options = { ...defaultOptions, ...customOptions };

    const handleImage = async (e) => {
        const inputFile = e.target.files[0];
        if (!inputFile) return;

        if (!inputFile.type.startsWith('image/')) {
            form.setError(field, 'Arquivo deve ser uma imagem');
            return;
        }

        loading.value = true;

        try {
            const compressedBlob = await imageCompression(inputFile, options);

            file.value = new File([compressedBlob], inputFile.name, {
                type: compressedBlob.type,
            });

            if (preview.value) {
                URL.revokeObjectURL(preview.value);
            }

            preview.value = URL.createObjectURL(file.value);

            // attach to form
            form[field] = file.value;
        } catch (error) {
            form.setError(field, 'Houve um problema ao carregar a imagem');
        } finally {
            loading.value = false;
        }
    };

    return {
        file,
        preview,
        loading,
        handleImage,
    };
}
