let selectedRegulation = null;

export function getSelectedRegulation() {
    console.log(selectedRegulation);
    return selectedRegulation;
}

export function setSelectedRegulation(id) {
    selectedRegulation = id;
}

export function checkTrack() {
    // считуємо зміни в регламенті (та розблоковуємо кнопку)
    $('.accordion-item input, .accordion-item select').change(function () {
        var isChecked = $(this).is(':checked');
        if (isChecked) {
            $('#btn-cancel-changes').removeClass('d-none');
        }
        $('#btn-cancel-changes').removeClass('d-none');
        $('#btn-save').attr('data-bs-toggle', 'modal');
        $('#btn-save').attr('data-bs-target', '#amendedChangesModal');

        $('#btn-sign').attr('data-bs-toggle', 'modal');
        $('#btn-sign').attr('data-bs-target', '#amendedChangesModal');
    });
}
