import { getLocalizedText } from '../localization/user/getLocalizedText.js';

$(document).ready(function () {
    const prevWeekButton = $('#prevWeekButton');
    const nextWeekButton = $('#nextWeekButton');
    const currentDateColumn = $('#currentMonthYear');

    // Початкова дата
    let currentDate = new Date();

    const dayNameMap = {
        [getLocalizedText('short_monday')]: 'Monday',
        [getLocalizedText('short_tuesday')]: 'Tuesday',
        [getLocalizedText('short_wednesday')]: 'Wednesday',
        [getLocalizedText('short_thursday')]: 'Thursday',
        [getLocalizedText('short_friday')]: 'Friday',
        [getLocalizedText('short_saturday')]: 'Saturday',
        [getLocalizedText('short_sunday')]: 'Sunday',
    };

    // Функція для отримання повного імені місяця
    function getMonthFullName(month) {
        const monthNames = [
            getLocalizedText('January'),
            getLocalizedText('February'),
            getLocalizedText('March'),
            getLocalizedText('April'),
            getLocalizedText('May'),
            getLocalizedText('June'),
            getLocalizedText('July'),
            getLocalizedText('August'),
            getLocalizedText('September'),
            getLocalizedText('October'),
            getLocalizedText('November'),
            getLocalizedText('December'),
        ];
        return monthNames[month];
    }

    // Функція для отримання скороченого імені місяця
    function getMonthShortName(month) {
        const monthNames = [
            getLocalizedText('short_january'),
            getLocalizedText('short_february'),
            getLocalizedText('short_march'),
            getLocalizedText('short_april'),
            getLocalizedText('short_may'),
            getLocalizedText('short_june'),
            getLocalizedText('short_july'),
            getLocalizedText('short_august'),
            getLocalizedText('short_september'),
            getLocalizedText('short_october'),
            getLocalizedText('short_november'),
            getLocalizedText('short_december'),
        ];
        return monthNames[month];
    }

    function findScheduleForDay(dayName) {
        let fullDayName = dayNameMap[dayName];
        return schedule.find((item) => item.weekday === fullDayName);
    }

    function getMonthName(month, year) {
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();

        let startDayOfWeek = new Date(currentDate);
        startDayOfWeek.setDate(currentDate.getDate() - (currentDate.getDay() || 7) + 1); // Понеділок поточного тижня
        let startDay = startDayOfWeek.getDate();

        let endDayOfWeek = new Date(startDayOfWeek);
        endDayOfWeek.setDate(startDayOfWeek.getDate() + 6); // Неділя поточного тижня
        let endDay = endDayOfWeek.getDate();

        let startMonth = startDayOfWeek.getMonth();
        let endMonth = endDayOfWeek.getMonth();
        let startYear = startDayOfWeek.getFullYear();
        let endYear = endDayOfWeek.getFullYear();

        if (startMonth !== endMonth || startYear !== endYear) {
            // Якщо тиждень охоплює дні з різних місяців або років
            let startMonthName = getMonthShortName(startMonth);
            let endMonthName = getMonthShortName(endMonth);
            return `<span class='text-dark'>${startDay} ${startMonthName} ${startYear !== currentYear ? ' ' + startYear : ''} - ${endDay} ${endMonthName}${endYear !== currentYear ? ' ' + endYear : ''}</span> ${year}`;
        } else if (year < currentYear || (month === 0 && year === currentYear - 1)) {
            return `<span class='text-dark'>${startDay} - ${endDay} ${getMonthFullName(month)}  ${year}`;
        } else {
            return `<span class='text-dark'>${startDay} - ${endDay} ${getMonthFullName(month)} </span> ${year}`;
        }
    }

    // Функція для отримання назви дня тижня
    function getDayOfWeekName(dayOfWeek) {
        const dayNames = [
            getLocalizedText('short_monday'),
            getLocalizedText('short_tuesday'),
            getLocalizedText('short_wednesday'),
            getLocalizedText('short_thursday'),
            getLocalizedText('short_friday'),
            getLocalizedText('short_saturday'),
            getLocalizedText('short_sunday'),
        ];

        return dayNames[dayOfWeek];
    }

    function formatDate(date) {
        var d = new Date(date),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) month = '0' + month;
        if (day.length < 2) day = '0' + day;

        return [year, month, day].join('-');
    }

    function isDateInRange(dateToCheck, startDate, endDate) {
        let date = new Date(dateToCheck);
        let start = new Date(startDate);
        let end = endDate ? new Date(endDate) : start;
        return date >= start && date <= end;
    }

    function generateDayColumn(scheduleForDay, dayOfWeek, isWeekend) {
        let dayIndex = dayOfWeek.getDay() === 0 ? 6 : dayOfWeek.getDay() - 1;
        let dayName = getDayOfWeekName(dayIndex);
        let dayCellText =
            scheduleForDay && !scheduleForDay.is_day_off
                ? dayName + ' ' + dayOfWeek.getDate()
                : `<span class="text-danger">${dayName} ${dayOfWeek.getDate()}</span>`;

        let dayCell = $('<td class="text-nowrap col-3">').html(dayCellText);

        // if (isWeekend) {
        //     dayCell.addClass('text-danger');
        // }
        return dayCell;
    }

    // Функція для генерації колонки робочого дня з урахуванням спеціальних умов
    function generateWorkdayColumn(scheduleForDay, conditionForDay, isWeekend) {
        let workdayCellText;
        if (
            conditionForDay &&
            !scheduleForDay.is_day_off &&
            (conditionForDay.type_id === 1 ||
                conditionForDay.type_id === 2 ||
                conditionForDay.type_id === 4)
        ) {
            // Якщо є спеціальна умова, використовуємо часи з цієї умови
            workdayCellText = '--';
        } else if (conditionForDay && !scheduleForDay.is_day_off && conditionForDay.type_id === 3) {
            // Якщо є спеціальна умова, використовуємо часи з цієї умови
            workdayCellText = conditionForDay.work_from + ' - ' + conditionForDay.work_to;
        } else if (scheduleForDay && !scheduleForDay.is_day_off) {
            // Якщо немає спеціальної умови, але день не є вихідним
            workdayCellText = scheduleForDay.start_at + ' - ' + scheduleForDay.end_at;
        } else {
            // Якщо день є вихідним
            workdayCellText =
                '<span class="text-danger fw-bold">' + getLocalizedText('weekend') + '</span>';
        }

        let workdayCell = $('<td class="text-nowrap col-4">').html(workdayCellText);
        // if (isWeekend && !conditionForDay) {
        //     workdayCell.addClass('text-danger fw-bold');
        // }
        return workdayCell;
    }

    // Функція для генерації колонки обіду з урахуванням спеціальних умов
    function generateDinnerColumn(scheduleForDay, conditionForDay, isWeekend) {
        let dinnerCellText;
        if (
            conditionForDay &&
            !scheduleForDay.is_day_off &&
            (conditionForDay.type_id === 1 ||
                conditionForDay.type_id === 2 ||
                conditionForDay.type_id === 4)
        ) {
            // Якщо є спеціальна умова, використовуємо часи з цієї умови
            dinnerCellText = '--';
        } else if (conditionForDay && !scheduleForDay.is_day_off && conditionForDay.type_id === 3) {
            // Якщо є спеціальна умова, використовуємо часи обідньої перерви з цієї умови
            dinnerCellText = conditionForDay.break_from + ' - ' + conditionForDay.break_to;
        } else if (scheduleForDay && !scheduleForDay.is_day_off) {
            // Якщо немає спеціальної умови, але день не є вихідним
            dinnerCellText = scheduleForDay.break_start_at + ' - ' + scheduleForDay.break_end_at;
        } else {
            // Якщо день є вихідним
            dinnerCellText =
                '<span class="text-danger fw-bold">' + getLocalizedText('weekend') + '</span>';
        }

        let dinnerCell = $('<td class="text-nowrap col-4">').html(dinnerCellText);
        // if (isWeekend && !conditionForDay) {
        //     dinnerCell.addClass('text-danger fw-bold');
        // }
        return dinnerCell;
    }

    // Функція для генерації колонки спеціальних умов
    function generateConditionColumn(formattedDate, scheduleForDay, isWeekend) {
        let conditionCell = $('<td class="text-nowrap col-1">');
        let conditionForDay = conditions.find((condition) =>
            isDateInRange(formattedDate, condition.date_from, condition.date_to)
        );

        if (conditionForDay && !isWeekend && !scheduleForDay.is_day_off) {
            let conditionName = exceptionsArray[conditionForDay.type_id];

            const conditionNameTranslate = {
                Вихідний: getLocalizedText('weekendCondition'),
                Лікарняний: getLocalizedText('sickLeaveCondition'),
                'Cкорочений день': getLocalizedText('shortDayCondition'),
                'Державний вихідний': getLocalizedText('publicHolidayCondition'),
            };

            let conditionDiv = `<div class="d-flex align-items-center" style="width: 30px; height: 30px">
            <div class="js-td-condition avatar-group">
                <div data-bs-toggle="tooltip" data-popup="tooltip-custom"
                     data-bs-placement="top" class="border-radius-50 js-tooltip-custom pull-up my-0"
                     title="${conditionNameTranslate[conditionName]}">
                        <img src="${window.location.origin}/assets/icons/utils/info-circle-condition.svg" alt="info-circle"/>
                </div>
            </div>
        </div>`;
            conditionCell.html(conditionDiv);
        }
        return conditionCell;
    }

    // Функція для оновлення календаря
    function updateCalendar() {
        currentDateColumn.html(getMonthName(currentDate.getMonth(), currentDate.getFullYear()));
        $('.css-calendar-table tbody').empty(); // Очищаємо таблицю

        for (let i = 0; i < 7; i++) {
            let dayOfWeek = new Date(currentDate);
            dayOfWeek.setDate(currentDate.getDate() - (currentDate.getDay() || 7) + 1 + i);
            let isWeekend = dayOfWeek.getDay() === 6 || dayOfWeek.getDay() === 0; // Перевіряємо, чи це субота або неділя

            let shortDayName = getDayOfWeekName(
                dayOfWeek.getDay() === 0 ? 6 : dayOfWeek.getDay() - 1
            );
            let scheduleForDay = findScheduleForDay(shortDayName);
            let conditionForDay = conditions.find((condition) =>
                isDateInRange(formatDate(dayOfWeek), condition.date_from, condition.date_to)
            );

            let dayCell = generateDayColumn(scheduleForDay, dayOfWeek, isWeekend);
            let workdayCell = generateWorkdayColumn(scheduleForDay, conditionForDay, isWeekend);
            let dinnerCell = generateDinnerColumn(scheduleForDay, conditionForDay, isWeekend);
            let conditionCell = generateConditionColumn(
                formatDate(dayOfWeek),
                scheduleForDay,
                isWeekend
            );

            let row = $('<tr style="height: 55px">').append(
                dayCell,
                workdayCell,
                dinnerCell,
                conditionCell
            );
            $('.css-calendar-table tbody').append(row);
        }
        // Ініціалізація tooltips для динамічних елементів
        $('.css-calendar-table .js-tooltip-custom').tooltip();
    }

    // Кнопка "Минулий тиждень"
    prevWeekButton.click(function () {
        currentDate.setDate(currentDate.getDate() - 7);
        updateCalendar();
    });

    // Кнопка "Наступний тиждень"
    nextWeekButton.click(function () {
        currentDate.setDate(currentDate.getDate() + 7);
        updateCalendar();
    });

    // Оновлення календаря при завантаженні сторінки
    updateCalendar();
});
