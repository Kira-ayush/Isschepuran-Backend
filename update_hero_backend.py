import os

models_dir = 'app/Models'
filament_pages_dir = 'app/Filament/Pages'

heroes = [
    'AboutHero',
    'ContactHero',
    'GalleryHero',
    'GetInvolvedHero',
    'ImpactHero',
    'InitiativesHero'
]

# Update Models
for hero in heroes:
    model_path = os.path.join(models_dir, f"{hero}.php")
    if os.path.exists(model_path):
        with open(model_path, 'r') as f:
            content = f.read()
        
        # Replace fillable
        old_fillable = "protected $fillable = ['headline', 'subheading'];"
        new_fillable = "protected $fillable = ['headline', 'subheading', 'background_image', 'text_alignment', 'show_glassmorphism_button'];"
        if old_fillable in content:
            content = content.replace(old_fillable, new_fillable)
            with open(model_path, 'w') as f:
                f.write(content)
            print(f"Updated {model_path}")
        else:
            print(f"Fillable not found in {model_path}")

# Update Filament Pages
components_to_add = """            Forms\Components\FileUpload::make('background_image')->image()->directory('heroes'),
            Forms\Components\Select::make('text_alignment')
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ])
                ->default('center')
                ->required(),
            Forms\Components\Toggle::make('show_glassmorphism_button')
                ->label('Show Glassmorphism Toggle Button')
                ->default(true),"""

for hero in heroes:
    page_path = os.path.join(filament_pages_dir, f"Manage{hero}.php")
    if os.path.exists(page_path):
        with open(page_path, 'r') as f:
            content = f.read()
        
        target = "Forms\\Components\\Textarea::make('subheading')->required()->rows(3),"
        if target in content and "background_image" not in content:
            replacement = f"{target}\n{components_to_add}"
            content = content.replace(target, replacement)
            with open(page_path, 'w') as f:
                f.write(content)
            print(f"Updated {page_path}")
        else:
            print(f"Target not found or already updated in {page_path}")
