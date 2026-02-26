function checkUrl() {
    if (window.location.search.includes('bookmark')) {
        const urlParams = new URLSearchParams(window.location.search);
        let key = urlParams.get('bookmark');
        $.ajax({
            url: window.location.origin + '/bookmarks/find-by-key/' + key,
            success: function (data, textStatus, xhr) {
                if (data[0].properties) {
                    let table = $('#' + data[0].html_id);
                    let key = 'jqxGrid' + data[0].html_id;
                    let state = JSON.parse(data[0].properties);
                    table.jqxGrid('loadstate', state);
                    listbox(table, createListsource(state.columns), '', state.columns);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert('Something wrong');
            },
        });
    }
}

function createListsource(settingsObj) {
    const settingsArray = [];
    for (const key in settingsObj) {
        if (settingsObj.hasOwnProperty(key)) {
            const setting = settingsObj[key];
            if (setting.text) {
                settingsArray.push({
                    label: setting.text,
                    value: key,
                    checked: !setting.hidden,
                });
            }
        }
    }
    return settingsArray;
}

function listbox(table, listSource, idListBox = '', objColumns) {
    const listBoxContainer = document.getElementById(`jqxlistbox${idListBox}`);
    listBoxContainer.style.width = '400px';
    listBoxContainer.style.height = '350px';
    listBoxContainer.style.overflow = 'auto';

    listSource.forEach((item, index) => {
        const listItem = document.createElement('div');
        listItem.classList.add('custom-list-item');
        listItem.style.height = '42px';
        listItem.style.display = 'flex';
        listItem.style.justifyContent = 'space-between';
        listItem.style.alignItems = 'center';
        listItem.style.padding = '0 20px';

        const checkboxContainer = document.createElement('div');
        checkboxContainer.style.display = 'flex';
        checkboxContainer.style.alignItems = 'center';

        const checkbox = document.createElement('input');
        checkbox.classList.add('checkbox-castom-listbox', 'form-check-input');
        checkbox.type = 'checkbox';
        checkbox.checked = item.checked;

        const label = document.createElement('span');
        label.textContent = listSource[index].label;
        label.style.marginLeft = '10px';
        label.classList.add('fw-bold');

        const pinButton = document.createElement('button');
        pinButton.classList.add('btn', 'pinButton');
        pinButton.style.padding = '0';

        const pinIcon = document.createElement('img');
        pinIcon.classList.add('pinIcon');
        pinIcon.src = item.pinned
            ? '/assets/icons/table/pined.svg'
            : '/assets/icons/table/unpined.svg';
        pinIcon.alt = 'pined';

        pinButton.appendChild(pinIcon);
        checkboxContainer.appendChild(checkbox);
        checkboxContainer.appendChild(label);
        listItem.appendChild(checkboxContainer);
        listItem.appendChild(pinButton);
        listBoxContainer.appendChild(listItem);

        checkbox.addEventListener('click', function (event) {
            listSource[index].checked = this.checked;
            table.jqxGrid('beginUpdate');
            if (this.checked) {
                table.jqxGrid('showColumn', listSource[index].value);
            } else {
                table.jqxGrid('hideColumn', listSource[index].value);
            }
            table.jqxGrid('endUpdate');
        });

        pinButton.addEventListener('click', function (event) {
            listSource[index].pinned = !listSource[index].pinned;
            this.classList.toggle('pinned', listSource[index].pinned);
            pinIcon.src = listSource[index].pinned
                ? '/assets/icons/table/pined.svg'
                : '/assets/icons/table/unpined.svg';
            listItem.classList.toggle('pinned', listSource[index].pinned);

            const column = table.jqxGrid('getcolumn', listSource[index].value);
            column.pinned = listSource[index].pinned ? 'left' : '';

            table.jqxGrid('beginUpdate');
            // if (listSource[index].checked) {
            //     if (listSource[index].pinned) {
            //         table.jqxGrid('showColumn', column.datafield);
            //     } else {
            //         table.jqxGrid('hideColumn', column.datafield);
            //     }
            // }
            table.jqxGrid('endUpdate');
        });

        for (const key in objColumns) {
            if (
                objColumns.hasOwnProperty(key) &&
                objColumns[key].pinned === 'left' &&
                key === listSource[index].value
            ) {
                listSource[index].pinned = true;
                listItem.classList.add('pinned');
                pinIcon.src = '/assets/icons/table/pined.svg';
                break;
            }
        }
    });
}
