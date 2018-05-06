<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Converters\JsonConverter;
use App\Converters\CsvConverter;
use App\Converters\YmlConverter;
use App\Converters\XmlConverter;
use Illuminate\Support\Facades\Storage;

class ConverterTest extends TestCase
{
    use WithoutMiddleware;

    public function testConverterPage(){
        $this->get('/')
            ->assertStatus(200)
            ->assertSee('Laravel');
    }

    public function testJsonConverterMakesArrayFromJsonAndConversely() {
        $json = Storage::disk('local')->get('test/test.json');

        $converter = new JsonConverter($json);

        $array = [
            'name' => 'Adam',
            'Age' => '18',
            'Address' => [
                'Street' => '1000 Brooklyn Meadow',
                'City' => 'Denver'
            ]
        ];

        $this->assertEquals($array, $converter->toArray());
        $converter = new JsonConverter($converter->fromArray($array));
        $this->assertTrue($converter->isValid());
    }

    public function testCsvConverterMakesArrayFromCsvAndConversely(){
        $csv = Storage::disk('local')->get('test/test.csv');

        $converter = new CsvConverter($csv);

        $array = [
            [
                'Type' => 'MyRequirementType',
                'Primary Text' => 'The vehicle must have two wheels.',
                'Title' => 'Vehicle wheels',
                'Description' => 'This requirement defines the rules for vehicles',
                'Owner' => 'Joe Blogs'
            ]
        ];


        $this->assertEquals($array, array_values($converter->toArray()));
        $converter = new CsvConverter($converter->fromArray($array));
        $this->assertTrue($converter->isValid());
    }

    public function testYmlConverterMakesArrayFromYmlAndConversely(){
        $yml = Storage::disk('local')->get('test/test.yml');

        $converter = new YmlConverter($yml);


        $array = [
            'current' => [
                'major' => 1,
                'minor' => 0,
                'patch' => 0
            ],
            'cache' => [
                'enabled' => true,
                'key' => 'pragmarx-version'
            ]
        ];

        $this->assertEquals($array, $converter->toArray());
        $converter = new YmlConverter($converter->fromArray($array));
        $this->assertTrue($converter->isValid());
    }

    public function testXmlConverterMakesArrayFromXmlAndConversely(){
        $xml = Storage::disk('local')->get('test/test.xml');

        $converter = new XmlConverter($xml);


        $array = [
            'content' => [
                'dataset' => [
                    'record' => [
                        [
                            'id' => 1,
                            'first_name' => 'Dre',
                            'last_name' => 'Messiter'
                        ],
                        [
                            'id' => 2,
                            'first_name' => 'Mathew',
                            'last_name' => 'Bright'
                        ]
                    ]
                ]
            ]
        ];

        $this->assertEquals($array, $converter->toArray());
        $converter = new XmlConverter($converter->fromArray($array));
        $this->assertTrue($converter->isValid());
    }



}
