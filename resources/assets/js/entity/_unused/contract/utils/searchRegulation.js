import { getResponse } from './httpRequest.js';

export function searchRegulation(
    baseUrl,
    typeRegulation,
    sideRegulation,
    regulationsArray,
    checkSelectValues
) {
    $(document).on('input', `#search-retail-regulation`, async function () {
        let searchValue = $(this).val();
        //console.log(searchValue)

        let uriSearch;

        let typeBlockName = typeRegulation;
        let typeServiceSide = sideRegulation;
        //console.log(typeBlockName, typeServiceSide)

        let typeSearch;
        let serviceSideSearch;

        if (typeServiceSide === '0') {
            serviceSideSearch = 0;
            if (typeBlockName === '0') {
                typeSearch = 0;
            } else if (typeBlockName === '1') {
                typeSearch = 1;
            } else if (typeBlockName === '2') {
                typeSearch = 2;
            }
        } else if (typeServiceSide === '1') {
            serviceSideSearch = 1;
            if (typeBlockName === '0') {
                typeSearch = 0;
            } else if (typeBlockName === '1') {
                typeSearch = 1;
            } else if (typeBlockName === '2') {
                typeSearch = 2;
            }
        }

        if (typeSearch !== undefined && serviceSideSearch !== undefined) {
            uriSearch = `/regulations/search?type=${typeSearch}&service_side=${serviceSideSearch}&without_trashed=1&without_draft=1&name=${searchValue}`;
        }
        try {
            if (uriSearch !== undefined) {
                let searchResponse = await getResponse(baseUrl, uriSearch, regulationsArray);
                //console.log(searchResponse);

                checkSelectValues(searchResponse);
            }
        } catch (error) {
            console.error('Error:', error);
            // Handle the error here
        }
    });
}
