<?php

namespace YourName\ItalicFonts;
use Illuminate\Support\Facades\Config;
class ItalicFonts
{
    protected $config;
    
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }
    
    public function enable()
    {
        Config::set('italic-fonts.enabled', true);
        return $this;
    }
    
    public function disable()
    {
        Config::set('italic-fonts.enabled', false);
        return $this;
    }
    
    public function isEnabled()
    {
        return config('italic-fonts.enabled', false);
    }
    
    public function setIntensity($intensity)
    {
        Config::set('italic-fonts.intensity', $intensity);
        return $this;
    }
    
    public function addExcludedSelector($selector)
    {
        $excluded = config('italic-fonts.exclude_selectors', []);
        $excluded[] = $selector;
        Config::set('italic-fonts.exclude_selectors', array_unique($excluded));
        return $this;
    }
    
    public function applyToView($viewContent)
    {
        // Directly apply italics to HTML content
        $pattern = '/(<[^>]*?)(style="[^"]*")([^>]*>)/i';
        
        $replaced = preg_replace_callback($pattern, function($matches) {
            $styles = $matches[2];
            // Add italic to existing styles or create new style attribute
            if (str_contains($styles, 'font-style')) {
                $styles = preg_replace('/font-style:\s*[^;"]*/', 'font-style: italic', $styles);
            } else {
                $styles = str_replace('style="', 'style="font-style: italic; ', $styles);
            }
            return $matches[1] . $styles . $matches[3];
        }, $viewContent);
        
        return $replaced ?: $viewContent;
    }
}