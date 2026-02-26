// inputGroups.js
export function toggleInputGroups(emailGroupSelector, numberGroupSelector, linkSelector) {
    $(linkSelector).click(function (e) {
        $(emailGroupSelector).hide();
        $(numberGroupSelector).show();
        $(emailGroupSelector + ' input').attr('aria-selected', 'false');
        $(numberGroupSelector + ' input')
            .attr('aria-selected', 'true')
            .focus();
    });
}

export function hideInputGroup(groupSelector) {
    $(groupSelector).hide();
    $(groupSelector + ' input').attr('aria-selected', 'false');
}

export function showInputGroup(groupSelector) {
    $(groupSelector).show();
    $(groupSelector + ' input').attr('aria-selected', 'true');
}
