<?php

namespace App\Filament\Resources;

use App\Filament\Options\FileTypeOptions;
use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Models\File;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\FileUpload::make('icon_url')->previewable(false)->downloadable(),
                Forms\Components\FileUpload::make('path')->required()->previewable(false)->downloadable(),
                Forms\Components\Select::make('type')
                    ->options(FileTypeOptions::class),
                Forms\Components\TextInput::make('position')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\Select::make('folder_id')
                    ->relationship(name: 'folder', titleAttribute: 'name'),
                Forms\Components\DatePicker::make('created_at'),
                Forms\Components\DatePicker::make('updated_at'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('path'),
                Tables\Columns\TextColumn::make('type')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('icon_url'),
                Tables\Columns\TextColumn::make('folder.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('position')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'type'];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFiles::route('/'),
            'create' => Pages\CreateFile::route('/create'),
            'edit' => Pages\EditFile::route('/{record}/edit'),
        ];
    }
}
