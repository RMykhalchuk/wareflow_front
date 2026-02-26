/**
 * Поллінг статусу асинхронної генерації етикеток.
 * Виконує запит до /stickers/print-labels/status/{jobId} кожні 2 секунди.
 * Коли готово — відкриває всі PDF файли в нових вкладках.
 */
export async function pollLabelJob(url, jobId, $loader) {
    console.log('[pollLabelJob] v2 — blob version started');
    const MAX_ATTEMPTS = 150; // 5 хв максимум (150 * 2с)

    for (let attempt = 0; attempt < MAX_ATTEMPTS; attempt++) {
        await new Promise((resolve) => setTimeout(resolve, 2000));

        let data;
        try {
            const res = await fetch(
                `${window.location.origin}/${url}stickers/print-labels/status/${jobId}`
            );
            data = await res.json();
        } catch {
            $loader.removeClass('d-flex').addClass('d-none');
            alert('Не вдалося сформувати файл для друку.');
            return;
        }

        if (data.status === 'done') {
            const fileUrls = data.file_urls ?? [];

            try {
                // Завантажуємо файли як blob поки лоадер видимий
                const blobs = await Promise.all(
                    fileUrls.map(async (fileUrl) => {
                        const res = await fetch(fileUrl);
                        return await res.blob();
                    })
                );

                $('#print-modal').modal('hide');

                // Тригеримо скачування з blob
                blobs.forEach((blob, index) => {
                    const blobUrl = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = `labels_${index + 1}.pdf`;
                    a.style.display = 'none';
                    document.body.appendChild(a);
                    a.click();
                    setTimeout(() => {
                        document.body.removeChild(a);
                        URL.revokeObjectURL(blobUrl);
                    }, 1000);
                });

                // Ховаємо лоадер після того як браузер підхопить скачування
                setTimeout(() => {
                    $loader.removeClass('d-flex').addClass('d-none');
                }, 3000);
            } catch (e) {
                console.error('Помилка завантаження PDF:', e);

                // Фолбек — пряме скачування через <a>
                fileUrls.forEach((fileUrl, index) => {
                    setTimeout(() => {
                        const a = document.createElement('a');
                        a.href = fileUrl;
                        a.download = `labels_${index + 1}.pdf`;
                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.click();
                        setTimeout(() => document.body.removeChild(a), 1000);
                    }, index * 500);
                });

                $loader.removeClass('d-flex').addClass('d-none');
                $('#print-modal').modal('hide');
            }

            return;
        }

        if (data.status === 'failed') {
            $loader.removeClass('d-flex').addClass('d-none');
            console.error('Помилка генерації етикеток:', data.error);
            alert('Не вдалося сформувати файл для друку.');
            return;
        }

        // status === 'pending' — чекаємо наступної ітерації
    }

    $loader.removeClass('d-flex').addClass('d-none');
    alert('Час очікування вичерпано. Зверніться до адміністратора або спробуйте пізніше.');
}
