<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParseFileRequest;
use App\Services\Parsers\ParserAbstractFactory;
use Illuminate\Http\Request;

class RosterFileController extends Controller
{
    public function parse(ParseFileRequest $request, ParserAbstractFactory $factory)
    {
        $file = $request->file('file');
        $sourceType = $request->input('source_type');

        $parserFactory = $factory->getFactory($sourceType);
        $parser = $parserFactory->makeParser($file);
        try {
            $parser->parse($file);
        } catch(\Exception $ex){
            return response()->json(['error' => 'File Parsing Error'], 500);
        }
        return response()->json(['message' => 'File parsed successfully']);
    }
}
