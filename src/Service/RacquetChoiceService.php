<?php

namespace App\Service;

class RacquetChoiceService{

    public function arraySeter(array $allDatas, string $prefix = ''){
        $datas = array_combine(
            array_map(function ($d) use ($prefix) {
                return $d . $prefix;
            }, $allDatas),
            $allDatas
        );

        return $datas;
    }
}