<?php

namespace App\Services\Parsers;

use App\Contracts\ParserFactory;
use Illuminate\Contracts\Foundation\Application;

class ParserAbstractFactory
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function getFactory(string $sourceType): ParserFactory
    {
        //TODO remove hardcoded strings to ValueObject (enum maybe?)
        return match ($sourceType) {
            'CCNX' => $this->app->make(CCNXParserFactory::class),
            'SomeOther' => $this->app->make(SomeotherParserFactory::class),
            default => throw new \InvalidArgumentException("Unsupported source type: $sourceType"),
        };
    }
}
