<?php


function a()
{
    echo "В начале a\n";

    b();


    echo "В конце a\n";
}

function b()
{
    echo "В начале b\n";
    throw new Exception("Ошибка в функции b()");
    echo "В конце b\n";
}

a();

class LoadUsersException extends Exception
{
    public function lalala(){
        return $this->line;
    }
}
