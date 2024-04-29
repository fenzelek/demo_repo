<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;
interface ParserFactory
{
    public function makeParser(UploadedFile $file): ParserService;
}
