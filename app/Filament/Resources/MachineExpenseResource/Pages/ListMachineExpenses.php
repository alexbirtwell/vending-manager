<?php

namespace App\Filament\Resources\MachineExpenseResource\Pages;

use App\Filament\Resources\MachineExpenseResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListMachineExpenses extends ListRecords
{
    protected static string $resource = MachineExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
             ExportAction::make()
                ->exports([
                    ExcelExport::make('table')->fromTable(),
                ]),
        ];
    }
}
