const fs = require('fs');
const path = require('path');

const resourcesDir = 'app/Http/Resources';

const heroes = [
    'AboutHero',
    'ContactHero',
    'GalleryHero',
    'GetInvolvedHero',
    'ImpactHero',
    'InitiativesHero'
];

heroes.forEach(hero => {
    const resourcePath = path.join(resourcesDir, `${hero}Resource.php`);
    if (fs.existsSync(resourcePath)) {
        let content = fs.readFileSync(resourcePath, 'utf8');
        
        const oldArray = "'subheading' => $this->subheading,";
        const newArray = `'subheading' => $this->subheading,
            'backgroundImage' => $this->background_image ? url('storage/' . $this->background_image) : null,
            'textAlignment' => $this->text_alignment,
            'showGlassmorphismButton' => (bool) $this->show_glassmorphism_button,`;
        
        if (content.includes(oldArray) && !content.includes("backgroundImage")) {
            content = content.replace(oldArray, newArray);
            fs.writeFileSync(resourcePath, content);
            console.log(`Updated ${resourcePath}`);
        } else {
            console.log(`Not found or already updated in ${resourcePath}`);
        }
    }
});
