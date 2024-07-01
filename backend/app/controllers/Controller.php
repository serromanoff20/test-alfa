<?php namespace App\controllers;

class Controller
{
    protected function isFilled(array $fields): bool
    {
        $isNotEmpty = array();
        foreach ($fields as $field) {
            $isNotEmpty[] = !empty($field);
        }

        if (count($fields) === count($isNotEmpty) && $this->allTrue($isNotEmpty)) {
            return true;
        }
        return false;
    }

    private function allTrue(array $array): bool {
        foreach ($array as $value) {
            if ($value !== true) {
                return false;
            }
        }
        return true;
    }
}