<?php

namespace App\Services\Parsers;

use App\Contracts\ParserFactory;
use App\Contracts\ParserService;
use App\Services\Parsers\Sources\CCNX\ExcelDataFormat\ExcelRosterParser;
use App\Services\Parsers\Sources\CCNX\HtmlDataFormat\HtmlRosterParser;
use App\Services\Parsers\Sources\CCNX\PdfDataFormat\PdfRosterParser;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;
use Illuminate\Contracts\Foundation\Application;
class CCNXParserFactory implements ParserFactory
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function makeParser(UploadedFile $file): ParserService
    {
        $extension = $file->getClientOriginalExtension();

        return match ($extension) {
            'html' => $this->app->make(HtmlRosterParser::class),
            'xlsx' => $this->app->make(ExcelRosterParser::class),
            'pdf' => $this->app->make(PdfRosterParser::class),
            default => throw new InvalidArgumentException("Unsupported file type for CCNX source"),
        };
    }
}
