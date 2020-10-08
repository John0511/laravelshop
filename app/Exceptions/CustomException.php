<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class CustomException extends Exception
{
    //
    public function render(Request $Request)
    {
        # code...
        return view('error',['msg'=>$this->getMessage()]);
    }
}
