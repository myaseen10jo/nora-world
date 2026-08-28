<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class InventoryStatusWidget extends TableWidget
{
    protected static ?string $heading = 'Inventory Status';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Product::query()->orderBy('stock_quantity'))
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage.path')
                    ->circular()
                    ->label('Image'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('sku'),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'danger',
                        $state <= 2 => 'warning',
                        default => 'success',
                    }),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_one_of_a_kind')
                    ->boolean()
                    ->label('Unique'),
            ])
            ->defaultSort('stock_quantity')
            ->paginated([10]);
    }
}
