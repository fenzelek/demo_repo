<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;
interface ParserService
{
    public function parse(UploadedFile  $content);
}
