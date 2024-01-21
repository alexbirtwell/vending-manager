<?php

namespace App\Filament\Resources\MachineResource\Pages;

use App\Actions\SpreadsheetFileToArrayAction;
use App\Filament\Resources\MachineResource;
use App\Models\Machine;
use Filament\Forms\Components\FileUpload;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListMachines extends ListRecords
{
    protected ?string $maxContentWidth = 'full';

    protected static string $resource = MachineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
             ExportAction::make()
                ->exports([
                    ExcelExport::make('table')->fromTable(),
                ]),
            Actions\Action::make('import')
                ->action('importCSV')
                ->icon('heroicon-m-arrow-up-tray')
            ->form([
                FileUpload::make('csv')
                    ->visibility('private')
                    ->disk('public')
                    ->required()
                    ->acceptedFileTypes(['text/csv','application/vnd.ms-excel',
          'application/msexcel',
          'application/x-msexcel',
          'application/x-ms-excel',
          'application/x-excel',
          'application/x-dos_ms_excel',
          'application/xls',
          'application/x-xls',
          'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
          'application/vnd.ms-office'])
                    ->helperText("Must be a .csv,xls or xlsx file. Please include the header row as this is used to match the columns. Columns should include machine_number, amount, type (CASH/CARD), date, collected_by (full name as per system)'")
                ])
        ];
    }

    public function importCSV(array $data, Request $request)
    {
        dd('still working on this');
        $file = new UploadedFile(Storage::disk('public')->path($data['csv']), 'import.csv');

        $csvData = SpreadsheetFileToArrayAction::run($file);
        $rows = 0;
        foreach($csvData as $row) {
            if (! isset($row['amount']) || ! is_numeric($row['amount'])) {
                continue;
            }
            $user_id = $data['user'] ?? User::where('name', $row['collected_by'])->get()->first()->id ?? auth()->user()->id;
            $machine_id = $data['machine_id'] ?? Machine::where('machine_number', $row['machine_number'])->get()->first()->id ?? 0;
            $date = $data['date'] ?? null;
            if (!$date) {
                $date = isset($row['date']) ? Carbon::createFromTimeString($row['date']) : now();
            }
            $type = $data['type'] ?? $row['type'] ?? "CASH";
            $amount = $row['amount'] ?? 0;

            Machine::create([
                'user_id' => $user_id,
                'machine_number' => $machine_id,
                'date' => $date,
                'type' => $type,
                'amount' => (float) $amount,
            ]);
            $rows++;
        }
        $this->notify('success', "Imported $rows rows.");
    }
}
