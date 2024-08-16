<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // This group will span two columns and contains only the Product Information section.
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Information')
                        ->schema([
                            Forms\Components\TextInput::make('category_id')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('brand_id')
                                ->required()
                                ->numeric(),
                            Forms\Components\TextInput::make('name')
                                ->maxLength(255)
                                ->label('Product Name')
                                ->required(),
                            Forms\Components\TextInput::make('slug')
                                ->maxLength(255)
                                ->required()
                                ->live()
                                ->disabled()
                                ->prefix('https://')
                                ->label('Slug URL')
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),
                            Forms\Components\Textarea::make('images')
                                ->columnSpan('full'),
                            Forms\Components\MarkdownEditor::make('description')
                                ->label('Product Description')
                                ->fileAttachmentsDirectory('products')
                                ->columnSpan('full'),
                            Forms\Components\TextInput::make('price')
                                ->required()
                                ->numeric()
                                ->default(0)
                                ->prefix('$'),
                            Forms\Components\Toggle::make('is_active')
                                ->required(),
                            Forms\Components\Toggle::make('is_featured')
                                ->required(),
                            Forms\Components\Toggle::make('in_stock')
                                ->required(),
                            Forms\Components\Toggle::make('on_sale')
                                ->required(),
                        ])
                        ->collapsible()
                        ->collapsed(true)
                        ->columns(2)  // This section uses two columns of the parent group
                ])->columnSpan(2),  // This group spans two out of three columns in the form
                // This group will also span one column and contains the Price section.
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Price Information')
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->numeric()
                                ->required()
                                ->prefix('$')
                                ->label('Product Price'),
                        ])->collapsible(),
                    Forms\Components\Section::make('Images')
                        ->schema([
                            Forms\Components\FileUpload::make('images')
                                ->multiple()
                                ->directory('products')
                                ->maxFiles(5)
                                ->reorderable(),
                        ])->collapsible(),
                ]),
            ])
            ->columns(3);  // Total columns for the form
    }

        public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filters can be defined here
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

    public static function getRelations(): array
    {
        return [
            // Define relationships if any
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
