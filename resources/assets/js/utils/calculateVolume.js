export function calculateVolume() {
    // Отримати значення довжини, ширини та висоти
    var length = parseFloat(document.getElementById('length').value);
    var width = parseFloat(document.getElementById('width').value);
    var height = parseFloat(document.getElementById('height').value);

    // Перевірити, чи введені значення коректні числа
    if (isNaN(length) || isNaN(width) || isNaN(height)) {
        // Якщо не коректні, не розраховувати об'єм
        //document.getElementById('volume').value = 'Невірні дані';
        return;
    }

    // Розрахунок об'єму (об'єм = довжина * ширина * висота)
    var volume = length * width * height;

    // Вивід результату у відповідне поле
    document.getElementById('volume').value = volume.toFixed(1); // Округлення до 1 знака після коми
}
