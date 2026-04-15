<?php

$dir = 'd:/laragon/www/casureco-dms/resources/views';

$replacements = [
    'SELECT GROUP' => 'SELECT LOCATION',
    'SELECT GROUP' => 'SELECT LOCATION',
    'Group <span class="text-red-500">*</span>' => 'Location <span class="text-red-500">*</span>',
    'Group <span' => 'Location <span',
    'label for="group"' => 'label for="group"', // ID stays same for convenience unless I want to change IDs too. User didn't ask to change IDs.
    '>Group</label>' => '>Location</label>',
    '>Group <' => '>Location <',
    'STANDBY (AVAILABLE)' => 'STORAGE AVAILABLE',
    'ASSIGN TO EMPLOYEE' => 'DEPLOYMENT',
    'ALL GROUPS' => 'ALL LOCATIONS',
    'Group:' => 'Location:',
    'GROUP:' => 'LOCATION:',
    'GROUP' => 'LOCATION', // Be careful with this one, but usually labels are in uppercase.
];

// Context-aware replacements for labels
$labelReplacements = [
    '/\bGROUP\b(?![^<]*>)/' => 'LOCATION', // Replace GROUP word if not inside a tag (roughly)
];

function processDir($dir, $replacements) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            $originalContent = $content;

            foreach ($replacements as $search => $replace) {
                $content = str_replace($search, $replace, $content);
            }
            
            // Specifically target uppercase label text
            $content = preg_replace('/>GROUP<\/label>/', '>LOCATION</label>', $content);
            $content = preg_replace('/>GROUP </', '>LOCATION <', $content);
            $content = preg_replace('/label(.*?)>GROUP/s', 'label$1>LOCATION', $content);

            if ($content !== $originalContent) {
                file_put_contents($file->getPathname(), $content);
                echo "Updated: " . $file->getPathname() . "\n";
            }
        }
    }
}

processDir($dir, $replacements);
echo "Done.\n";
