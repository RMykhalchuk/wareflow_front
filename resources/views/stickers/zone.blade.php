@extends('layouts.empty')
@section('title', 'Контейнер')

@section('content')
    <style>
        /* ==== Основні параметри стикера ==== */
        .sticker-wrapper {
            width: 100%;
            max-width: 100mm;
            aspect-ratio: 100 / 60;
            background: #fff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 3mm;
        }

        /* ==== Контент усередині ==== */
        .sticker-content {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            justify-content: space-between;
            height: 100%;
        }

        .sticker-left {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex: 0 0 40%;
        }

        .sticker-right {
            flex: 0 0 58%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ==== Тексти ==== */
        h1 {
            font-size: clamp(8px, 3vw, 16px);
            line-height: 1.1;
            margin: 0;
        }

        h3 {
            font-size: clamp(6px, 2.2vw, 12px);
            line-height: 1.2;
            margin: 0;
        }

        h4 {
            font-size: clamp(5px, 1.8vw, 10px);
            line-height: 1.2;
            margin: 0;
        }

        .fw-bolder {
            font-weight: 700 !important;
        }

        /* ==== Зображення ==== */
        img {
            max-width: 100%;
            height: auto;
        }

        .qr-img {
            width: 98%;
            height: auto;
            object-fit: contain;
        }

        .logo-img {
            max-width: 24%;
            height: auto;
        }
    </style>

    <div
        class="d-flex justify-content-center align-items-center min-vh-100 bg-secondary-subtle p-2"
    >
        <div class="sticker-wrapper">
            <div class="sticker-content gap-25">
                <div class="sticker-left gap-50">
                    <div class="d-flex gap-50 flex-column">
                        <div class="d-flex gap-25 flex-column">
                            <h3 class="text-black mb-0">Підлога зони</h3>
                            <h1 class="fw-bolder mb-0 text-black d-inline-flex flex-column">
                                Приймання
                            </h1>
                        </div>

                        <div class="d-flex gap-25 flex-column">
                            <h4 class="text-black mb-0">Склад</h4>
                            <h3 class="text-black mb-0 fw-bolder">Пасіки 1</h3>
                        </div>

                        <div class="d-flex gap-25 flex-column">
                            <h4 class="text-black mb-0">Тип</h4>
                            <h3 class="text-black mb-0 fw-bolder">Європалета</h3>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-25">
                        <img
                            src="{{ asset('assets/icons/entity/stickers/logo.svg') }}"
                            alt="Wareflow Logo"
                            class="logo-img"
                        />
                        <span
                            class="fw-bolder text-black"
                            style="font-size: clamp(6px, 2.2vw, 12px)"
                        >
                            Wareflow
                        </span>
                    </div>
                </div>

                <div class="sticker-right">
                    <img
                        src="{{ asset('assets/icons/entity/stickers/qr.svg') }}"
                        alt="QR"
                        class="qr-img"
                    />
                </div>
            </div>
        </div>
    </div>
@endsection
