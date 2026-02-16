<?php

namespace App\Service;

class GlobalServices{

    public function replaceKeys($oldKey, $newKey, array $input){
        $return = array(); 
        foreach ($input as $key => $value) {
            if ($key===$oldKey)
                $key = $newKey;

            if (is_array($value))
                $value = $this->replaceKeys( $oldKey, $newKey, $value);

            $return[$key] = $value;
        }
        return $return; 
    }
}