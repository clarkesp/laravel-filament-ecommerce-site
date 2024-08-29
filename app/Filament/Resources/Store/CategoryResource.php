<?php

namespace App\Filament\Resources\Store;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Filament\Resources\Store;
use App\Models\Store\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;


class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Section::make([  //Ensure this is the Forms Section component
                        Grid::make()  // Assuming you've aliased Forms\Components\Grid as FormsGrid
                            ->schema([
                            Forms\Components\TextInput::make('name')
                                ->maxLength(255)
                                ->label('Category Name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (string $operation, $state, \Filament\Forms\Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                            Forms\Components\TextInput::make('slug')
                                ->maxLength(255)
                                ->required()
                                ->live()
                                ->disabled()
                                ->prefix('https://')
                                ->label('SLUG: Choose the name first then you can alter the slug')
                                ->dehydrated()
                                ->unique(Category::class, 'slug', ignoreRecord: true),
                            ]),

                            FileUpload::make('image')
                                ->image()
                                ->directory('categories'),
                            Toggle::make('is_active')
                                ->required()
                                ->default(true),




                    ]),
                ]);
    }

        public static function table(Table $table): Table
    {
        return $table
            ->columns([
                    Tables\Columns\ImageColumn::make('image')
                        ->square(),
                    Tables\Columns\TextColumn::make('name')
                        ->description("This is a descrtiption")
                        ->sortable()
                        ->searchable(),
                    Tables\Columns\TextColumn::make('slug')
                        ->sortable()
                        ->searchable(),
                    Tables\Columns\IconColumn::make('is_active')
                        ->label('Status')
                        ->sortable()
                        ->boolean(),
                    Tables\Columns\TextColumn::make('created_at')
                        ->dateTime()
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->dateTime()
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Store\CategoryResource\Pages\ListCategories::route('/'),
            'create' => Store\CategoryResource\Pages\CreateCategory::route('/create'),
            'edit' => Store\CategoryResource\Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
