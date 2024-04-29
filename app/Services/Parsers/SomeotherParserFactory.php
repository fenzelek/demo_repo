<?php

namespace App\Services\Parsers;

use App\Contracts\ParserFactory;
use App\Contracts\ParserService;
use Illuminate\Http\UploadedFile;

class SomeotherParserFactory implements ParserFactory
{

    public function makeParser(UploadedFile $file): ParserService
    {
        // Define logic for other source types here
        throw new \InvalidArgumentException("NOT IMPLEMENTED");
    }
}
