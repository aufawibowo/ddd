<?php


namespace A7Pro\Marketplace\Customer\Infrastructure\Persistence;


class SqlBaseRepository
{
    public function whereInBuilder($id)
    {
        if (!isset($id[0])) return "('')";

        $sqlWhereIdProductIn = "(";
        foreach ($id as $key => $value) {
            $sqlWhereIdProductIn .= "'" . $value . "'";

            if (isset($id[$key + 1]))
                $sqlWhereIdProductIn .= ",";
            else
                $sqlWhereIdProductIn .= ")";
        }

        return $sqlWhereIdProductIn;
    }
}