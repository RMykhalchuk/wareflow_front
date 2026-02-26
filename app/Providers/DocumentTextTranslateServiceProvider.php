<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

final class DocumentTextTranslateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

//        $docTypesSystemBlockNames = [
//
//            // Request for transportation
//            'Основна інформація' => 'documents_block_name_1',
//            'Вимоги до транспорту' => 'documents_block_name_2',
//            'Інформація про вантаж' => 'documents_block_name_3',
//            'Відвантаження' => 'documents_block_name_4',
//            'Розвантаження' => 'documents_block_name_5',
//            'Умови доставки' => 'documents_block_name_6',
//
//            // Cargo Request
//            'Інформація про маршрут' => 'documents_block_name_7',
//
//            // Order
//            'Шапка' => 'documents_block_name_8',
//        ];
//
//        View::share('docTypesSystemBlockNames', $docTypesSystemBlockNames);

    }

    #[\Override]
    public function register()
    {
        //
    }
}
