$('#jqx-tabs-content').addClass('invisible');

$('#jqxLoader').jqxLoader({ width: 250, height: 150, autoOpen: true });

// Затримка для приховання лоадера після ініціалізації jqxTabs
setTimeout(function () {
    $('#jqxLoader').jqxLoader('close');
    $('#tabs').removeClass('invisible');
}, 50); // Задайте відповідну затримку, залежно від складності завантаження
