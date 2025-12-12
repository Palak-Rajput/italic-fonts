<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Italic Fonts Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can configure the behavior of the italic fonts package.
    |
    */
    
    'enabled' => env('ITALIC_FONTS_ENABLED', true),
    
    'font_family' => env('ITALIC_FONTS_FAMILY', 'inherit'),
    
    // Intensity of italic effect (1-10)
    'intensity' => env('ITALIC_FONTS_INTENSITY', 2),
    
    // Exclude certain elements
    'exclude_selectors' => [
        'button',
        'input',
        'textarea',
        'select',
        '.no-italic',
        '[data-no-italic]',
    ],
    
    // Include only specific elements (empty means all)
    'include_selectors' => [],
    
    // Apply to specific routes only
    'routes' => [
        // 'admin/*',
        // 'dashboard/*',
        // '*' // All routes
    ],
];