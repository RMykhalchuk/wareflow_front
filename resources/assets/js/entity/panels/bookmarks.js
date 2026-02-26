import { translit } from '../../utils/translit.js';
import { getLocalizedText } from '../../localization/bookmarks/getLocalizedText.js';

$(function hideCreateBookmarkWindow() {
    $('#create-bookmark').hide();
});

$(function bookmarksCloseButton() {
    $('.bookmarks').on('click', function () {
        $('#create-bookmark').hide();
        $('#body-wrapper').show();
    });
});

$(function showCreateBookmarkWindow() {
    $('#create-btn').on('click', function () {
        $('#body-wrapper').hide();
        $('#create-bookmark').show();
    });
});

$(function showBookmarksListWindow() {
    $('#cancel-btn').on('click', function () {
        $('#create-bookmark').hide();
        $('#body-wrapper').show();
    });
});

$(function disabledAddButoon() {
    $('#add-bookmark').attr('disabled', true);
    $('#bookmarkInput').on('input', function () {
        if ($(this).val().trim() !== '') {
            $('#add-bookmark').attr('disabled', false);
        } else {
            $('#add-bookmark').attr('disabled', true);
        }
    });
});

$(function findListItem() {
    $('#searchBar').on('input', function () {
        const searchValue = $(this).val().toLowerCase();
        $('#list li').each(function () {
            const listItemText = $(this).find('a').text().toLowerCase();
            if (listItemText.includes(searchValue)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

$(function () {
    let csrf = document.querySelector('meta[name="csrf-token"]').content;

    $('#add-bookmark').on('click', addBookmark);
    $('#list').on('click', 'li button', deleteListItem);

    function addBookmark() {
        let bookmarkName = $('#bookmarkInput').val();
        // console.log(bookmarkName);
        let bookmarkKey = generate_url(bookmarkName);
        let bookmarkKeyArray = getBookmarkArray();
        if (bookmarkKeyArray && bookmarkKeyArray.includes(bookmarkKey)) {
            alert(getLocalizedText('differentName'));
        } else {
            let table = $('.table-block');
            let html_id = null;
            let properties = null;
            if (table.length) {
                html_id = table[0].id;
                properties = JSON.stringify($('#' + html_id).jqxGrid('getstate'));
            }

            $.post(window.location.origin + '/bookmarks', {
                _token: csrf,
                name: bookmarkName,
                key: bookmarkKey,
                html_id: html_id,
                properties: properties,
                page_uri: window.location.pathname,
            })
                .done(function (msg) {
                    const newListItem = $('<li>')
                        .html('<a href="#">' + bookmarkName + '</a>')
                        .css('line-height', '32px')
                        .addClass('list-item w-100');
                    const deleteBtn = $('<button>')
                        .html(
                            '<img src=' +
                                window.location.origin +
                                '/assets/icons/entity/bookmarks/delete-button-item.svg>'
                        )
                        .addClass('delete-btn');
                    newListItem.append(deleteBtn);
                    $('#list').append(newListItem);
                    $('#noItemsMsg').hide();
                    $('#bookmarkInput').val('');
                    $('#add-bookmark').attr('disabled', true);
                    $('#body-wrapper').show();
                    $('#create-bookmark').hide();
                    $('.list-item a').css('width', '100%');
                })
                .fail(function (xhr, status, error) {
                    alert(getLocalizedText('changingName'));
                });
        }
    }

    function deleteListItem() {
        // console.log($(this).parent()[0].firstChild);
        let bookmarkKey = generate_url($(this).parent()[0].firstElementChild.innerHTML);
        $.post(window.location.origin + '/bookmarks/delete', {
            _token: csrf,
            key: bookmarkKey,
        });
        $(this).parent().remove();
        if ($('#list li').length === 0) {
            $('#noItemsMsg').show();
        }
    }

    function getBookmarkArray() {
        let list = document.getElementById('list').getElementsByTagName('li');
        let bookmarkArray = [];
        for (let key = 0; key < list.length; key++) {
            bookmarkArray.push(generate_url(list[key].firstElementChild.innerHTML));
        }
        return bookmarkArray;
    }

    function generate_url(str) {
        // console.log(str);
        var url = str.replace(/[\s]+/gi, '-');
        url = translit(url);
        url = url.replace(/[^0-9a-z_\-]+/gi, '').toLowerCase();
        return url;
    }
});

document.addEventListener('DOMContentLoaded', function () {
    var offcanvasElement = document.getElementById('offcanvasEnd');
    var offcanvasToggleLink = document.getElementById('offCanvasToggleLink');

    offcanvasToggleLink.addEventListener('click', function () {
        this.classList.toggle('nav-img-bookmarks-active');
    });

    offcanvasElement.addEventListener('hidden.bs.offcanvas', function () {
        offcanvasToggleLink.classList.remove('nav-img-bookmarks-active');
    });
});
