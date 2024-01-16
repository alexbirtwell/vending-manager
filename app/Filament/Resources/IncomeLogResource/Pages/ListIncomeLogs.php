<?php

namespace App\Filament\Resources\IncomeLogResource\Pages;

use App\Actions\SpreadsheetFileToArrayAction;
use App\Filament\Resources\IncomeLogResource;
use App\Models\IncomeLog;
use App\Models\Machine;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ListIncomeLogs extends ListRecords
{
    protected static string $resource = IncomeLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
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
                    ->helperText("Must be a .csv,xls or xlsx file. Please include the header row as this is used to match the columns. Columns should include machine_number, amount, type (CASH/CARD), date, collected_by (full name as per system)'"),
                Section::make('Override Import')
                ->schema([
                    Select::make('type')
                        ->label('Type')
                        ->options(['CASH' => 'CASH', 'CARD' => 'CARD'])
                        ->helperText('The type of payment received.'),
                    Select::make('machine_id')
                        ->label('Machine')
                        ->options( Machine::all()->pluck('machine_number', 'id')),

                    Select::make('user')
                        ->label('Collected By')
                        ->options( User::all()->pluck('name', 'id')),
                    DatePicker::make('date')
                        ->label('Date Collected')
                    ]),
            ]),
        ];
    }

    public function importCSV(array $data, Request $request)
    {

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

            IncomeLog::create([
                'user_id' => $user_id,
                'machine_id' => $machine_id,
                'date' => $date,
                'type' => $type,
                'amount' => (float) $amount,
            ]);
            $rows++;
        }
        $this->notify('success', "Imported $rows rows.");
    }
}
