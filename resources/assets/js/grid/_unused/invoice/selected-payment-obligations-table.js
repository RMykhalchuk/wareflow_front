import { pagerRenderer } from '../../components/pager.js';
import { toolbarRender } from '../../components/toolbar-advanced.js';
import { listbox } from '../../components/listbox.js';
import { hover } from '../../components/hover.js';
import { getLocalizedText } from '../../../localization/_unused/invoice/getLocalizedText.js';
import { switchLang } from '../../components/switch-lang.js';

const pagerRendererLeftovers = pagerRenderer.bind({});
const toolbarRendererLeftovers = toolbarRender.bind({});

$(document).ready(function () {
    let table = $('#selected-payment-obligations-table');
    let isRowHovered = false;

    let language = switchLang();
    let languageBlock = language === 'en' ? '' : '/' + language;

    let dataFields = [
        { name: 'obligations', type: 'string' },
        { name: 'correction', type: 'string' },
        { name: 'type', type: 'string' },
        { name: 'performer', type: 'number' },
        { name: 'recipient', type: 'string' },
        { name: 'date', type: 'string' },
        { name: 'costWithoutPDV', type: 'string' },
        { name: 'id', type: 'string' },
    ];

    var myDataSource = {
        datatype: 'array',
        datafields: dataFields,
        localdata: customData,
    };

    let dataAdapter = new $.jqx.dataAdapter(myDataSource);
    var grid = table.jqxGrid({
        theme: 'light-wms sel-payment-oblig-table-custom',
        width: '100%',
        autoheight: true,
        pageable: true,
        source: dataAdapter,
        pagerRenderer: function () {
            return pagerRendererLeftovers(table);
        },
        virtualmode: true,
        rendergridrows: function () {
            return dataAdapter.records;
        },
        ready() {
            checkUrl();
        },
        sortable: true,
        columnsResize: false,
        filterable: true,
        filtermode: 'default',
        localization: getLocalization(language),
        selectionMode: 'checkbox',
        enablehover: false,
        columnsreorder: true,
        autoshowfiltericon: true,
        pagermode: 'advanced',
        rowsheight: 35,
        filterbarmode: 'simple',
        toolbarHeight: 45,
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

            return toolbarRendererLeftovers(statusbar, table, false, 1, columnCount - 1); // Subtract 1 to exclude the action column
        },
        columns: [
            {
                dataField: 'obligations',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsNumber'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    const objectData = JSON.stringify(rowdata);
                    return `<a data-object='${objectData}' class=" ps-1 d-flex my-auto " href='#' >${value}</a>`;
                },
                width: 120,
            },
            {
                dataField: 'correction',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsCorrection'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p style="" class="text-secondary ps-1  my-auto" >${value}</p>`;
                },
                width: 120,
            },
            {
                dataField: 'type',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsType'),
                minwidth: 200,
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class=" text-secondary ps-1  my-auto" style="" >${value}</p>`;
                },
            },
            {
                minwidth: 100,
                dataField: 'performer',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsPerformer'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="text-secondary text-decoration-underline fw-medium-c  ps-1  my-auto" href='${
                        window.location.origin + languageBlock
                    }/companies/${rowdata.id}'  >${value}</a>`;
                },
            },
            {
                minwidth: 100,
                dataField: 'recipient',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsRecipient'),
                editable: false,
                cellsrenderer: function (
                    row,
                    columnfield,
                    value,
                    defaulthtml,
                    columnproperties,
                    rowdata
                ) {
                    return `<a class="text-secondary text-decoration-underline fw-medium-c  ps-1  my-auto" href='${
                        window.location.origin + languageBlock
                    }/companies/${rowdata.id}'  >${value}</a>`;
                },
            },
            {
                width: 140,
                dataField: 'date',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsDate'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="text-secondary  ps-1  my-auto" style="" >${value}</p>`;
                },
            },
            {
                width: 170,
                dataField: 'costWithoutPDV',
                align: 'left',
                cellsalign: 'right',
                text: getLocalizedText('tableSelectedPaymentObligationsCostWithoutPDV'),
                editable: false,
                cellsrenderer: function (row, column, value, rowData) {
                    return `<p class="text-secondary  ps-1  my-auto" style="" >${value} ${getLocalizedText('tableSelectedPaymentObligationsCostWithoutPDVUnit')}</p>`;
                },
            },
        ],
    });

    let listSource = [
        {
            label: getLocalizedText('tableSelectedPaymentObligationsNumber'),
            value: 'obligations',
            checked: true,
        },
        {
            label: getLocalizedText('tableSelectedPaymentObligationsCorrection'),
            value: 'correction',
            checked: true,
        },
        {
            label: getLocalizedText('tableSelectedPaymentObligationsType'),
            value: 'type',
            checked: true,
        },
        {
            label: getLocalizedText('tableSelectedPaymentObligationsPerformer'),
            value: 'performer',
            checked: true,
        },
        {
            label: getLocalizedText('tableSelectedPaymentObligationsRecipient'),
            value: 'recipient',
            checked: true,
        },
        {
            label: getLocalizedText('tableSelectedPaymentObligationsDate'),
            value: 'date',
            checked: true,
        },
        {
            label: getLocalizedText('tableSelectedPaymentObligationsCostWithoutPDV'),
            value: 'costWithoutPDV',
            checked: true,
        },
    ];

    listbox(table, listSource);
    hover(table, isRowHovered);
});

var customData = [
    {
        obligations: '234234',
        correction: '3',
        type: 'Транспортне перевезення',
        performer: 'ТОВ Кондитерська Ярич ',
        recipient: 'Навігор ТД ТОВ',
        date: '01.05.2023',
        costWithoutPDV: '12 000.00',
        id: '234234',
    },
    {
        obligations: '456456',
        correction: '2',
        type: 'Будівельні роботи',
        performer: 'ТОВ БудІнвест',
        recipient: 'БудІнвест Група',
        date: '15.06.2023',
        costWithoutPDV: '35 000.00',
        id: '456456',
    },
    {
        obligations: '789789',
        correction: '1',
        type: 'Інформаційні технології',
        performer: 'ТОВ ТехноСервіс',
        recipient: 'IT Solutions Inc.',
        date: '10.07.2023',
        costWithoutPDV: '8 500.00',
        id: '789789',
    },
    {
        obligations: '987987',
        correction: '4',
        type: 'Консалтингові послуги',
        performer: 'ТОВ Консалт',
        recipient: 'Глобальні Консалтингові Рішення',
        date: '25.08.2023',
        costWithoutPDV: '20 000.00',
        id: '987987',
    },
];
