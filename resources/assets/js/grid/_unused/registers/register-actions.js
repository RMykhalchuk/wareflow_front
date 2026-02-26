const registerClick = function (id) {
    sendRequest(
        window.location.origin + '/registers/status/register/',
        $('#guardRegisterDataTable'),
        id
    );
};

const storekeeperEntranceClick = function (id) {
    sendRequest(
        window.location.origin + '/registers/status/apply/',
        $('#storekeeperRegisterTable'),
        id
    );
};

const guardianEntranceClick = function (id) {
    sendRequest(
        window.location.origin + '/registers/status/launch/',
        $('#guardRegisterDataTable'),
        id
    );
};

const cancelClick = function (id) {
    sendRequest(
        window.location.origin + '/registers/cancel/entrance/',
        $('#storekeeperRegisterTable'),
        id
    );
};

const releaseClick = function (id) {
    sendRequest(
        window.location.origin + '/registers/status/released/',
        $('#guardRegisterDataTable'),
        id
    );
};

function findRowById(table, rowID) {
    var rows = table.jqxGrid('getrows');
    var id = null;

    for (var i = 0; i < rows.length; i++) {
        if (rows[i].id === rowID) {
            id = i;
            break;
        }
    }

    return id;
}

function sendRequest(url, table, id) {
    $('.loader')[0].style.display = 'block';
    let rowId = findRowById(table, id);
    table.jqxGrid('endrowedit', rowId, false);
    let rowData = table.jqxGrid('getrowdatabyid', rowId);

    let formData = new FormData();

    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    for (var key in rowData) {
        formData.append(key, rowData[key]);
    }

    fetch(url + rowData.id, {
        method: 'POST',
        body: formData,
    });
    table.jqxGrid('clearselection');
}

var blockEditMode = function (id) {
    $('#storekeeperRegisterTable').jqxGrid('beginrowedit', id);
};

async function goToClick(id) {
    let table = $('#guardRegisterDataTable');
    let pagerInfo = table.jqxGrid('getpaginginformation');
    let sortInfo = table.jqxGrid('getsortinformation');
    let currentPage = pagerInfo.pagenum;
    let height = table.jqxGrid('rowsheight') / 2;
    let formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    formData.append('id', id);
    formData.append('pager_rows', pagerInfo.pagesize);

    if (sortInfo.sortcolumn == 'id' && !sortInfo.sortdirection.descending) {
        formData.append('sort', 'asc');
    } else {
        formData.append('sort', 'desc');
    }

    await fetch(window.location.origin + '/registers/page-by-record', {
        method: 'POST',
        body: formData,
    }).then(async (response) => {
        let res = await response.json();
        let page = res.page - 1;

        if (currentPage == page) {
            scrollToRow(table, id, height);
        } else {
            table.one('bindingcomplete', function () {
                scrollToRow(table, id, height);
            });
            table.jqxGrid('goToPage', page);
        }
    });
    $('.toast').hide();
}

function scrollToRow(table, id, height) {
    var rows = table.jqxGrid('getrows');
    let rowID = null;
    for (var i = 0; i < rows.length; i++) {
        if (rows[i].id == id) {
            rowID = i;
            break;
        }
    }
    window.scroll({
        top: 150 + height * rowID,
        behavior: 'smooth',
    });

    $('#row' + rowID + 'guardRegisterDataTable > div').addClass('blink');

    // Додайте таймер на видалення класу .blink після 5 секунд
    setTimeout(function () {
        $('#row' + rowID + 'guardRegisterDataTable > div').removeClass('blink');
    }, 20000);
}
