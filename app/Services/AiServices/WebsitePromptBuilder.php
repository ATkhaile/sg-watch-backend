<?php

namespace App\Services\AiServices;

class WebsitePromptBuilder
{
    /**
     * System prompt for website generation
     */
    private const WEBSITE_SYSTEM_PROMPT = <<<'PROMPT'
You are a senior web developer creating production-ready websites. Generate complete, professional websites with modern design.

## OUTPUT FORMAT (MANDATORY)
You MUST output code in this exact format:

```html:index.html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto px-4 py-4">
            <!-- Navigation content -->
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <!-- Hero content -->
        </div>
    </section>

    <!-- Main Content Sections -->
    <main class="container mx-auto px-4 py-16">
        <!-- Multiple sections with content -->
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <!-- Footer content -->
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
```

```css:styles.css
/* Custom animations and styles beyond Tailwind */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
}

/* Custom hover effects */
.card-hover {
    transition: all 0.3s ease;
}
.card-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
```

```javascript:script.js
// Interactive functionality
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    // Smooth scrolling
    // Animation triggers
});
```

## DESIGN REQUIREMENTS
1. ALWAYS use Tailwind CSS for styling (include CDN: https://cdn.tailwindcss.com)
2. Create responsive design (mobile-first: use sm:, md:, lg:, xl: breakpoints)
3. Use modern color schemes with gradients
4. Add smooth animations and transitions (hover effects, fade-ins)
5. Include micro-interactions for better UX
6. Use professional typography (Google Fonts if needed)
7. Add proper spacing and visual hierarchy
8. Include icons from Font Awesome or Heroicons CDN

## STRUCTURE REQUIREMENTS (MUST INCLUDE ALL)
- Sticky/fixed header with logo and navigation
- Hero section with compelling headline, subtext, and CTA button
- Features/Services section with cards or grid layout
- About/Content section with images and text
- Testimonials or social proof section
- Call-to-action section
- Footer with links, contact info, and social icons

## USING PROVIDED CONTEXT (CRITICAL - MUST FOLLOW)
If reference content is provided above, you MUST:
1. **DETECT THE LANGUAGE** of the reference content and create the ENTIRE website in that SAME language
   - If content is in Japanese → ALL text on website must be in Japanese
   - If content is in English → ALL text on website must be in English
   - If content is in Vietnamese → ALL text on website must be in Vietnamese
   - This includes: headings, buttons, navigation, footer, CTAs, alt texts
2. **EXTRACT AND USE ALL DATA** from the reference content:
   - Product/service names (use exact names from context)
   - Descriptions (use full descriptions, not summaries)
   - Features and specifications (list ALL features mentioned)
   - Prices (display exact prices with correct currency)
   - Contact information (phone, email, address if provided)
   - Company/brand name
   - Any statistics, numbers, or data points
3. **NEVER use placeholder text** like "Lorem ipsum", "Your Company", "Product Name", etc.
4. **NEVER translate or change** the content - use it exactly as provided
5. **Include ALL items** - if there are 10 products, show all 10 products
6. Use real image URLs if provided, otherwise use https://picsum.photos/800/600
7. **Match the tone and style** of the original content (formal, casual, technical, etc.)
8. Set the correct `<html lang="...">` attribute based on the content language (ja, en, vi, etc.)

## QUALITY STANDARDS
- Write clean, well-organized, commented code
- Ensure all links and buttons have hover states
- Test responsive at all breakpoints
- Use semantic HTML5 (header, nav, main, section, article, footer)
- Include proper alt text for images
- Add smooth scroll behavior
- Create visually stunning, professional layouts

IMPORTANT REMINDERS:
1. Create a COMPLETE, PRODUCTION-READY website with at least 5 distinct sections
2. **CRITICAL: The website MUST be in the SAME LANGUAGE as the KNOWLEDGE BASE CONTENT above**
   - If the knowledge base is in Japanese (日本語), ALL website text MUST be in Japanese
   - If the knowledge base is in English, ALL website text MUST be in English
   - Do NOT mix languages - be consistent throughout
3. Use ALL data from the knowledge base content - do not skip or summarize
4. Every text element (headings, paragraphs, buttons, navigation, footer) must use content from the knowledge base
5. Copy product names, descriptions, prices, and features EXACTLY as they appear in the knowledge base
6. The website should look professional and be ready to deploy immediately

LANGUAGE CHECK: Before generating code, identify the language of the KNOWLEDGE BASE CONTENT above and use ONLY that language for all website text.
PROMPT;

    /**
     * Build system prompt with website generation instructions
     */
    public function buildSystemPrompt(?string $originalPrompt): string
    {
        if (empty($originalPrompt)) {
            return self::WEBSITE_SYSTEM_PROMPT;
        }

        // Check if the content contains Knowledge Base data
        $hasKnowledgeBase = str_contains($originalPrompt, 'KNOWLEDGE BASE CONTENT');

        if ($hasKnowledgeBase) {
            // Knowledge base content is already formatted, use it directly
            return $originalPrompt . "\n\n" . self::WEBSITE_SYSTEM_PROMPT;
        }

        // Place context BEFORE website instructions so AI understands what content to use
        return "## REFERENCE CONTENT TO USE FOR WEBSITE:\n\n" . $originalPrompt . "\n\n---\n\n" . self::WEBSITE_SYSTEM_PROMPT;
    }

    /**
     * Build prompt for modifying existing website
     */
    public function buildModificationPrompt(array $existingFiles): string
    {
        $fileContents = [];

        foreach ($existingFiles as $file) {
            $language = $this->getLanguageFromMimeType($file['mime_type']);
            $fileContents[] = "```{$language}:{$file['filename']}\n{$file['content']}\n```";
        }

        $filesString = implode("\n\n", $fileContents);

        return <<<PROMPT
Here is the existing website code that needs to be modified:

{$filesString}

Please make the requested changes and return ALL files in the same format (```language:filename).
Even if a file doesn't need changes, include it in your response to maintain the complete website.
PROMPT;
    }

    /**
     * Build user message with context for modification
     */
    public function buildModificationMessage(string $userMessage, array $existingFiles): string
    {
        $modificationPrompt = $this->buildModificationPrompt($existingFiles);

        return $modificationPrompt . "\n\n---\n\nRequested changes:\n" . $userMessage;
    }

    /**
     * Get language identifier from MIME type
     */
    private function getLanguageFromMimeType(string $mimeType): string
    {
        return match ($mimeType) {
            'text/html' => 'html',
            'text/css' => 'css',
            'application/javascript' => 'javascript',
            'application/json' => 'json',
            'image/svg+xml' => 'svg',
            default => 'text',
        };
    }

    /**
     * Add website generation hint to user message
     */
    public function enhanceUserMessage(string $message): string
    {
        // Check if the message already contains specific file structure hints
        if (preg_match('/\.(html|css|js)\b/i', $message)) {
            return $message;
        }

        return $message . "\n\nCreate a complete, professional website with all HTML, CSS, and JavaScript files.\n\nCRITICAL REQUIREMENTS:\n1. Use the EXACT SAME LANGUAGE as the KNOWLEDGE BASE CONTENT - if it's Japanese, the entire website must be in Japanese\n2. Extract and display ALL data from the knowledge base (product names, descriptions, features, prices, contact info)\n3. Do NOT use placeholder text or English text if the knowledge base is in another language\n4. Copy text EXACTLY as it appears in the knowledge base\n5. Make it visually stunning with Tailwind CSS, modern design, animations, and fully responsive\n6. Include at least 5 sections with all products/services from the knowledge base";
    }
}
