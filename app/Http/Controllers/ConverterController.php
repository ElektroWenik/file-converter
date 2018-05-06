<?php
namespace App\Http\Controllers;

use App\Http\Requests\ConvertFileRequest;
use App\Converters\JsonConverter;
use App\Converters\CsvConverter;
use App\Converters\XmlConverter;
use App\Converters\YmlConverter;
use App\Converters\FileConverter;

class ConverterController extends Controller
{
    public function convertFile(ConvertFileRequest $request){

        // Set relations between extensions & converters
        $extensionRelations = [
            'json' => JsonConverter::class,
            'xml' => XmlConverter::class,
            'yml' => YmlConverter::class,
            'csv' => CsvConverter::class,
        ];

        $sourceExt = $request->get('sourceFileExtension');
        $outputExt = $request->get('outputFileExtension');

        // Select converter classes by extensions using relations
        $sourceClass = $extensionRelations[$sourceExt];
        $outputClass = $extensionRelations[$outputExt];

        $converter = new FileConverter($sourceClass::createFromFile($request->file('file')), new $outputClass());

        // If file was successfully converted return stored file url in response
        if($converter->convert()) {
            $convertedFileUrl = $converter->getFile(sprintf('converted-file-%d.%s', time(), $outputExt));
            return response()->json([
                'fileUrl' => $convertedFileUrl
            ]);
        }

        return response([
            'message' => 'Converting failed'
        ], 500);

    }
}
