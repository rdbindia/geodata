<?php


namespace App\Http\Controllers;


class Factorial
{
    public function index($int = 5)
    {

        $fact = $this->findFactorial($int);
        echo $fact;
    }

    public function findFactorial($int)
    {
        $factorial = 1;
        for ($i = 1; $i <= $int; $i++) {
            echo $factorial. "*" .$i;
            $factorial = $factorial * $i;
        }
        echo "=";
        return $factorial;
    }
}
