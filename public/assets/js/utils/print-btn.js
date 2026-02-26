export function setupPrintButton(btnID, contentId) {
    // Перевіряємо, чи `btnID` є селектором, або ж переданим як конкретний `id`
    let buttons;

    if (btnID.includes('[id^=')) {
        // Якщо `btnID` містить селектор для початкового значення `id`, то використовуємо `querySelectorAll`
        buttons = document.querySelectorAll(btnID);
    } else {
        // В іншому випадку, припускаємо, що це конкретний `id`, і отримуємо відповідний елемент
        buttons = [document.getElementById(btnID)];
    }

    buttons.forEach(function (button) {
        if (button) {
            // Додаємо обробник подій для кожної кнопки друку
            button.addEventListener('click', function () {
                // Викликаємо функцію друку вікна браузера
                window.print();
            });

            // CSS правила для друку
            var css = `
                @media print {
                    .header-navbar, .horizontal-menu-wrapper, .header-navbar-shadow, .js-breadcrumb-wrapper {
                        display: none!important;
                    }

                    body{
                        background-color: #ffffff!important;
                    }

                    .content-body{
                        padding-top:20px
                    }

                    #${contentId}, #${button.id} {
                        display: block !important;
                    }

                    .js-show-all{
                        max-height: max-content !important;
                        overflow-y: visible !important;
                    }
                }
            `;

            // Створюємо тег <style> та додаємо стилі до сторінки
            var style = document.createElement('style');
            style.type = 'text/css';
            if (style.styleSheet) {
                style.styleSheet.cssText = css;
            } else {
                style.appendChild(document.createTextNode(css));
            }
            document.head.appendChild(style);
        }
    });
}
