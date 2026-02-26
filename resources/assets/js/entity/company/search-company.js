import { getLocalizedText } from '../../localization/company/getLocalizedText.js';

$(document).ready(function () {
    let url = window.location.origin;

    //Перемикання між сторінками
    const $blocks = $(
        '#personal-info-user, #find-company, #create-company, #create-workspace, #send-join'
    );

    $('#back-to-find-company').click(function () {
        $('#listFindCompany').addClass('d-none');
        $('#onbording-link-proposition').addClass('d-none');
        $('#notFoundCompany').addClass('d-none');
        $('#back-to-find-company').addClass('d-none');

        $('#findCompany').removeClass('d-none');
        $('#searchCompanyResult').removeClass('d-none');
    });

    $(document).on('click', '#company-send-join', function () {
        $blocks.addClass('d-none');
        $('#send-join').removeClass('d-none');
        $('#send-request').attr('company_id', $(this).attr('company_id'));

        $('#request-company-card').attr('company-id', $(this).attr('company-id'));
        $('#request-company-card')[0].innerHTML = $(this)[0].innerHTML;
    });

    $('#back-find-company-2').click(function () {
        $('#send-join').addClass('d-none');
        $('#find-company').removeClass('d-none');
    });

    // Створення елементу компанії

    //Пошук компанії
    $('#searchCompanyButton').click(function () {
        const titleCountryFull = $('.inpSelectCountry .iti__selected-flag').attr('title');
        let countryTitle = titleCountryFull.match(/^([\w\s]+?)(?=\s*[(:])/)[0].trim();
        // console.log("country: ", countryTitle); назва вибраної країни
        if (countryTitle === 'Ukraine') {
            countryTitle = 'Україна';
        }

        const searchTerm = $('#searchCompanyInpCountry').val(); // get the search term and convert to lowercase
        let endpoint = `/companies/find?query=${searchTerm}&country=${countryTitle}`;
        let requestUrl = url + endpoint;

        fetch(requestUrl)
            .then((response) => response.json())
            .then(({ data }) => {
                const list = document.getElementById('listItemCompany');
                list.innerHTML = '';
                let generatedCount = 0;
                data.forEach((item) => {
                    const edrpou = item.edrpou ? '' + item.edrpou : '';
                    const ipn = item.ipn ? '' + item.ipn : '';
                    const country = countryTitle;

                    const searchFields =
                        item.company_type_id === 1
                            ? [item.first_name, edrpou, ipn, country]
                            : [item.name, edrpou, ipn, country];

                    if (searchTerm.trim()) {
                        const $companyItem = createCompanyItem(
                            item.company_type_id === 1
                                ? `${item.surname} ${item.first_name}`
                                : item.name,
                            item.edrpou,
                            item.ipn,
                            item.company_type_id,
                            item.country
                                ? '' + item.country
                                : getLocalizedText('searchCompanyButtonCreateCompanyItem'),
                            item.created_at,
                            item.img_type,
                            item.id,
                            item.creator_id
                        );
                        list.appendChild($companyItem[0]);
                        $('#searchCompanyResult').addClass('d-none');
                        $('#listFindCompany').removeClass('d-none');
                        $('#onbording-link-proposition').removeClass('d-none');
                        $('#back-to-find-company').removeClass('d-none');

                        generatedCount++;
                        const resultText =
                            generatedCount === 1
                                ? getLocalizedText('searchCompanyButtonResultText_1')
                                : generatedCount >= 2 && generatedCount <= 4
                                  ? getLocalizedText('searchCompanyButtonResultText_2')
                                  : getLocalizedText('searchCompanyButtonResultText_3');
                        $('#countCompanyItem').text(
                            `${getLocalizedText('searchCompanyButtonCountCompanyItem')} ${generatedCount} ${resultText}`
                        );
                    }
                });
            })
            .catch((error) => {
                console.error(error);
                $('#searchCompanyResult').removeClass('d-none');
                $('#notFoundCompany').removeClass('d-none');
                $('#create-new-company').removeClass('d-none');
                $('#findCompany').addClass('d-none');
                $('#listFindCompany').addClass('d-none');
                $('#onbording-link-proposition').addClass('d-none');
                $('#back-to-find-company').removeClass('d-none');
            });
    });

    function createCompanyItem(
        name,
        edrpou,
        ipn,
        company_type_id,
        country,
        created_at,
        img_type,
        id,
        workspace
    ) {
        // Створення елементів з вказаними параметрами
        const itemTemplate = `
        <button id="company-send-join" company-id="${id}" class="col-12 px-0 border onboarding-item-company mt-1 link-dark" style="border-radius: 6px; background-color: #fff;">
            <div class="row mx-0">
                <div class="col-auto p-0">
                    ${
                        img_type
                            ? `<div style="background-color: #A8AAAE14; width: 138px;"><img style="border-radius: 6px 0 0 6px;" width="138px" height="138px" src="${window.location.origin}/uploads/company/image/${id}.${img_type}"></div>`
                            : `<div class="p-2" style="background-color: #A8AAAE14; width: 138px;"><img src="${window.location.origin}/assets/icons/entity/company/building-community-company.svg"></div>`
                    }
                </div>
                <div class="col-9 py-1 flex-grow-1">
                    <div class="d-flex align-items-center" style="gap: 12px;">
                        <h4 class="fw-bolder mb-0 text-capitalize">${name}</h4>
                        ${
                            workspace === null
                                ? `<span class="badge badge-light-primary">${getLocalizedText('createCompanyItemItemTemplateBadgeTrue')}</span>`
                                : `<span class="badge badge-light-warning">${getLocalizedText('createCompanyItemItemTemplateBadgeFalse')}</span>`
                        }
                    </div>
                    ${
                        company_type_id === 2
                            ? `<div class="d-flex align-items-center mt-1" style="gap: 5px; font-size: 15px!important;">
                             <p class="mb-0 fw-normal">${getLocalizedText('createCompanyItemItemTemplateEdrpou')} </p>
                             <p class="fw-bold mb-0">${edrpou}</p>
                           </div>`
                            : ''
                    }
                    ${
                        company_type_id === 1
                            ? `<div class="d-flex align-items-center mt-1" style="gap: 5px; font-size: 15px!important;">
                             <p class="mb-0 fw-normal">${getLocalizedText('createCompanyItemItemTemplateIpn')} </p>
                             <p class="fw-bold mb-0">${ipn}</p>
                           </div>`
                            : ''
                    }
                    <div class="d-flex align-items-center" style="gap: 5px; margin-top: 6px; font-size: 15px!important;">
                        <p class="mb-0 fw-normal">${getLocalizedText('createCompanyItemItemTemplateCountry')}</p>
                        <p class="fw-bold mb-0">${country}</p>
                    </div>
                    <div class="d-flex align-items-center" style="gap: 5px; margin-top: 6px; font-size: 15px!important;">
                        <p class="mb-0 fw-normal">${getLocalizedText('createCompanyItemItemTemplateCreated_at')}</p>
                        <p class="fw-bold mb-0">${created_at}</p>
                        <p class="mb-0 fw-normal">${getLocalizedText('createCompanyItemItemTemplateCompany')}</p>
                        <p class="fw-bold mb-0">${getLocalizedText('createCompanyItemItemTemplateCompanyNameExample')}</p>
                    </div>
                </div>
            </div>
        </button>
    `;

        // Повернення створеного елементу
        return $(itemTemplate);
    }

    //  додати до списку компаній
    $('#add-to-company-list').click(function (e) {
        e.preventDefault();
        const companyId = $('#request-company-card').attr('company-id');

        console.log('додати об‘єкт по цьому айді на бек в список компаній таблиці :', companyId);
    });
});

// інпут пошуку компанії вказуючи країну
const input = document.querySelector('#searchCompanyInpCountry');

const iti = window.intlTelInput(input, {
    initialCountry: 'auto',
    geoIpLookup: function (callback) {
        $.get('https://ipinfo.io', function () {}, 'jsonp').always(function (resp) {
            const countryCode = resp && resp.country ? resp.country : 'ua';
            callback(countryCode);
        });
    },
    utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/18.1.6/js/utils.js',
    //   separateDialCode: true,
    onlyCountries: ['ua', 'pl', 'gb', 'us', 'de'],
});
