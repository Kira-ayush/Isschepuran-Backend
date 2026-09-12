const fs = require('fs');
const path = require('path');

const modelsDir = 'app/Models';
const filamentPagesDir = 'app/Filament/Pages';

const heroes = [
    'AboutHero',
    'ContactHero',
    'GalleryHero',
    'GetInvolvedHero',
    'ImpactHero',
    'InitiativesHero'
];

// Update Models
heroes.forEach(hero => {
    const modelPath = path.join(modelsDir, `${hero}.php`);
    if (fs.existsSync(modelPath)) {
        let content = fs.readFileSync(modelPath, 'utf8');
        
        const oldFillable = "protected $fillable = ['headline', 'subheading'];";
        const newFillable = "protected $fillable = ['headline', 'subheading', 'background_image', 'text_alignment', 'show_glassmorphism_button'];";
        
        if (content.includes(oldFillable)) {
            content = content.replace(oldFillable, newFillable);
            fs.writeFileSync(modelPath, content);
            console.log(`Updated ${modelPath}`);
        } else {
            console.log(`Fillable not found in ${modelPath}`);
        }
    }
});

// Update Filament Pages
const componentsToAdd = `            Forms\\Components\\FileUpload::make('background_image')->image()->directory('heroes'),
            Forms\\Components\\Select::make('text_alignment')
                ->options([
                    'left' => 'Left',
                    'center' => 'Center',
                    'right' => 'Right',
                ])
                ->default('center')
                ->required(),
            Forms\\Components\\Toggle::make('show_glassmorphism_button')
                ->label('Show Glassmorphism Toggle Button')
                ->default(true),`;

heroes.forEach(hero => {
    const pagePath = path.join(filamentPagesDir, `Manage${hero}.php`);
    if (fs.existsSync(pagePath)) {
        let content = fs.readFileSync(pagePath, 'utf8');
        
        const target = "Forms\\Components\\Textarea::make('subheading')->required()->rows(3),";
        if (content.includes(target) && !content.includes("background_image")) {
            const replacement = `${target}\n${componentsToAdd}`;
            content = content.replace(target, replacement);
            fs.writeFileSync(pagePath, content);
            console.log(`Updated ${pagePath}`);
        } else {
            console.log(`Target not found or already updated in ${pagePath}`);
        }
    }
});
