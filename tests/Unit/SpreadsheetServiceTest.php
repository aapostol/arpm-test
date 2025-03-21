<?php

namespace Tests\Unit;

use App\Events\ProcessProductImage;
use App\Models\Product;
use App\Service\SpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;


class SpreadsheetServiceTest extends TestCase
{
    use RefreshDatabase;

    public static function provider_process_spreadsheet(): array
    {
        return [
            'no_products:no_data' => [[], []],
            'no_products:validation_exception:quantity_under_threshold' => [
                [
                    ['product_code' => '1', 'quantity' => 0],
                ],
                []
            ],
            'no_products:validation_exception:quantity_not_a_number' => [
                [
                    ['product_code' => '1', 'quantity' => 'this is not a number'],
                ],
                []
            ],
            'one_product' => [
                [
                    ['product_code' => '2', 'quantity' => 1],
                ],
                ['2']
            ],
            'one_product_with_one_unique_validation_exception' => [
                [
                    ['product_code' => '3', 'quantity' => 1],
                    ['product_code' => '3', 'quantity' => 12],
                ],
                ['3']
            ],
            'two_products' => [
                [
                    ['product_code' => '4', 'quantity' => 12],
                    ['product_code' => '5', 'quantity' => 12],
                ],
                ['3']
            ],
        ];
    }

    #[DataProvider("provider_process_spreadsheet")]
    public function test_process_spreadsheet(array $data, array $validProductCodes)
    {
        Event::fake([
            ProcessProductImage::class,
        ]);

        $this->mock(Importer::class, function (MockInterface $mock) use ($data) {
            $mock->shouldReceive('import')
                ->andReturn($data)
                ->once();
        });

        (new SpreadsheetService)->processSpreadsheet('mocked-file-path');

        Event::assertDispatched(ProcessProductImage::class, count($validProductCodes));

        $this->assertDatabaseCount(Product::class, count($validProductCodes));

        foreach ($validProductCodes as $entry) {
            $this->assertDatabaseHas(Product::class, [
                'code' => $entry,
            ]);
        }
    }
}
