<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <style>
            /** Dompdf-friendly PDF template: each label = one page 100x60 mm **/

            @page {
                margin: 0;
                size: 100mm 60mm;
            }

            html,
            body {
                margin: 0;
                padding: 0;
                width: 100mm;
                height: 60mm;
                font-family: 'DejaVu Sans', Helvetica, sans-serif;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .page {
                box-sizing: border-box;
                width: 97mm;
                height: 57mm;
                padding: 3mm 0 0 3mm;
                background: #ffffff;
                page-break-after: auto;
            }
            .page:not(:last-of-type) {
                page-break-after: always;
            }

            .sticker-wrapper {
                width: 90%;
                height: 100%;
                table-layout: fixed;
            }

            .sticker-left {
                width: 33%;
                vertical-align: top;
                border: none;
            }

            .label-type {
                font-size: 7pt; /* зменшено */
                line-height: 7pt;
                margin: 0 0 0 0;
                color: #222;
            }

            .label-code {
                font-size: 10pt; /* зменшено */
                line-height: 10pt;
                font-weight: 700;
                margin: 0 0 0 0;
                color: #000;
            }

            .meta-row {
                /*margin-top: 3px;*/
            }

            .meta-title {
                font-size: 6pt; /* трохи менше */
                line-height: 6pt;
                font-weight: 400;
                margin: 0;
                color: #333;
            }

            .meta-value {
                font-size: 9pt; /* трохи менше */
                line-height: 9pt;
                font-weight: 700;
                margin: 0;
                color: #000;
            }

            .footer-cell {
                vertical-align: bottom;
                border: none;
                padding-bottom: 5mm;
            }
            .footer img {
                margin-left: 0;
                float: left;
                max-width: 20px;
            }

            .brand {
                font-size: 6pt;
                font-weight: 700;
                color: #000;
                float: left;
                margin-top: 3px;
                margin-left: 4px;
            }

            .sticker-right {
                width: 69%;
                text-align: center;
                vertical-align: middle;
                border: none;
            }

            .qr-wrap {
                width: 100%;
                max-width: 54mm;
                padding-top: 0;
            }

            .qr {
                width: 100%;
                height: auto;
                object-fit: contain;
                display: block;
                margin: 0 auto;
            }

            @media screen {
                /*.page { box-shadow: 0 0 6px rgba(0,0,0,0.08); margin: 8px; }*/
            }
        </style>
    </head>
    <body>
        @foreach ($labels as $label)
            <div class="page">
                <table class="sticker-wrapper" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="sticker-left">
                            <div>
                                <div class="label-top">
                                    <div class="label-type">Контейнер</div>
                                    <div class="label-code">
                                        {{ $label['code_format'] ?? ($label->code_format ?? '') }}
                                    </div>
                                    <div class="label-code">
                                        {{ $label['code'] ?? ($label->code ?? '') }}
                                    </div>
                                </div>

                                <div class="meta-row">
                                    <p class="meta-title">Назва</p>
                                    <p class="meta-value">
                                        {{ $label['name'] ?? ($label->name ?? '-') }}
                                    </p>
                                </div>

                                <div class="meta-row">
                                    <p class="meta-title">Тип</p>
                                    <p class="meta-value">
                                        {{ $label['type_name'] ?? ($label->type_name ?? '-') }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="sticker-right" rowspan="2">
                            <div class="qr-wrap">
                                <img src="{{ $label['qr'] }}" alt="QR" class="qr" />
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer-cell">
                            <div class="footer">
                                <img
                                    src="{{ $logoBase64 }}"
                                    alt="Wareflow Logo"
                                    class="logo-img"
                                />
                                <div class="brand">Wareflow</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        @endforeach
    </body>
</html>
