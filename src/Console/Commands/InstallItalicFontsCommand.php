<?php

namespace YourName\ItalicFonts\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallItalicFontsCommand extends Command
{
    protected $signature = 'italic-fonts:install';
    protected $description = 'Install the Italic Fonts package';
    
    public function handle()
    {
        $this->info('🎨 Installing Italic Fonts Package...');
        
        // Publish configuration
        $this->call('vendor:publish', [
            '--provider' => 'YourName\\ItalicFonts\\Providers\\ItalicFontsServiceProvider',
            '--tag' => 'config'
        ]);
        
        // Add environment variables
        $this->addEnvironmentVariables();
        
        // Create example CSS file
        $this->createExampleCss();
        
        // Show usage instructions
        $this->showInstructions();
        
        $this->info('✅ Italic Fonts package installed successfully!');
        $this->info('📖 All your Blade views will now render in italic font.');
    }
    
    protected function addEnvironmentVariables()
    {
        $envPath = base_path('.env');
        
        if (File::exists($envPath)) {
            $envContent = File::get($envPath);
            
            $variables = [
                'ITALIC_FONTS_ENABLED=true',
                'ITALIC_FONTS_FAMILY=inherit',
                'ITALIC_FONTS_INTENSITY=5',
            ];
            
            foreach ($variables as $variable) {
                $key = explode('=', $variable)[0];
                if (!str_contains($envContent, $key)) {
                    File::append($envPath, PHP_EOL . $variable);
                    $this->info("Added {$key} to .env file");
                }
            }
        }
    }
    
    protected function createExampleCss()
    {
        $cssContent = <<<'CSS'
/* Custom overrides for Italic Fonts package */
.no-italic {
    font-style: normal !important;
    transform: none !important;
}



/* Code elements should not be italic */
code, pre, kbd, samp {
    font-style: normal !important;
    transform: none !important;
    font-family: 'Courier New', monospace !important;
}
CSS;
        
        $cssPath = resource_path('css/vendor/italic-fonts.css');
        File::ensureDirectoryExists(dirname($cssPath));
        File::put($cssPath, $cssContent);
        
        $this->info("📁 Created custom CSS file: resources/css/vendor/italic-fonts.css");
    }
    
    protected function showInstructions()
    {
        $this->newLine();
        $this->info('📝 Usage Instructions:');
        $this->line('1. All text in your Blade views will automatically be italicized');
        $this->line('2. To disable: set ITALIC_FONTS_ENABLED=false in .env');
        $this->line('3. To exclude elements, add class "no-italic"');
        $this->line('4. Adjust intensity with ITALIC_FONTS_INTENSITY (1-10)');
        $this->line('5. Run "php artisan italic-fonts:disable" to temporarily disable');
        $this->newLine();
    }
}