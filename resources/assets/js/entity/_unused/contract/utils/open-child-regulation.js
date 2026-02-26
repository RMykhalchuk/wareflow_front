import { regulationItemChild } from './regulation-render.js';

export function toggleChildrenRegulations(regulationsArray) {
    $(document).off('click', '.content-for-market .link-open-children-regulations');

    $(document).on('click', '.content-for-market .link-open-children-regulations', function (e) {
        var $contentForMarket = $(this).closest('.content-for-market');
        var $childrenRegulationsList = $contentForMarket.find('.children-regulations-list');
        var $img = $(this).find('img');
        $childrenRegulationsList.toggleClass('d-none');
        $img.toggleClass('rotate-down');

        var parentId = $contentForMarket.data('id');

        var parentRegulation = regulationsArray.find(function (regulation) {
            return regulation.id === parentId;
        });

        if (parentRegulation && parentRegulation.children) {
            $childrenRegulationsList.empty();

            parentRegulation.children.forEach(function ({ name, id, contracts_count }) {
                var childElement = regulationItemChild(name, id, contracts_count, parentId);
                $childrenRegulationsList.append(childElement);
            });
        }
    });
}
