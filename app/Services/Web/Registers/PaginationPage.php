<?php

namespace App\Services\Web\Registers;

use App\Models\Dictionaries\Register;

final class PaginationPage
{
    public function getPageByRegisterId($id, $pagerNumber, $sort = 'desc'): int
    {
        if ($sort == 'desc') {
            $operation = '>=';
        } else {
            $operation = '<=';
        }
        $register = Register::where('id', $operation, $id)->count();
        $page = $register / $pagerNumber;
        if (!is_int($page)) {
            $page = intval(floor($page)) + 1;
        }
        return $page;
    }
}
