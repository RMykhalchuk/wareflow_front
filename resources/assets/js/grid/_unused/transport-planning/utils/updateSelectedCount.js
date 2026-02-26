import { getLocalizedText } from '../../../../localization/_unused/transport-planning/getLocalizedText.js';

export function updateSelectedCount(tableOneSel, tableTwoSel, selectorOne, selectorTwo) {
    var selectedCount = tableOneSel.jqxGrid('getselectedrowindexes').length;
    var selectedCount2 = tableTwoSel.jqxGrid('getselectedrowindexes').length;

    selectorTwo.text('Додати ' + selectedCount2);

    //console.log(selectedCount)
    if (selectedCount > 0) {
        if (selectedCount2 > 0) {
            tableTwoSel.jqxGrid('clearselection');
        }
        selectorOne.removeAttr('disabled');
        selectorOne.text(
            getLocalizedText('transport_planning_create_tabs_1_add_btn') + ' ' + selectedCount
        );
        selectorTwo.text(getLocalizedText('transport_planning_create_tabs_2_add_btn'));
    } else if (selectedCount <= 0) {
        selectorOne.attr('disabled', '');
        selectorOne.text(getLocalizedText('transport_planning_create_tabs_1_add_btn'));
    } else if (selectedCount2 > 0) {
        if (selectedCount > 0) {
            tableOneSel.jqxGrid('clearselection');
        }
        selectorTwo.removeAttr('disabled');
        selectorOne.text(getLocalizedText('transport_planning_create_tabs_1_add_btn'));
        selectorTwo.text(
            getLocalizedText('transport_planning_create_tabs_2_add_btn') + ' ' + selectedCount2
        );
    } else if (selectedCount2 <= 0) {
        selectorTwo.attr('disabled', '');
        selectorTwo.text(getLocalizedText('transport_planning_create_tabs_2_add_btn'));
    }
}
