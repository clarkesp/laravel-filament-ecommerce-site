<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\Store;
use App\Models\Store\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;


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
                            Forms\Components\TextInput::make('name')
                                ->maxLength(255)
                                ->label('Product Name')
                                ->required()
                                ->label('The is a sentence')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function(string $operation, $state, \Filament\Forms\Set $set){
                                    if($operation !== 'create'){
                                        return;
                                    }
                                    $set('slug', Str::slug($state));
                                }),
                            Forms\Components\TextInput::make('slug')
                                ->maxLength(255)
                                ->required()
                                ->live()
                                ->disabled()
                                ->prefix('https://')
                                ->label('The is a sentence')
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),
                            Forms\Components\MarkdownEditor::make('description')
                                ->label('Product Description')
                                ->fileAttachmentsDirectory('products')
                                ->columnSpan('full'),
                            Forms\Components\Section::make('Images')
                                ->schema([
                                    Forms\Components\FileUpload::make('images')
                                        ->multiple()
                                        ->directory('products')
                                        ->maxFiles(5)
                                        ->reorderable(),
                                ])->collapsible(),
                        ])
                        ->collapsible()
                        ->collapsed(false)
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
                    Forms\Components\Section::make('Associations')
                        ->schema([
                            Forms\Components\Select::make('category_id')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->relationship('category', 'name'),
                            Forms\Components\Select::make('brand_id')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->relationship('brand', 'name'),
                            // Buttons
                            Forms\Components\Toggle::make('in_stock')
                                ->required()
                                ->default(true),
                            Forms\Components\Toggle::make('is_active')
                                ->required()
                                ->default(true),
                            Forms\Components\Toggle::make('is_featured')
                                ->required(),
                            Forms\Components\Toggle::make('on_sale')
                                ->required(),
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
                SelectFilter::make('category')
                    ->relationship('category', 'name'),
                SelectFilter::make('brand')
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->slideOver(),
                    Tables\Actions\EditAction::make()
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Store\ProductResource\Pages\ListProducts::route('/'),
            'create' => Store\ProductResource\Pages\CreateProduct::route('/create'),
            'edit' => Store\ProductResource\Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
