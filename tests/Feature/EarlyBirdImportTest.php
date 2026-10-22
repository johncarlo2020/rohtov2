<?php

namespace Tests\Feature;

use App\Imports\EarlyBirdImport;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EarlyBirdImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null,
        ]);
        DB::purge('sqlite');
        Schema::create('early_birds', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('source_of_channel');
            $table->boolean('claimed')->default(false);
            $table->timestamps();
        });
    }

    public static function formats(): array
    {
        return ['CSV' => ['csv'], 'XLSX' => ['xlsx']];
    }

    #[DataProvider('formats')]
    public function test_upload_import_preserves_headings_and_row_validation(string $format): void
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray([
            ['email', 'name', 'source_of_channel'],
            [' FIRST@example.com ', 'First', 'WEB'],
            ['invalid', 'Invalid', 'web'],
            ['first@example.com', 'Duplicate', 'web'],
        ]);
        $path = tempnam(sys_get_temp_dir(), 'early-bird-');

        try {
            $writer = $format === 'csv' ? new Csv($spreadsheet) : new Xlsx($spreadsheet);
            $writer->save($path);
            $file = new UploadedFile($path, 'early-birds.'.$format, null, null, true);
            $import = new EarlyBirdImport();
            Excel::import($import, $file);

            $this->assertSame(1, $import->imported);
            $this->assertSame(2, $import->skipped);
            $this->assertSame(['Row 3: Invalid email'], $import->errors);
            $this->assertDatabaseCount('early_birds', 1);
            $this->assertDatabaseHas('early_birds', [
                'email' => 'first@example.com',
                'name' => 'First',
                'source_of_channel' => 'web',
                'claimed' => false,
            ]);
        } finally {
            $spreadsheet->disconnectWorksheets();
            unlink($path);
        }
    }
}
