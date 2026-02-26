import js from "@eslint/js";
import globals from "globals";
import { defineConfig } from "eslint/config";

export default defineConfig([
    {
        files: ["public/assets/js/**/*.{js,mjs,cjs}"],
        plugins: { js },
        extends: ["js/recommended"],
    },
    {
        files: ["public/assets/js/**/*.{js,mjs,cjs}"],
        languageOptions: {
            globals: {
                ...globals.browser,
                $: "readonly",
                jQuery: "readonly",
                // checkUrl: "readonly",
                // getLocalization: "readonly",
                // sku_id: "readonly",
                // barcodeData: "readonly",
                // flatpickr: "readonly",
                // L: "readonly",
                // coordinates: "readonly",
                // coordinatesLoad: "readonly",
                // marker: "readonly",
                // bootstrap: "readonly",
                // Cropper: "readonly",
                // DarkReader: "readonly",
                // wNumb: "readonly",
                // noUiSlider: "readonly",
                //
                // // ✅ jqWidgets підтримка:
                // jqxBaseFramework: "readonly",
                // jqx: "readonly",
                // JQXLite: "readonly",
                // jqxWidget: "readonly",
                // jqwidgets: "readonly",
                // jqxGrid: "readonly",
                // jqxDropDownList: "readonly",
                // jqxComboBox: "readonly",
            },
        },
    },
]);
