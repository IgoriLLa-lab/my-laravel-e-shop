<?php

namespace App\Filament\Resources;

use App\Enum\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('phone'),
                Forms\Components\Select::make('status')
                ->options(OrderStatus::class)
                ->label('Status'),
                Forms\Components\Placeholder::make('products_count')
                    ->label('Кол-во товаров')
                    ->content(fn (Order $record) => $record->products()->sum('order_product.count')),
                Repeater::make('products')
                    ->label('Товары в заказе')
                    ->relationship('products')
                    ->schema([
                        Forms\Components\TextInput::make('name')->disabled(),
                        FileUpload::make('image')->label('Картинка')
                            ->disk('public')->image()->disabled(),
                        Forms\Components\TextInput::make('price')->disabled(),
                        Forms\Components\TextInput::make('count')
                            ->label('Количество')
                            ->disabled()
                            ->formatStateUsing(fn ($record) => $record->pivot->count ?? 999),
                    ])
                    ->columns(4)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable()->label('ID'),
                Tables\Columns\TextColumn::make('name')->label('Имя клиента'),
                Tables\Columns\TextColumn::make('phone')->label('Телефон'),
                TextColumn::make('created_at'),
                TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn (OrderStatus $state) => $state->getLabel()),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
