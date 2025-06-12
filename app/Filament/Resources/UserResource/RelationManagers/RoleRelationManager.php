<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Tables\Table;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Phpsa\FilamentAuthentication\Resources\UserResource\RelationManager\RoleRelationManager as BaseRoleRelationManager;

class RoleRelationManager extends BaseRoleRelationManager
{
    public function table(Table $table): Table
    {
        return parent::table($table)
            ->headerActions([
                AttachAction::make(),
            ])
            ->actions([
                DetachAction::make(),
            ]);
    }
}
