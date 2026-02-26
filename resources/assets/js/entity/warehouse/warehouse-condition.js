import { getLocalizedText } from '../../localization/warehouse/getLocalizedText.js';

$(document).ready(function () {
    // console.log('conditions :', conditions)
    updateConditionsUI();
    let urlBase = window.location.origin;

    $("input[name='select_period']").change(function () {
        if ($(this).val() === 'one_day') {
            $('#one_day').css('display', 'flex');
            $('#period').css('display', 'none');
            $('.date-1').val('');
            $('.date-2').val('');
        } else {
            $('#period').css('display', 'flex');
            $('#one_day').css('display', 'none');
            $('.one_day').val('');
        }
    });

    function insertDOM(recordBlock, conditionsEntity, id) {
        const workDayText =
            conditionsEntity.hasOwnProperty('work_from') &&
            conditionsEntity.hasOwnProperty('work_to')
                ? `${getLocalizedText('workDay')}:
            <span class="hours f-15" id="work_from_${id}">${conditionsEntity.work_from}</span> -
            <span class="hours f-15" id="work_to_${id}">${conditionsEntity.work_to}</span><br>`
                : '';

        const lunchBreakText =
            conditionsEntity.hasOwnProperty('break_from') &&
            conditionsEntity.hasOwnProperty('break_to')
                ? `${getLocalizedText('lunchBreak')}:
            <span class="hours f-15" id="break_from_${id}">${conditionsEntity.break_from}</span> -
            <span class="hours f-15" id="break_to_${id}">${conditionsEntity.break_to}</span><br>`
                : '';

        const dateContent =
            conditionsEntity.period === 'one_day'
                ? `<span class="f-15" id="date_${id}">${conditionsEntity.date_from}</span>`
                : `<span class="f-15" id="date_from_${id}">${conditionsEntity.date_from}</span> -
           <span class="f-15" id="date_to_${id}">${conditionsEntity.date_to}</span>`;

        const timeBlockContent = `
        <div class="col-10">
            <div class="d-flex flex-wrap flex-column">
                <div class="d-flex align-items-center mb-25">
                    <img src="${urlBase}/assets/icons/entity/user/calendar-chosen-user.svg" style="margin-right: 5px;">
                    <span class="f-15 fw-bold" id="condition_${id}">${conditionsEntity.name}</span>
                </div>
                 <div class="mb-1">
                    ${dateContent}
                </div>
                 <div>
                    ${workDayText}
                    ${lunchBreakText}
                </div>

            </div>
        </div>
    `;

        const btnBlockContent = `
        <div class="col-2 row mx-0 align-self-start ps-0">
            <button class="btn p-0 edit-condition w-50" id="edit-condition-${id}" data-condition="${id}" onclick="editCondition">
                <img src="${urlBase}/assets/icons/entity/user/edit-img-user.svg">
            </button>
            <button class="btn p-0 delete-condition w-50" id="delete-condition-${id}" data-condition="${id}" onclick="deleteCondition">
                <img src="${urlBase}/assets/icons/entity/user/delete-img-user.svg">
            </button>
        </div>
    `;

        recordBlock.innerHTML = timeBlockContent + btnBlockContent;

        document.getElementById('condition-list').append(recordBlock);

        // Ensure elements are available before attaching event listeners
        const editBtn = document.getElementById(`edit-condition-${id}`);
        const deleteBtn = document.getElementById(`delete-condition-${id}`);

        if (editBtn) {
            editBtn.onclick = editCondition;
        } else {
            console.error(`Edit button with ID edit-condition-${id} not found`);
        }

        if (deleteBtn) {
            deleteBtn.onclick = deleteCondition;
        } else {
            console.error(`Delete button with ID delete-condition-${id} not found`);
        }
    }

    function createRecord(conditionsEntity) {
        let id = conditionsEntity.id;
        let recordBlock = document.createElement('div');
        recordBlock.className = 'record border-bottom pb-1  row mx-0 mt-1';
        recordBlock.id = 'record_' + id;
        insertDOM(recordBlock, conditionsEntity, id);
    }

    function clearPopUp() {
        $('#condition_name').val('Не вказано');
        $('#work_from').val('');
        $('#work_to').val('');
        $('#break_from').val('');
        $('#break_to').val('');
        $('input[name="one_day"]').val('');
        $('#date-1').val('');
        $('#date-2').val('');
        $('.modal').modal('hide');
        $('#condition_submit').prop('disabled', true);
        $('#condition_name').val(null).trigger('change');
    }

    $('#condition_submit').on('click', function () {
        if (checkHoursInSchedule('.two-input-for-schedule-inmodal')) {
            return;
        }

        let conditionsEntity = {};

        conditionsEntity['id'] =
            conditions.length > 0 ? Math.max(...conditions.map((el) => +el.id)) + 1 : 1;

        // Отримання data-name для вибраного варіанту
        let selectedOption = $('#condition_name').find('option:selected');
        conditionsEntity['name'] = selectedOption.data('name');

        conditionsEntity['key'] = selectedOption.val();

        conditionsEntity['period'] = $('input[name=select_period]:checked').val();
        conditionsEntity['type_id'] = $('#condition_name').find('option:selected').data('id');

        if ($('#work_from').val() && $('#work_to').val()) {
            conditionsEntity['work_from'] = $('#work_from').val();
            conditionsEntity['work_to'] = $('#work_to').val();
        }

        if ($('#break_from').val() && $('#break_to').val()) {
            conditionsEntity['break_from'] = $('#break_from').val();
            conditionsEntity['break_to'] = $('#break_to').val();
        }

        if (conditionsEntity['period'] === 'one_day') {
            conditionsEntity['date_from'] = $('input[name="one_day"]').val();
        } else {
            conditionsEntity['date_from'] = $('#date-1').val();
            conditionsEntity['date_to'] = $('#date-2').val();
        }

        conditions.push(conditionsEntity);

        createRecord(conditionsEntity);
        clearPopUp();
        updateConditionsUI();
    });

    $('#edit_condition_submit').on('click', function () {
        if (checkHoursInSchedule('.two-input-for-schedule-inmodal')) {
            return;
        }

        let condition_id = $('#edit-modal').attr('data-condition');
        const foundIndex = conditions.findIndex((el) => el.id == condition_id);

        let conditionsEntity = conditions[foundIndex];

        conditionsEntity['id'] = +condition_id;
        // Отримання data-name для вибраного варіанту
        let selectedOption = $('#edit_condition_name').find('option:selected');
        conditionsEntity['name'] = selectedOption.data('name');
        let name = selectedOption.data('name');
        conditionsEntity['key'] = selectedOption.val();

        conditionsEntity['period'] = $('input[name=edit_select_period]:checked').val();
        let type_id = $('#edit_condition_name').find('option:selected').data('id');
        conditionsEntity['type_id'] = type_id;
        conditionsEntity['type'] = { id: type_id, name };
        if ($('#edit_work_from').val() && $('#edit_work_to').val()) {
            conditionsEntity['work_from'] = $('#edit_work_from').val();
            conditionsEntity['work_to'] = $('#edit_work_to').val();
        }

        if ($('#edit_break_from').val() && $('#edit_break_to').val()) {
            conditionsEntity['break_from'] = $('#edit_break_from').val();
            conditionsEntity['break_to'] = $('#edit_break_to').val();
        }

        if (conditionsEntity['period'] === 'one_day') {
            conditionsEntity['date_from'] = $('input[name="edit_one_day"]').val();
        } else {
            conditionsEntity['date_from'] = $('#edit_date-1').val();
            conditionsEntity['date_to'] = $('#edit_date-2').val();
        }
        let recordBlock = $('#record_' + condition_id);
        recordBlock.empty();
        clearUpdatePopUp();
        if (type_id !== 3) {
            const { work_from, work_to, break_from, break_to, ...rest } = conditionsEntity;
            conditionsEntity = rest;
        }

        insertDOM(recordBlock.get(0), conditionsEntity, condition_id);
        $('.modal').modal('hide');

        conditions[foundIndex] = conditionsEntity;
    });

    function clearUpdatePopUp() {
        $('#edit_work_from').val('');
        $('#edit_work_to').val('');
        $('#edit_break_from').val('');
        $('#edit_break_to').val('');
        $('input[name="edit_one_day"]').val('');
        $('#edit_date-1').val('');
        $('#edit_date-2').val('');
        $('.modal').modal('hide');
    }

    $('.cancel-btn').on('click', function () {
        $('.modal').modal('hide');
        clearPopUp();
        clearUpdatePopUp();
    });

    $("input[name='edit_select_period']").change(function () {
        if ($(this).val() === 'one_day') {
            $('#edit_one_day').css('display', 'flex');
            $('#edit_period').css('display', 'none');
            $('.edit_date-1').val('');
            $('.edit_date-2').val('');
        } else {
            $('#edit_period').css('display', 'flex');
            $('#edit_one_day').css('display', 'none');
            $('.edit_one_day').val('');
        }
    });

    function editCondition() {
        let condition_id = $(this).attr('data-condition');
        $('#edit-modal').attr('data-condition', condition_id);
        let conditionsEntity = conditions.find((el) => el.id == condition_id);

        $('#edit_condition_name').val(conditionsEntity.key).trigger('change');
        $("input[name='edit_select_period'][value='" + conditionsEntity.period + "']").prop(
            'checked',
            true
        );

        if (conditionsEntity.period === 'period') {
            $('#edit_period').css('display', 'flex');
            $('#edit_one_day').css('display', 'none');
            $('.edit_one_day').val('');
            $('#edit_date-1').val(conditionsEntity.date_from);
            $('#edit_date-2').val(conditionsEntity.date_to);
        } else {
            $('#edit_one_day').css('display', 'flex');
            $('#edit_period').css('display', 'none');
            $('#edit_date-1').val('');
            $('#edit_date-2').val('');
            $('.edit_one_day').val(conditionsEntity.date_from);
        }

        if (
            conditionsEntity.hasOwnProperty('work_from') &&
            conditionsEntity.hasOwnProperty('work_to')
        ) {
            $('#edit_work_from').val(conditionsEntity.work_from);
            $('#edit_work_to').val(conditionsEntity.work_to);
        }
        if (
            conditionsEntity.hasOwnProperty('break_from') &&
            conditionsEntity.hasOwnProperty('break_to')
        ) {
            $('#edit_break_from').val(conditionsEntity.break_from);
            $('#edit_break_to').val(conditionsEntity.break_to);
        }

        $('#edit-modal').modal('toggle');
    }

    function editConditionBack() {
        let condition_id = $(this).attr('data-condition');

        $('#edit-modal').attr('data-condition', condition_id);
        let conditionsEntity = conditions.find((el) => el.id == condition_id);

        $('#edit_condition_name').val(conditionsEntity.type.key);
        $('#edit_condition_name').trigger('change');

        const { date_from, date_to } = conditionsEntity;
        if (date_from && date_to) {
            $("input[name='edit_select_period'][value='" + 'period' + "']").prop('checked', true);
            $('#edit_period').css('display', 'flex');
            $('#edit_one_day').css('display', 'none');
            $('.edit_one_day').val('');
            $('#edit_date-1').val(date_from);
            $('#edit_date-2').val(date_to);
        } else {
            $("input[name='edit_select_period'][value='" + 'one_day' + "']").prop('checked', true);
            $('#edit_one_day').css('display', 'flex');
            $('#edit_period').css('display', 'none');
            $('#edit_date-1').val('');
            $('#edit_date-2').val('');
            $('.edit_one_day').val(date_from);
        }

        if (
            conditionsEntity.hasOwnProperty('work_from') &&
            conditionsEntity.hasOwnProperty('work_to')
        ) {
            $('#edit_work_from').val(conditionsEntity.work_from);
            $('#edit_work_to').val(conditionsEntity.work_to);
        }
        if (
            conditionsEntity.hasOwnProperty('break_from') &&
            conditionsEntity.hasOwnProperty('break_to')
        ) {
            $('#edit_break_from').val(conditionsEntity.break_from);
            $('#edit_break_to').val(conditionsEntity.break_to);
        }

        $('#edit-modal').modal('toggle');
    }

    $('.edit-condition-back').on('click', (event) => {
        editConditionBack.call(event.currentTarget);
    });

    $('.edit-condition').on('click', function () {
        editCondition();
    });

    function deleteCondition() {
        let condition_id = $(this).attr('data-condition');
        const foundIndex = conditions.findIndex((el) => el.id == condition_id);
        conditions.splice(foundIndex, 1);
        $('#record_' + condition_id).remove();
        updateConditionsUI();
    }

    $('.delete-condition-back').on('click', function (e) {
        deleteConditionBack.bind(this)();
    });

    function deleteConditionBack() {
        const btn = $(this)[0];
        let condition_id = $(btn).attr('data-condition');
        const foundIndex = conditions.findIndex((el) => el.id == condition_id);

        conditions.splice(foundIndex, 1);
        $('#record_' + condition_id).remove();
        updateConditionsUI();
    }

    $('.delete-condition').on('click', function () {
        deleteCondition();
    });

    $('#condition_name').on('change', function () {
        var oneDayInput = $('#one_day input');
        var periodInputs = $('#period input');

        if (
            oneDayInput.val() !== '' ||
            (periodInputs.eq(0).val() !== '' && periodInputs.eq(1).val() !== '')
        ) {
            $('#condition_submit').prop('disabled', false);
        } else {
            $('#condition_submit').prop('disabled', true);
        }
    });

    $('#one_day input, #period input').on('change', function () {
        var conditionName = $('#condition_name');
        var oneDayInput = $('#one_day input');
        var periodInputs = $('#period input');

        if (
            conditionName.val() !== '' &&
            (oneDayInput.val() !== '' ||
                (periodInputs.eq(0).val() !== '' && periodInputs.eq(1).val() !== ''))
        ) {
            $('#condition_submit').prop('disabled', false);
        } else {
            $('#condition_submit').prop('disabled', true);
        }
    });

    $('#edit-modal')[0].addEventListener('hidden.bs.modal', function () {
        clearUpdatePopUp();
    });

    function updateConditionsUI() {
        var conditionsLength = conditions.length;
        var headerElement = $('#card-header-conditions');
        var paragraphElement = headerElement.find('p');
        var blockWithBtn = headerElement.find('div');

        if (conditionsLength > 0) {
            headerElement.removeClass('d-flex flex-column align-items-center my-auto gap-2');
            headerElement.addClass('card-header row');
            paragraphElement.addClass('d-none');
            blockWithBtn.addClass('col-2');
        } else {
            headerElement.removeClass('card-header row');
            headerElement.addClass('d-flex flex-column align-items-center my-auto gap-2');
            paragraphElement.removeClass('d-none');
            blockWithBtn.removeClass('col-2');
        }
        // console.log('conditions: ', conditions);
    }

    function checkHoursInSchedule(blockClass) {
        let errorMessageShown = false;
        let hasErrors = false;

        let selectedOption = $('#condition_name').find('option:selected');

        const isShortDay = selectedOption.val() === 'short_day';

        $(blockClass).each(function () {
            const block = $(this);

            // ❗ не перевіряємо приховані блоки
            if (!block.is(':visible')) return;

            const inputs = block.find('input');

            // чистимо попередні помилки
            inputs.removeClass('border-error');

            const rawStart = (inputs.eq(0).val() || '').trim();
            const rawEnd = (inputs.eq(1).val() || '').trim();

            const start = rawStart ? toMinutes(rawStart) : null;
            const end = rawEnd ? toMinutes(rawEnd) : null;

            const isStartFilled = rawStart.length > 0;
            const isEndFilled = rawEnd.length > 0;

            /* ---------- short_day ---------- */
            if (isShortDay) {
                if (!isStartFilled || !isEndFilled) {
                    inputs.addClass('border-error');

                    if (!errorMessageShown) {
                        alert(getLocalizedText('scheduleMustFilled'));
                        errorMessageShown = true;
                    }

                    hasErrors = true;
                    return;
                }
            }

            // 👉 базова логіка (як було раніше)
            if (start !== null && end !== null && start >= end) {
                inputs.eq(0).addClass('border-error');
                inputs.eq(1).addClass('border-error');

                if (!errorMessageShown) {
                    alert(getLocalizedText('invalidHours'));
                    errorMessageShown = true;
                }

                hasErrors = true;
            }
        });

        return hasErrors;
    }

    function toMinutes(time) {
        if (!time) return null;
        const [h, m] = time.split(':');
        return parseInt(h) * 60 + parseInt(m);
    }

    $('.two-input-for-schedule-inmodal').each(function () {
        const inputs = $(this).find('input');

        inputs.eq(0).on('input', function () {
            if (parseInt(inputs.eq(0).val()) < parseInt(inputs.eq(1).val())) {
                inputs.eq(0).removeClass('border-error');
                inputs.eq(1).removeClass('border-error');
            }
        });

        inputs.eq(1).on('input', function () {
            if (parseInt(inputs.eq(0).val()) < parseInt(inputs.eq(1).val())) {
                inputs.eq(0).removeClass('border-error');
                inputs.eq(1).removeClass('border-error');
            }
        });
    });

    $('.two-input-for-schedule').each(function () {
        const inputs = $(this).find('input');

        inputs.eq(0).on('input', function () {
            if (parseInt(inputs.eq(0).val()) < parseInt(inputs.eq(1).val())) {
                inputs.eq(0).removeClass('border-error');
                inputs.eq(1).removeClass('border-error');
            }
        });

        inputs.eq(1).on('input', function () {
            if (parseInt(inputs.eq(0).val()) < parseInt(inputs.eq(1).val())) {
                inputs.eq(0).removeClass('border-error');
                inputs.eq(1).removeClass('border-error');
            }
        });
    });
});
