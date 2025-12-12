<?php

namespace YourName\ItalicFonts\Middleware;

use Closure;
use Illuminate\Http\Request;

class ItalicizeFonts
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only apply to HTML responses
        if ($this->shouldApplyItalics($request) && 
            $response->headers->get('Content-Type') &&
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            
            $content = $response->getContent();
            $modifiedContent = $this->addItalicStyles($content);
            $response->setContent($modifiedContent);
        }
        
        return $response;
    }
    
    protected function shouldApplyItalics(Request $request)
    {
        $config = config('italic-fonts');
        
        if (!$config['enabled']) {
            return false;
        }
        
        // Check if route is in included routes
        if (!empty($config['routes'])) {
            foreach ($config['routes'] as $route) {
                if ($route === '*' || $request->is($route)) {
                    return true;
                }
            }
            return false;
        }
        
        return true;
    }
    
    protected function addItalicStyles($content)
    {
        $config = config('italic-fonts');
        
        // Create CSS to make all text italic
        $css = $this->generateItalicCss($config);
        
        // Inject CSS into head
        $headPosition = strpos($content, '</head>');
        
        if ($headPosition !== false) {
            $styleTag = "<style id=\"italic-fonts-styles\">{$css}</style>";
            $content = substr_replace($content, $styleTag, $headPosition, 0);
        }
        
        return $content;
    }
    
    protected function generateItalicCss($config)
    {
        $selectors = '*';
        
        if (!empty($config['include_selectors'])) {
            $selectors = implode(', ', $config['include_selectors']);
        }
        
        $excludeSelectors = implode(', ', $config['exclude_selectors']);
        
        $intensity = min(10, max(1, $config['intensity']));
        $obliqueValue = ($intensity * 10) . 'deg';
        
        return <<<CSS
        /* Italic Fonts Package Styles */
        {$selectors} {
            font-style: italic !important;
            font-family: {$config['font_family']} !important;
            transform: skewX({$obliqueValue}) !important;
            transform-origin: left bottom !important;
            display: inline-block !important;
        }
        
        /* Excluded elements */
        {$excludeSelectors} {
            font-style: normal !important;
            transform: none !important;
        }
        
        /* Special handling for form elements */
        input, textarea, select, button {
            font-style: normal !important;
            transform: none !important;
        }
CSS;
    }
}