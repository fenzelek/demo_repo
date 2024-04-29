<?php

namespace App\Services\Parsers\Sources\CCNX\PdfDataFormat;

use App\Contracts\ParserService;
use Illuminate\Http\UploadedFile;

class PdfRosterParser implements ParserService
{

    public function __construct()
    {
    }

    public function parse(UploadedFile $content)
    {
        // TODO: Implement parse() method.
    }
}
