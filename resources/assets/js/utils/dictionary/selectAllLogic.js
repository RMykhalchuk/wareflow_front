export function initSelectAllLogic(selectors, allValue = 'all') {
    selectors.forEach((selector) => {
        const $select = $(selector);

        if (!$select.length) return;

        $select.on('change', function () {
            const values = $(this).val() || [];

            // якщо вибрали "all" + ще щось
            if (values.includes(allValue) && values.length > 1) {
                $(this).val([allValue]).trigger('change.select2');
                return;
            }

            // якщо вибрали щось інше
            if (!values.includes(allValue)) {
                $(this)
                    .val(values.filter((v) => v !== allValue))
                    .trigger('change.select2');
            }
        });
    });
}
