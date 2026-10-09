<?php
final class Seo
{
    public static function corePages(): array {
        return [
            '/' => [
                'name' => 'Homepage',
                'icon' => 'home-4-line',
                'desc' => 'Primary landing page & featured equipment showcase',
                'url' => url()
            ],
            '/about' => [
                'name' => 'About Us',
                'icon' => 'building-4-line',
                'desc' => 'Company legacy, filling plant & credibility',
                'url' => url('about')
            ],
            '/contact' => [
                'name' => 'Contact Us',
                'icon' => 'phone-line',
                'desc' => 'Direct procurement desks & priority 24/7 dispatch',
                'url' => url('contact')
            ],
            '/products' => [
                'name' => 'Equipment Catalogue',
                'icon' => 'store-2-line',
                'desc' => 'All medical & industrial products directory',
                'url' => url('products')
            ],
            '/use-cases' => [
                'name' => 'Care Settings',
                'icon' => 'hospital-line',
                'desc' => 'ICU, emergency, dental & industrial care settings',
                'url' => url('use-cases')
            ],
            '/case-studies' => [
                'name' => 'Case Studies',
                'icon' => 'article-line',
                'desc' => 'Hospital manifold installations & clinical impact',
                'url' => url('case-studies')
            ],
            '/blog' => [
                'name' => 'Journal & Insights',
                'icon' => 'newspaper-line',
                'desc' => 'PESO cylinder rules & medical gas safety protocols',
                'url' => url('blog')
            ],
            '/privacy' => [
                'name' => 'Privacy Policy',
                'icon' => 'shield-keyhole-line',
                'desc' => 'Data privacy charter (DPDP Act 2023)',
                'url' => url('privacy')
            ],
            '/terms' => [
                'name' => 'Terms & Conditions',
                'icon' => 'file-text-line',
                'desc' => 'Supply terms, cylinder rotation & security deposits',
                'url' => url('terms')
            ],
            '/compliance' => [
                'name' => 'Safety & Compliance',
                'icon' => 'award-line',
                'desc' => 'PESO regulations, 5-yr hydro testing & IP standards',
                'url' => url('compliance')
            ],
            '/shipping-policy' => [
                'name' => 'Delivery Policy',
                'icon' => 'truck-line',
                'desc' => 'Emergency 2-hr SLA & safe cylinder transport',
                'url' => url('shipping-policy')
            ]
        ];
    }

    public static function meta(string $key, string $title = '', string $description = '', string $image = ''): array {
        $stmt = Database::get()->prepare('SELECT * FROM seo_meta WHERE page_key = ?'); $stmt->execute([$key]); $row = $stmt->fetch() ?: [];
        return [
            'title'=>($row['meta_title'] ?? '') ?: ($title ? $title . ' | ' . SITE_NAME : setting('meta_title')),
            'description'=>($row['meta_desc'] ?? '') ?: ($description ?: setting('meta_description')),
            'keywords'=>($row['focus_keyword'] ?? '') ?: (setting('focus_keywords') ?: 'medical oxygen Agra, oxygen cylinders, surgical equipment, hospital gas manifold, emergency cylinder refill, Shree Ji Gas'),
            'canonical'=>($row['canonical_url'] ?? '') ?: SITE_URL . ($key === '/' ? '/' : '/'.trim($key,'/')),
            'robots'=>$row['robots'] ?? 'index,follow',
            'og_title'=>($row['og_title'] ?? '') ?: ($title ?: SITE_NAME),
            'og_desc'=>($row['og_desc'] ?? '') ?: ($description ?: setting('meta_description')),
            'image'=>($row['og_image'] ?? '') ?: ($image ?: 'assets/images/equipment.webp')
        ];
    }
    public function endpoint(string $path): never {
        if ($path === 'robots.txt') { header('Content-Type: text/plain'); echo "User-agent: *\nDisallow: " . url('admin/') . "\nDisallow: " . url('storage/') . "\nSitemap: " . SITE_URL . "/sitemap.xml\n"; exit; }
        header('Content-Type: application/xml; charset=utf-8');
        $paths=[''=>['1.0','weekly'],'products'=>['0.9','weekly'],'about'=>['0.6','monthly'],'contact'=>['0.7','monthly'],'privacy'=>['0.5','monthly'],'terms'=>['0.5','monthly'],'compliance'=>['0.6','monthly'],'shipping-policy'=>['0.6','monthly'],'blog'=>['0.7','weekly'],'case-studies'=>['0.6','weekly'],'use-cases'=>['0.6','monthly']];
        foreach (['products'=>'products','blog_posts'=>'blog','case_studies'=>'case-studies','use_cases'=>'use-cases'] as $table=>$prefix) foreach ((new ContentModel($table))->all(true) as $row) $paths[$prefix.'/'.$row['slug']]=['0.7','monthly'];
        foreach ((new ContentModel('pages'))->all(true) as $page) if (!isset($paths[$page['slug']])) $paths[$page['slug']]=['0.6','monthly'];
        foreach (allCategories() as $cat) $paths['categories/'.$cat['slug']]=['0.6','weekly'];
        $excluded=Database::get()->query("SELECT page_key FROM seo_meta WHERE robots LIKE 'noindex%'")->fetchAll(PDO::FETCH_COLUMN);
        echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach($paths as $p=>$options) if (!in_array('/'.$p,$excluded,true) && !in_array($p,$excluded,true)) echo '<url><loc>'.e(SITE_URL.'/'.$p).'</loc><changefreq>'.$options[1].'</changefreq><priority>'.$options[0].'</priority></url>';
        echo '</urlset>'; exit;
    }
}
