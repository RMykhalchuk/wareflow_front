$(document).ready(function () {
    let url = window.location.origin;
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    let statusInfo;

    $("a[id*='open-edit-status-modal-button-']").click(function (e) {
        e.preventDefault();

        statusInfo = JSON.parse(
            $(`#status-block-${e.currentTarget.dataset.statusId}`)[0].dataset.statusInfo
        );

        $('#edit-status-select').val(statusInfo.pivot.status_id).trigger('change');
        $('#edit-status-select-location').val(statusInfo.pivot.address_id).trigger('change');

        $('#fp-date-time-edit').val(statusInfo.pivot.date);
        $('#edit-status-comment').val(statusInfo.pivot.comment);
    });

    $("a[id*='change-status-modal-finish-']").click(function (e) {
        e.preventDefault();
        $('#change-status-select').val(12).trigger('change');
    });

    $("a[id*='change-status-modal-cancel-']").click(function (e) {
        e.preventDefault();
        $('#change-status-select').val(13).trigger('change');
    });

    $('#edit-status-button').click(async function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('address_id', $('#edit-status-select-location').val());
        formData.append('status_id', $('#edit-status-select').val());
        formData.append('date', $('#fp-date-time-edit').val());
        formData.append('comment', $('#edit-status-comment').val());

        await fetch(url + `/transport-planning/status/${statusInfo.pivot.id}`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if (response.status === 200) {
                location.reload();
            } else {
                let res = await response.json();
                let data = res.errors;
                console.log(data);
            }
        });
    });

    $("a[id*='delete-status-']").click(async function (e) {
        e.preventDefault();

        await fetch(url + `/transport-planning/status/${e.target.dataset.statusId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if (response.status === 200) {
                location.reload();
            } else {
                let res = await response.json();
                let data = res.errors;
                console.log(data);
            }
        });
    });

    $('#create-status-button').click(async function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('address_id', $('#change-status-select-location').val());
        formData.append('status_id', $('#change-status-select').val());
        formData.append('date', $('#fp-date-time').val());
        formData.append('comment', $('#change-status-comment').val());
        formData.append('transport_planning_id', e.target.dataset.transportPlanningId);

        await fetch(url + `/transport-planning/status`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if (response.status === 200) {
                $('#change-status-select-location').val([]);
                $('#change-status-select').val([]);
                $('#fp-date-time').val('');
                $('#change-status-comment').val('');

                window.location.href = window.location.href;
            } else {
                let res = await response.json();
                let data = res.errors;
                console.log(data);
            }
        });
    });

    let currentDeleteId;

    $("div[id*='delete-transport-planning-']").click(async function (e) {
        e.preventDefault();

        currentDeleteId = e.currentTarget.dataset.transportId;
        $('#delete-planning-id-block')[0].innerText = `№ ${currentDeleteId}`;
    });

    $('#delete-transport-planning').click(async function (e) {
        e.preventDefault();

        await fetch(url + `/transport-planning/${currentDeleteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if (response.status === 200) {
                location.reload();
            } else {
                let res = await response.json();
                let data = res.errors;
                console.log(data);
            }
        });
    });

    $("a[id*='open-add-failure-model-button-']").click(async function (e) {
        e.preventDefault();

        statusInfo = JSON.parse(
            $(`#status-block-${e.currentTarget.dataset.statusId}`)[0].dataset.statusInfo
        );

        if (statusInfo.failure_id) {
            $('#problemSelect').val(statusInfo.failure_type_id).trigger('change');
            $('#problem-reason').val(statusInfo.cause_failure);
            $('#problem-author').val(statusInfo.culprit_of_failure);
            $('#problem-price').val(statusInfo.cost_of_fines);
            $('#problem-comment').val(statusInfo.failure_comment);
        }
    });

    $('#add-reason-submit').click(async function (e) {
        e.preventDefault();

        let formData = new FormData();
        formData.append('comment', $('#problem-comment').val());
        formData.append('cause_failure', $('#problem-reason').val());
        formData.append('culprit_of_failure', $('#problem-author').val());
        formData.append('cost_of_fines', $('#problem-price').val());
        formData.append('type_id', $('#problemSelect').val());

        await fetch(url + `/transport-planning/status/${statusInfo.pivot.id}/failure`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-Token': csrf,
            },
        }).then(async (response) => {
            if (response.status === 200) {
                $('#problemSelect').val([]);
                $('#problem-reason').val([]);
                $('#problem-author').val('');
                $('#problem-price').val('');
                $('#problem-comment').val('');

                location.reload(true);
            } else {
                let res = await response.json();
                let data = res.errors;
                console.log(data);
            }
        });
    });

    // Функція для оновлення класу кнопки
    function updateButtonClass(button, forceHide = false) {
        var dropdownMenu = button.querySelector('.dropdown-menu');
        if (dropdownMenu.classList.contains('show') && !forceHide) {
            button.classList.add('js-button-dropdown-menu-active');
        } else {
            button.classList.remove('js-button-dropdown-menu-active');
        }
    }

    // Делегування обробника кліку для кнопки
    document.addEventListener('click', function (event) {
        // Перевірка для кожної кнопки з dropdown
        document.querySelectorAll('.js-button-dropdown-menu').forEach(function (button) {
            // Якщо клік був в межах кнопки або її dropdown-menu
            if (button.contains(event.target)) {
                updateButtonClass(button);
            } else {
                // Якщо клік був поза межами, прибираємо клас та закриваємо dropdown
                updateButtonClass(button, true);
                button.querySelector('.dropdown-menu').classList.remove('show');
            }
        });
    });

    $(document).on('mouseenter', '.transport-planning-status', function () {
        // Знаходимо відповідний елемент js-button-dropdown-menu-hover всередині цього блоку
        var dropdownHover = $(this).find('.js-button-dropdown-menu-hover');
        if (dropdownHover.hasClass('d-none')) {
            dropdownHover.removeClass('d-none').addClass('d-flex');
        }
    });

    $(document).on('mouseleave', '.transport-planning-status', function () {
        // Знову знаходимо відповідний елемент всередині цього блоку
        var dropdownHover = $(this).find('.js-button-dropdown-menu-hover');
        // Перевіряємо, чи внутрішній dropdown-menu має клас show
        if (!dropdownHover.find('.dropdown-menu').hasClass('show')) {
            // Якщо не має, то змінюємо d-flex на d-none
            dropdownHover.removeClass('d-flex').addClass('d-none');
        }
    });

    $(document).on('click', function (event) {
        // Перебираємо всі елементи js-button-dropdown-menu-hover
        $('.js-button-dropdown-menu-hover').each(function () {
            var dropdownMenu = $(this).find('.dropdown-menu');
            var transportPlanningStatus = $(this).closest('.transport-planning-status');

            // Перевіряємо, чи клік був зроблений поза dropdown-menu і поза transport-planning-status
            if (
                !dropdownMenu.is(event.target) &&
                !dropdownMenu.has(event.target).length &&
                !transportPlanningStatus.is(event.target) &&
                !transportPlanningStatus.has(event.target).length &&
                !dropdownMenu.hasClass('show')
            ) {
                // Ховаємо блок і додаємо клас d-none
                $(this).addClass('d-none').removeClass('d-flex');
            }
        });
    });
});
