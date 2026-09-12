const fs = require('fs');
const path = require('path');

const filamentPagesDir = 'app/Filament/Pages';
const heroes = [
    'AboutHero',
    'ContactHero',
    'GalleryHero',
    'GetInvolvedHero',
    'ImpactHero',
    'InitiativesHero'
];

heroes.forEach(hero => {
    const pagePath = path.join(filamentPagesDir, `Manage${hero}.php`);
    if (fs.existsSync(pagePath)) {
        let content = fs.readFileSync(pagePath, 'utf8');
        
        const target = "Forms\\Components\\FileUpload::make('background_image')->image()->directory('heroes'),";
        const replacement = "Forms\\Components\\FileUpload::make('background_image')->image()->directory('heroes')->disk('public'),";
        
        if (content.includes(target)) {
            content = content.replace(target, replacement);
            fs.writeFileSync(pagePath, content);
            console.log(`Fixed disk for ${pagePath}`);
        } else {
            console.log(`Target not found or already fixed in ${pagePath}`);
        }
    }
});
