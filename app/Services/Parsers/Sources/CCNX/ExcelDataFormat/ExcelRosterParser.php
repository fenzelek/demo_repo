<?php

namespace App\Services\Parsers\Sources\CCNX\ExcelDataFormat;

use App\Contracts\ParserService;
use Illuminate\Http\UploadedFile;

class ExcelRosterParser implements ParserService
{

    public function __construct()
    {
    }

    public function parse(UploadedFile $content)
    {
        // TODO: Implement parse() method.
    }
}
