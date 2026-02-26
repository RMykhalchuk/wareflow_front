const tableContainer = $('#table-container');
let editIndex = null; // 🔹 Індекс редагованого елемента

$('#package_submit').on('click', function () {
    const item = {
        // id: $('#goods').val(), // 🔹 id беремо з select товару
        name: $('#sku_name').text(),
        barcode: $('#sku_barcode').text(),
        brand: $('#sku_brand').text(),
        supplier: $('#sku_supplier').text(),
        manufacturer: $('#sku_manufacturer').text(),
        country: $('#sku_country').text(),
        party: $('#party').val(),
        manufactured_date: $('#manufactured_date').val(),
        bb_date: $('#bb_date').val(),
        quantity: $('#quantity').val(),
        unit: $('#measurement_unit').text(), // 🔹 одиниця виміру (input/select у формі)
    };

    if (!item.name || item.name === '-' || !item.party || !item.quantity) {
        alert('Будь ласка, заповніть усі обов’язкові поля');
        return;
    }

    if (editIndex !== null) {
        window.tableData[editIndex] = item;
        editIndex = null;
    } else {
        window.tableData.push(item);
    }

    renderTable(window.tableData);

    $('#add_sku').modal('hide');
    resetForm();
    $('#package_submit').text('Додати');
});

export function renderTable(data) {
    if (!data || data.length === 0) {
        tableContainer.html('');
        return;
    }

    let tableHtml = `
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Назва</th>
                        <th>Партія</th>
                        <th>Дата виготовлення</th>
                        <th>Придатний до</th>
                        <th>Од. виміру</th>
                        <th>Кількість</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
        `;

    data.forEach((item, index) => {
        // <td className="bg-white">${item.id ?? '-'}</td>;

        tableHtml += `
                <tr>
                   <td class="bg-white">${index + 1}</td> <!-- 🔹 порядковий ID -->

                   <td class="bg-white">
                        <div><strong>${item.name}</strong></div>
                        <div>Штрих-код: ${item.barcode}</div>
                        <div>Бренд: ${item.brand}</div>
                        <div>Постачальник: ${item.supplier}</div>
                        <div>Виробник: ${item.manufacturer}</div>
                        <div>Країна: ${item.country}</div>
                   </td>
                   <td class="bg-white">${item.party}</td>
                   <td class="bg-white">${item.manufactured_date}</td>
                   <td class="bg-white">${item.bb_date}</td>
                   <td class="bg-white">${item.unit ?? '-'}</td>
                   <td class="bg-white">${item.quantity}</td>
                   <td class="bg-white">
                        <button class="btn btn-sm btn-light" data-bs-toggle="popover" data-index="${index}">
                            <i data-feather="more-vertical"></i>
                        </button>
                        <div class="popover-actions d-none" id="popover-${index}">
                            <button class="btn btn-sm btn-link js-edit" data-index="${index}">
                                <i data-feather="edit-2"></i> Редагувати
                            </button><br>
                            <button class="btn btn-sm btn-link js-remove" data-index="${index}">
                                <i data-feather="trash-2"></i> Видалити
                            </button>
                        </div>
                    </td>

                </tr>
            `;
    });

    tableHtml += `</tbody></table>`;
    tableContainer.html(tableHtml);
    feather.replace();

    // ініціалізація поповерів
    const popoverInstances = [];
    const popoverTriggerList = [].slice.call(tableContainer.find('[data-bs-toggle="popover"]'));

    popoverTriggerList.forEach((popoverTriggerEl) => {
        const idx = $(popoverTriggerEl).data('index');
        const content = $(`#popover-${idx}`).html();

        const popoverInstance = new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            sanitize: false,
            content: content,
        });

        popoverInstances.push(popoverInstance);

        popoverTriggerEl.addEventListener('click', () => {
            popoverInstances.forEach((inst) => {
                if (inst._element !== popoverTriggerEl) {
                    inst.hide();
                }
            });
        });
    });
}

function resetForm() {
    // повертаємо форму до початкового стану
    $('#category').val(null).trigger('change');
    $('#goods').val(null).trigger('change');
    $('#goods').attr('disabled', true);

    $('#party').val(null);
    $('#manufactured_date').val('');
    $('#bb_date').val('');
    $('#quantity').val('');

    $(
        '#sku_name, #sku_barcode, #sku_brand, #sku_supplier, #sku_manufacturer, #sku_country, #measurement_unit'
    ).text('-');

    $('.js-category-block').removeClass('d-none');
    $('.js-full-block').addClass('d-none');
}

// видалення
$(document).on('click', '.js-remove', function () {
    const index = $(this).data('index');
    window.tableData.splice(index, 1);
    renderTable(window.tableData);
});

// редагування
$(document).on('click', '.js-edit', function () {
    const index = $(this).data('index');
    const item = window.tableData[index];
    editIndex = index;

    $('#sku_name').text(item.name);
    $('#sku_barcode').text(item.barcode);
    $('#sku_brand').text(item.brand);
    $('#sku_supplier').text(item.supplier);
    $('#sku_manufacturer').text(item.manufacturer);
    $('#sku_country').text(item.country);
    $('#measurement_unit').text(item.unit);

    $('#party').val(item.party);
    $('#manufactured_date').val(item.manufactured_date);
    $('#bb_date').val(item.bb_date);
    $('#quantity').val(item.quantity);

    $('.js-category-block').addClass('d-none');
    $('.js-full-block').removeClass('d-none');
    $('#package_submit').text('Оновити');
    $('#add_sku').modal('show');
});

// Обробник закриття модалки
$('#add_sku').on('hidden.bs.modal', function () {
    resetForm(); // 🔹 Скидаємо форму
    editIndex = null; // 🔹 Прибираємо індекс редагування
    $('#package_submit').text('Додати'); // 🔹 Кнопка назад
});

renderTable(window.tableData);
