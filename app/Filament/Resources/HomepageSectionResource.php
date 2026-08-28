<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomepageSectionResource\Pages;
use App\Models\HomepageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Section Details')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->options([
                                'announcement_bar' => 'Announcement Bar',
                                'hero' => 'Hero Section',
                                'featured_categories' => 'Featured Categories',
                                'promotional_banners' => 'Promotional Banners',
                                'best_sellers' => 'Best Sellers',
                                'new_arrivals' => 'New Arrivals',
                                'on_sale' => 'Products on Sale',
                                'artisan_story' => 'Artisan / Heritage Story',
                                'curated_collections' => 'Curated Collections',
                                'recently_viewed' => 'Recently Viewed',
                                'testimonials' => 'Testimonials',
                                'newsletter' => 'Newsletter',
                                'footer' => 'Footer',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('title')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('subtitle')
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('content')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Media & Links')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('homepage'),
                        Forms\Components\TextInput::make('link')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('link_text')
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Products')
                    ->schema([
                        Forms\Components\Select::make('products')
                            ->relationship('products', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->helperText('Select featured products for this section'),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\KeyValue::make('settings')
                            ->helperText('Key-value pairs for section-specific settings'),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'primary',
                        'best_sellers' => 'success',
                        'new_arrivals' => 'info',
                        'on_sale' => 'warning',
                        'newsletter' => 'gray',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'announcement_bar' => 'Announcement Bar',
                        'hero' => 'Hero Section',
                        'featured_categories' => 'Featured Categories',
                        'best_sellers' => 'Best Sellers',
                        'new_arrivals' => 'New Arrivals',
                        'on_sale' => 'Products on Sale',
                        'artisan_story' => 'Artisan Story',
                        'newsletter' => 'Newsletter',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomepageSections::route('/'),
            'create' => Pages\CreateHomepageSection::route('/create'),
            'edit' => Pages\EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
