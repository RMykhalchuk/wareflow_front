import { pagerRenderer } from '../components/pager.js';
import { toolbarRender } from '../components/toolbar-advanced.js';
import { listbox } from '../components/listbox.js';
import { hover } from '../components/hover.js';
import { getLocalizedText } from '../../localization/sku/getLocalizedText.js';
import { switchLang } from '../components/switch-lang.js';
import { hideLoader, showLoader } from '../components/loader.js';

$(document).ready(function () {
    let table = $('#sku-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'id', type: 'string' },
        { name: 'local_id', type: 'string' },
        { name: 'name', type: 'string' },
        { name: 'barcode', type: 'string' },
        // { name: 'status', type: 'string' },
        { name: 'category', type: 'string' },
        { name: 'manufacturer', type: 'string' },
        { name: 'country', type: 'string' },
        { name: 'brand', type: 'string' },
        { name: 'is_kit', type: 'bool' },
        { name: 'action', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'json',
        datafields: dataFields,
        url: window.location.origin + languageBlock + '/sku/table/filter',
        root: 'data',
        beforeprocessing: function (data) {
            console.log(data);
            myDataSource.totalrecords = data.total;
        },
        filter: function () {
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'filter');
        },
        sort: function () {
            // update the grid and send a request to the server.
            table.jqxGrid('updatebounddata', 'sort');
        },
    };
    showLoader(); // ПЕРЕД створенням dataAdapter

    let dataAdapter = new $.jqx.dataAdapter(myDataSource, {
        loadComplete: function () {
            hideLoader();
        },
        loadError: function (xhr, status, error) {
            hideLoader();
            // console.error('Load error:', status, error);
        },
    });

    var grid = table.jqxGrid({
        theme: 'light-wms sku-table-custom',
        width: '100%',
        autoheight: true,
        pageable: true,
        source: dataAdapter,
        showdefaultloadelement: false,
        pagerRenderer: function () {
            return pagerRenderer(table);
        },
        virtualmode: true,
        rendergridrows: function () {
            return dataAdapter.records;
        },
        ready() {
            checkUrl();
        },
        sortable: false,
        columnsResize: false,
        filterable: false,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: false,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 50,
        filterbarmode: 'simple',
        toolbarHeight: 55,
        showToolbar: true,
        filter: function () {
            var columnindex = table.jqxGrid('getcolumnindex', 'Action');

            var filterinfo = table.jqxGrid('getfilterinformation')[columnindex];

            // Disable filtering for the "Name" column
            if (filterinfo != null && filterinfo.filter != null) {
                filterinfo.filter.setlogic('and');
                filterinfo.filter.setoperator(0);
                filterinfo.filter.setvalue('');
            }
        },
        rendertoolbar: function (statusbar) {
            var columns = table.jqxGrid('columns').records;
            var columnCount = columns.length;

            return toolbarRender(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'local_id',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('id'),
                width: 70,
                editable: false,
                cellsrenderer: function (row, column, value, rowdata) {
                    return `<p style="" class="text-secondary ps-50 my-auto">${value}</p>`;
                },
            },
            {
                minwidth: 250,
                dataField: 'name_and_code',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('name'), // або 'Name + Code'
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const name = rowdata.name;

                    const barcodes = Array.isArray(rowdata.barcode)
                        ? rowdata.barcode.join(', ')
                        : '';

                    return `<div class="d-flex flex-column ps-50">
                                <a class="text-dark fw-bolder my-auto" href='${window.location.origin + languageBlock}/sku/${rowdata.id}'>${name}</a>
                                ${barcodes ? `<span class="text-secondary">${barcodes}</span>` : ''}
                            </div>`;
                },
            },
            // {
            //     dataField: 'status',
            //     align: 'left',
            //     text: getLocalizedText('status'),
            //     width: 160,
            //     editable: false,
            //     cellsrenderer: (row, column, value, rowData) => {
            //         const isActive = value?.value === 1;
            //         const badgeClass = isActive ? 'bg-light-success' : 'bg-light-danger';
            //
            //         // Визначаємо текст в залежності від мови
            //         const language = switchLang();
            //         const badgeText =
            //             language === 'en' ? value?.label_en || '' : value?.label || '';
            //
            //         return `<span class="badge ${badgeClass} text-white ms-50 py-50 px-1 text-capitalize">${badgeText}</span>`;
            //     },
            // },
            {
                width: 350,
                dataField: 'category',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('category'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        'Продукти харчування, б/а напої без дотримання температурного режиму': {
                            text: getLocalizedText('category_product'),
                        },
                        'Продукти харчування з дотриманням температурного режиму': {
                            text: getLocalizedText('category_product_t'),
                        },
                        'Побутові та господарські товари': {
                            text: getLocalizedText('category_pobut'),
                        },
                        'Продукція видобувної промисловості': {
                            text: getLocalizedText('category_vydub_prom'),
                        },
                        'Текстильні товари': { text: getLocalizedText('category_textile') },
                        'Будівельні матеріали, інструменти, сировина для будівництва, сантехніка': {
                            text: getLocalizedText('category_bud_material'),
                        },
                        'Поліграфічна продукція': {
                            text: getLocalizedText('category_poligraph_prod'),
                        },
                        'Спортивне приладдя та аксесуари для відпочинку': {
                            text: getLocalizedText('category_sport_prylad'),
                        },
                        'Нафтопродукти ADR': { text: getLocalizedText('category_naft_prod') },
                        'Матеріали штучного походження(синтетичні, гумові, пластмасові)': {
                            text: getLocalizedText('category_material_synt'),
                        },
                        'Вироби зі скла, фарфору, кераміки та інший крихкий вантаж': {
                            text: getLocalizedText('category_vyroby_zi_skla'),
                        },
                        'Електротехніка, деталі до електричних приладів, аксесуари': {
                            text: getLocalizedText('category_elektronika'),
                        },
                        Меблі: { text: getLocalizedText('category_mebli') },
                        'Природна сировина': { text: getLocalizedText('category_pryrodna_s') },
                        'Цінні матеріали': { text: getLocalizedText('category_tsinni_mat') },
                        'Інші види вантажів, не віднесені до попередніх угруповань': {
                            text: getLocalizedText('category_other'),
                        },
                        Сировина: { text: getLocalizedText('category_raw') },
                    };

                    const translateValue = valuesInCell[value] || { text: value };

                    return `<p class="ps-50 text-secondary text-truncate my-auto" title="${translateValue.text || value}"> ${translateValue.text || value} </p>`;
                },
            },
            {
                width: 200,
                dataField: 'manufacturer',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('manufacturer'),
                editable: false,
                cellsrenderer: (row, column, value) =>
                    `<p class="ps-50 text-secondary my-auto">${value || '-'}</p>`,
            },
            {
                width: 300,
                dataField: 'country',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('country'),
                editable: false,

                cellsrenderer: function (row, column, value, rowData) {
                    //console.log(value)

                    const valuesInCell = {
                        Україна: { text: getLocalizedText('countryUA') },
                        'Сполучені Штати Америки': { text: getLocalizedText('countryUSA') },
                        Англія: { text: getLocalizedText('countryENG') },
                        Польща: { text: getLocalizedText('countryPLN') },
                        Німеччина: { text: getLocalizedText('countryGER') },
                    };

                    const translateValue = valuesInCell[value] || { text: '-' };

                    return `<p class="text-secondary ps-50 my-auto" >${translateValue.text || '-'}</p>`;
                },
            },
            {
                dataField: 'brand',
                align: 'left',
                text: getLocalizedText('brand'),
                width: 150,
                editable: false,
                cellsrenderer: (row, column, value) =>
                    `<p class="ps-50 text-secondary my-auto">${value || '-'}</p>`,
            },
            {
                width: '70px',
                dataField: 'action',
                align: 'center',
                cellsalign: 'center',
                text: getLocalizedText('action'),
                renderer: function () {
                    return '<div></div>';
                },
                id: 'action',
                cellClassName: 'action-table-drop ',
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    var buttonId = 'button-' + rowdata.uid;
                    var popoverId = 'popover-' + rowdata.uid;

                    let button =
                        '<button id="' +
                        buttonId +
                        '" style="padding:0" class="btn btn-table-cell" type="button" data-bs-toggle="popover"> <img src="' +
                        window.location.origin +
                        '/assets/libs/jqwidget/jqwidgets/styles/images/castom-light-wms/menu_dots_vertical.svg" alt="menu_dots_vertical"> </button>';

                    var popoverOptions = {
                        html: true,
                        sanitize: false,
                        placement: 'left',
                        trigger: 'focus',
                        container: 'body',
                        content: function () {
                            return `<div id=${popoverId}>
                              <ul class="popover-castom" style="list-style: none">
                              <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/sku/${rowdata.id}">${rowdata.is_kit ? 'Переглянути комплект' : getLocalizedText('btnView')}</a></li>
                              <li><a class="dropdown-item" href="${window.location.origin + languageBlock}/sku${rowdata.is_kit ? '/kits' : ''}/${rowdata.id}/edit">${rowdata.is_kit ? 'Редагувати комплект' : getLocalizedText('btnEdit')}</a></li>
<!--                              <li><a class="dropdown-item delete-row" href="#">Видалити товар</a></li>-->
                             </ul>
                          </div>`;
                        },
                    };

                    $(document)
                        .off('click', '#' + buttonId)
                        .on('click', '#' + buttonId, function () {
                            $(this).popover(popoverOptions).popover('show');
                        });

                    $(document)
                        .off('click', '#' + popoverId + ' .delete-row')
                        .on('click', '#' + popoverId + ' .delete-row', function () {
                            var rowId = rowdata.uid;
                            grid.jqxGrid('deleterow', rowId);
                            $('#' + buttonId).popover('hide');
                        });

                    return '<div class="jqx-popover-wrapper">' + button + '</div>';
                },
            },
        ],
    });

    let listSource = [
        { label: getLocalizedText('id'), value: 'local_id', checked: true },
        { label: getLocalizedText('name'), value: 'name_and_code', checked: true },
        // { label: getLocalizedText('status'), value: 'status', checked: true },
        { label: getLocalizedText('category'), value: 'category', checked: true },
        { label: getLocalizedText('manufacturer'), value: 'manufacturer', checked: true },
        { label: getLocalizedText('country'), value: 'country', checked: true },
        { label: getLocalizedText('brand'), value: 'brand', checked: true },
        { label: getLocalizedText('action'), value: 'action', checked: true },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});
