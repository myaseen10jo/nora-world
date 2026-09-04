<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use App\Models\ProductMedia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Catalog';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Information')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('sku')
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\RichEditor::make('description')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('short_description')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Pricing & Inventory')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        Forms\Components\TextInput::make('compare_at_price')
                            ->numeric()
                            ->prefix('$')
                            ->helperText('Original price before discount (optional)'),
                        Forms\Components\TextInput::make('stock_quantity')
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('in_stock')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(3),

                Forms\Components\Section::make('Categories & Collections')
                    ->schema([
                        Forms\Components\Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('collections')
                            ->relationship('collections', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload(),
                    ])->columns(2),

                Forms\Components\Section::make('Handmade & Heritage Details')
                    ->schema([
                        Forms\Components\Select::make('origin_type')
                            ->options([
                                'jordan' => 'Jordan',
                                'palestine' => 'Palestine',
                                'jordan_and_palestine' => 'Jordan and Palestine',
                                'other' => 'Other',
                            ])
                            ->default('other'),
                        Forms\Components\TextInput::make('origin_country')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('artisan_name')
                            ->maxLength(255)
                            ->helperText('Name of the artisan or workshop'),
                        Forms\Components\RichEditor::make('product_story')
                            ->label('Product Story')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('materials_used')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('handmade_technique')
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('care_instructions')
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('cultural_note')
                            ->label('Cultural or Heritage Note')
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Shipping & Dimensions')
                    ->schema([
                        Forms\Components\TextInput::make('weight')
                            ->numeric()
                            ->suffix('kg'),
                        Forms\Components\TextInput::make('dimensions')
                            ->maxLength(255)
                            ->helperText('e.g., 30cm x 20cm x 5cm'),
                        Forms\Components\TextInput::make('height_cm')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('width_cm')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('depth_cm')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('estimated_preparation_time')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('estimated_shipping_time')
                            ->maxLength(255),
                    ])->columns(3),

                // ──── Extracted / OCR Specifications ────
                Forms\Components\Section::make('Extracted Specifications (OCR)')
                    ->description('Data extracted from product images — color, condition, age, and style analysis.')
                    ->icon('heroicon-o-magnifying-glass')
                    ->collapsible()
                    ->schema([
                        Forms\Components\TextInput::make('color_palette')
                            ->label('Color Palette')
                            ->maxLength(255)
                            ->helperText('e.g., Warm cream, terracotta, sage green accents'),
                        Forms\Components\TextInput::make('color_primary')
                            ->label('Primary Color')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('color_secondary')
                            ->label('Secondary Color')
                            ->maxLength(100),
                        Forms\Components\TextInput::make('condition')
                            ->maxLength(255)
                            ->helperText('e.g., Good — minor glaze crazing consistent with age'),
                        Forms\Components\TextInput::make('age_estimate')
                            ->label('Age Estimate')
                            ->maxLength(255)
                            ->helperText('e.g., Mid-20th century (estimated)'),
                        Forms\Components\Textarea::make('style_notes')
                            ->label('Style Notes')
                            ->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Special Options')
                    ->schema([
                        Forms\Components\Toggle::make('is_one_of_a_kind')
                            ->default(false),
                        Forms\Components\Toggle::make('is_made_to_order')
                            ->default(false),
                        Forms\Components\Toggle::make('gift_wrapping_available')
                            ->default(false),
                    ])->columns(3),

                // ──── Product Images Gallery ────
                Forms\Components\Section::make('Product Images')
                    ->description('Upload high-quality product photos. The first image marked as primary will be used as the main product image on the storefront.')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\FileUpload::make('path')
                                            ->label('Image')
                                            ->image()
                                            ->imageEditor()
                                            ->imageCropAspectRatio('1:1')
                                            ->imageResizeTargetWidth('1000')
                                            ->imageResizeTargetHeight('1000')
                                            ->directory('products')
                                            ->visibility('public')
                                            ->maxSize(5120) // 5MB
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                                            ->downloadable(false)
                                            ->openable()
                                            ->previewable(true)
                                            ->panelLayout('integrated')
                                            ->columnSpan(2),

                                        Forms\Components\TextInput::make('alt_text')
                                            ->label('Alt Text')
                                            ->maxLength(255)
                                            ->helperText('Describe the image for accessibility & SEO')
                                            ->columnSpan(1),

                                        Forms\Components\Grid::make(1)
                                            ->schema([
                                                Forms\Components\Toggle::make('is_primary')
                                                    ->label('Primary Image')
                                                    ->default(false)
                                                    ->inline(false)
                                                    ->reactive()
                                                    ->afterStateUpdated(function ($state, Forms\Set $set, $get, $livewire) {
                                                        if ($state) {
                                                            // Uncheck all other primary toggles
                                                            $images = $get('../../images') ?? [];
                                                            foreach ($images as $index => $image) {
                                                                if ($index !== $get('../../images_loop_index') && ($image['is_primary'] ?? false)) {
                                                                    $set("../../images.{$index}.is_primary", false);
                                                                }
                                                            }
                                                        }
                                                    }),

                                                Forms\Components\TextInput::make('sort_order')
                                                    ->label('Sort Order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),
                                            ])->columnSpan(1),
                                    ]),
                            ])
                            ->columns(1)
                            ->defaultItems(1)
                            ->addActionLabel('Add Image')
                            ->reorderable('sort_order')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemHeading(fn (array $state): ?string => $state['alt_text'] ?? ($state['path'] ? 'Image' : 'New Image'))
                            ->deleteAction(
                                fn ($action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('Delete Image')
                                    ->modalDescription('Are you sure you want to remove this product image?')
                                    ->modalSubmitActionLabel('Delete'),
                            )
                            ->columnSpanFull(),
                    ]),

                // ──── Product Media Gallery (Videos & Content) ────
                Forms\Components\Section::make('Media Gallery')
                    ->description('Add videos, YouTube/Vimeo embeds, and rich content to this product.')
                    ->icon('heroicon-o-film')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Repeater::make('media')
                            ->relationship()
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('type')
                                            ->options([
                                                'image' => 'Image',
                                                'video' => 'Video Upload',
                                                'youtube' => 'YouTube Video',
                                                'vimeo' => 'Vimeo Video',
                                                'content' => 'Rich Content / Text',
                                            ])
                                            ->required()
                                            ->default('image')
                                            ->live()
                                            ->columnSpan(1),

                                        Forms\Components\TextInput::make('title')
                                            ->label('Title')
                                            ->maxLength(255)
                                            ->columnSpan(1),
                                    ]),

                                // Conditional fields based on type
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\FileUpload::make('file_path')
                                            ->label('File')
                                            ->image()
                                            ->imageEditor()
                                            ->directory('product-media')
                                            ->visibility('public')
                                            ->maxSize(51200) // 50MB for videos
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/webm', 'video/quicktime'])
                                            ->downloadable(false)
                                            ->previewable(true)
                                            ->panelLayout('integrated')
                                            ->columnSpan(1)
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['image', 'video'])),

                                        Forms\Components\TextInput::make('external_url')
                                            ->label('Video URL')
                                            ->url()
                                            ->placeholder('https://www.youtube.com/watch?v=...')
                                            ->helperText('Paste YouTube or Vimeo URL')
                                            ->columnSpan(1)
                                            ->visible(fn (Forms\Get $get) => in_array($get('type'), ['youtube', 'vimeo'])),
                                    ]),

                                Forms\Components\RichEditor::make('content_html')
                                    ->label('Rich Content')
                                    ->columnSpanFull()
                                    ->visible(fn (Forms\Get $get) => $get('type') === 'content'),

                                Forms\Components\Textarea::make('description')
                                    ->label('Description')
                                    ->maxLength(65535)
                                    ->columnSpanFull(),

                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\Toggle::make('is_primary')
                                            ->label('Primary Media')
                                            ->default(false)
                                            ->inline(false),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active')
                                            ->default(true),
                                        Forms\Components\TextInput::make('sort_order')
                                            ->label('Sort')
                                            ->numeric()
                                            ->default(0),
                                    ]),
                            ])
                            ->columns(1)
                            ->defaultItems(0)
                            ->addActionLabel('Add Media')
                            ->reorderable('sort_order')
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemHeading(fn (array $state): ?string => $state['title'] ?? ucfirst($state['type'] ?? 'Media'))
                            ->deleteAction(
                                fn ($action) => $action
                                    ->requiresConfirmation()
                                    ->modalHeading('Delete Media')
                                    ->modalDescription('Are you sure you want to remove this media item?')
                                    ->modalSubmitActionLabel('Delete'),
                            )
                            ->columnSpanFull(),
                    ]),

                // ──── Quick Gallery Preview ────
                Forms\Components\Placeholder::make('gallery_preview')
                    ->label('Gallery Preview')
                    ->content(function (?Product $record): ?string {
                        if (!$record || $record->images->isEmpty()) {
                            return 'No images uploaded yet. Save the product to see the gallery preview.';
                        }
                        $count = $record->images->count();
                        $primary = $record->images->where('is_primary', true)->first();
                        $primaryLabel = $primary ? $primary->alt_text : 'None set';
                        return "{$count} image(s) uploaded. Primary: {$primaryLabel}";
                    })
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primaryImage.path')
                    ->label('Image')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-product.png'))
                    ->stacked(false),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sku')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('compare_at_price')
                    ->money('USD')
                    ->sortable()
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('stock_quantity')
                    ->sortable(),
                Tables\Columns\TextColumn::make('color_primary')
                    ->badge()
                    ->color(fn (?string $state): string => match (strtolower($state ?? '')) {
                        'cream' => 'warning',
                        'terracotta' => 'danger',
                        'navy' => 'info',
                        'gold' => 'warning',
                        'silver' => 'gray',
                        'brown' => 'warning',
                        'white' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('condition')
                    ->limit(30)
                    ->tooltip(fn (?string $state): ?string => $state),
                Tables\Columns\TextColumn::make('origin_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'jordan' => 'success',
                        'palestine' => 'warning',
                        'jordan_and_palestine' => 'info',
                        'other' => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('origin_type')
                    ->options([
                        'jordan' => 'Jordan',
                        'palestine' => 'Palestine',
                        'jordan_and_palestine' => 'Jordan and Palestine',
                        'other' => 'Other',
                    ]),
                Tables\Filters\TernaryFilter::make('is_featured'),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('in_stock'),
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

    public static function getRelations(): array
    {
        return [];
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
