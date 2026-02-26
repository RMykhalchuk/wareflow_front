import { getLocalizedText } from '../../../../localization/_unused/contract/getLocalizedText.js';

export function regulationItem(name, id, amount, children) {
    return `<li data-id=${id}  class="list-s-none content-for-market">
<div  data-id=${id}
    class="cont-for-market-item  d-flex justify-content-between align-items-center ps-2  pe-3 py-1">
    <div class="">
        <div class="d-flex align-items-center gap-1 mb-1">
            <h5 class="m-0" style="color: #6F6B7D">${name}</h5>
            <div class="avatar bg-light-success rounded float-start"
                style="padding:0px 6px">
                <div class="avatar-content align-items-center">
                    <img class="me-25" src="${window.location.origin}/assets/icons/entity/contract/notes.svg" alt="notes">
                    <span class="f-14 fw-bold" style="margin-top:2px">${amount}</span>
                </div>
            </div>
        </div>
      <p class="f-15 lh-lg mb-0 link-open-children-regulations"
            style="color: #A5A3AE;"> ${
                children.length > 0
                    ? ` ${getLocalizedText('regulationRenderItemTextChild')}
                (${children.length}) <img class="link-open-children-regulations-img"
                src="${window.location.origin}/assets/icons/entity/contract/little-arrow-down.svg">`
                    : getLocalizedText('regulationRenderItemTextNoChild')
            } </p>
    </div>
    <img src="${window.location.origin}/assets/icons/entity/contract/fill-arrow-right.svg">
</div>
<!-- list with children -->
<ul class="ps-0 list-group children-regulations-list d-none">
</ul>
<hr class="my-0">
</li>`;
}

export function regulationItemChild(name, id, amount, parentId) {
    return `<li data-id=${id} data-parentId=${parentId} class="child-regulations-item list-group-item border-0 justify-content-between ps-3 pe-3"
style="line-height: 2.5em;position: static">
<div class="align-self-center">
    <p class="m-0  d-flex gap-1 align-items-center">
        <a href="#" class="list-group-item-action w-auto fw-bold">
            ${name}
        </a>
        <span
            class="px-75 py-50 gap-25 badge badge-light-success d-inline-flex align-items-center"><img
                src="${window.location.origin}/assets/icons/entity/contract/notes.svg"
                alt="notes">${amount}</span>
<!--        <span class="alert alert-secondary d-inline-block p-0"-->
<!--            style="padding : 2px 10px !important; color: #4B4B4B !important;">Архів</span>-->
<!--        <span class="alert alert-danger d-inline-block p-0"-->
<!--            style="padding : 2px 10px !important;">Чернетка</span>-->
    </p>
</div>

<div class="d-flex gap-1 align-items-center">
    <div> <img src="${window.location.origin}/assets/icons/entity/contract/fill-arrow-right.svg"></div>

</div>
</li>`;
}

// <svg width="1" height="30" viewBox="0 0 1 50" fill="none"
//      xmlns="http://www.w3.org/2000/svg">
//     <rect width="1" height="50" fill="#A8AAAE" />
// </svg>
//
// <div className="d-inline-flex dropdown-menu-group">
//     <a className="dropdown-toggle hide-arrow" data-bs-toggle="dropdown"
//        style="color: #A8AAAE ">
//         <i style="transform: scale(1.3)"
//            data-feather='more-vertical'></i>
//     </a>
//     <div className="dropdown-menu dropdown-menu-end"
//          id="dropdown-menu-type">
//         <button className="dropdown-item w-100 duplicate-child-regulation" data-id=${id} data-parentId=${parentId}>
//             Дублювати
//         </button>
//         <button data-bs-toggle="modal" id="archive_button"
//                 data-bs-target="#archive_regulation" type="submit"
//                 className="dropdown-item w-100">
//             Архівувати
//         </button>
//         <button data-bs-toggle="modal" id="delete_button" data-id=${id} data-parentId=${parentId}
//                 data-bs-target="#delete_regulation" type="submit"
//                 className="text-danger dropdown-item w-100 delete-child-regulation">
//             Видалити
//         </button>
//
//     </div>
// </div>

export function inputNameNewRegulation(name, parentId) {
    return `<li  data-parentId="${parentId}" class="new-name-input-group border-0 d-flex justify-content-between ps-3 pe-2" >
    <div class="align-self-center" style="padding: 10px 0;">
    <input type="text" id="newRegulationNameInput" class="form-control" style="width: 345px"
                    value="${name}">
    </div>

    <div class="d-flex gap-1 align-items-center">
        <div> <img src="${window.location.origin}/assets/icons/entity/contract/lighter-fill-arrow-right.svg"></div>
        <svg width="1" height="30" viewBox="0 0 1 50" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <rect width="1" height="50" fill="#DCDDDF" />
        </svg>

        <div class="d-inline-flex">
            <a class=""
                style="color: #DCDDDF ">
                <i style="transform: scale(1.3)"
                    data-feather='more-vertical'></i>
            </a>

        </div>
    </div>
    </li>`;
}

export function hideAmendedChangesModal() {
    // закриття модалки "внесено зміни до регламенту"
    $('#btn-create-new-regulation-in-modal').click(function () {
        $('#amendedChangesModal').modal('hide');
    });
}
