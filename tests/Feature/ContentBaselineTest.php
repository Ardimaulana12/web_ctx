<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContentBaselineTest extends TestCase
{
    public function test_rendered_pages_match_original_content(): void
    {
        $this->withoutVite();
        $hashes = json_decode(file_get_contents(base_path('tests/Fixtures/website-baseline.json')), true, 512, JSON_THROW_ON_ERROR);
        foreach (['en', 'id'] as $locale) {
            foreach (['/' => 'home', '/about' => 'about', '/contact' => 'contact', '/product-and-services' => 'services', '/case-study' => 'cases', '/blog' => 'blog'] as $url => $name) {
                $html = $this->withSession(['locale' => $locale])->get($url)->assertOk()->getContent();
                $this->assertSame($hashes["$name/$locale"], hash('sha256', self::normalize($html)), "$name/$locale changed");
            }
        }
    }

    public static function normalize(string $html): string
    {
        // Ignore CSRF, build/dev asset injection, year, comments and formatting whitespace only.
        $html = preg_replace('/name="_token" value="[^"]+"/', 'name="_token" value="TOKEN"', $html);
        $html = preg_replace('~</title>.*?</head>~s', '</title></head>', $html);
        $html = preg_replace('/Copyright © \d{4}/', 'Copyright © YEAR', $html);
        $html = preg_replace('/<!--.*?-->/s', '', $html);
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        $html = preg_replace('/(<br\s*\/?>)\s+/', '$1', $html);

        return trim($html);
    }
}
