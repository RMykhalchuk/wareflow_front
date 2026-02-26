// DarkReader.enable({
//     brightness: 100,
//     contrast: 90,
//     sepia: 10
// });
//
// DarkReader.disable();
//
// // Enable when the system color scheme is dark.
// DarkReader.auto({
//     brightness: 100,
//     contrast: 90,
//     sepia: 10
// });
//
//
DarkReader.setFetchMethod(window.fetch);
// // Отримуємо значення з локального сховища
// const currentSkin = localStorage.getItem('current-skin');
//
// if (currentSkin === "light") {
//     DarkReader.disable();
// } else if (currentSkin === "dark") {
//     DarkReader.enable();
// }

// $('.nav-link-style').on('click', function () {
//     if (currentSkin === "light") {
//         DarkReader.auto(true);
//     } else {
//         DarkReader.auto(false);
//     }
// })
// // Get the generated CSS of Dark Reader returned as a string.
// const CSS = await DarkReader.exportGeneratedCSS();
//
// // Check if Dark Reader is enabled.
// const isEnabled = DarkReader.isEnabled();
