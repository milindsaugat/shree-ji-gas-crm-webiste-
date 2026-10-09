<?php
declare(strict_types=1);
require dirname(__DIR__) . '/config/config.php';
spl_autoload_register(function (string $class): void {
    foreach (['config','helpers','models','controllers','controllers/Admin'] as $dir) {
        $file = ROOT . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) { require $file; return; }
    }
});
require ROOT . '/helpers/functions.php';

$pdo = Database::get();

// 1. Delete contact from pages table if present (contact is managed via Site Settings + contact view)
$pdo->exec("DELETE FROM pages WHERE slug = 'contact'");
echo "Cleaned 'contact' from pages table.\n";

// 2. Remove individual product / blog clutter from seo_meta table
$pdo->exec("DELETE FROM seo_meta WHERE page_key LIKE '/products/%' OR page_key LIKE '/blog/%' OR page_key LIKE '/case-studies/%' OR page_key LIKE '/use-cases/%'");
echo "Cleaned individual entity item clutter from seo_meta.\n";

// 3. Define the 11 Core Website Pages with comprehensive, high-quality SEO metadata
$corePages = [
    '/' => [
        'meta_title' => 'Shree Ji Gas Industries | Medical & Industrial Gas Supply in Agra',
        'meta_desc' => 'Leading PESO-approved manufacturer & distributor of medical oxygen, nitrous oxide, liquid gas installations, and cylinder manifolds across Agra & NCR.',
        'focus_keyword' => 'medical oxygen Agra, oxygen cylinder supplier, hospital gas manifold, Shree Ji Gas',
        'og_title' => 'Shree Ji Gas Industries · Medical & Industrial Gas Equipment',
        'og_desc' => 'Equipping healthcare facilities and industrial plants with certified medical gases, emergency cylinder refills, and 24/7 priority dispatch.',
        'og_image' => 'assets/images/factory.jpg',
        'canonical_url' => SITE_URL . '/',
        'robots' => 'index,follow',
    ],
    '/about' => [
        'meta_title' => 'About Shree Ji Gas Industries | 30+ Years of Medical Gas Excellence',
        'meta_desc' => 'Discover our heritage, PESO-compliant filling facilities, stringent quality testing, and dedicated biomedical gas engineering team in Agra.',
        'focus_keyword' => 'about Shree Ji Gas, medical gas plant Agra, oxygen supplier history, PESO gas plant',
        'og_title' => 'About Shree Ji Gas Industries · 30+ Years of Trust & Care',
        'og_desc' => 'From clinical oxygen manifolds to high-purity industrial supply, learn how Shree Ji Gas powers healthcare across Uttar Pradesh.',
        'og_image' => 'assets/images/hospital.jpg',
        'canonical_url' => SITE_URL . '/about',
        'robots' => 'index,follow',
    ],
    '/contact' => [
        'meta_title' => 'Contact Us & Emergency Oxygen Dispatch | Shree Ji Gas Industries',
        'meta_desc' => '24/7 priority emergency medical oxygen dispatch & direct procurement desks. Call +91 94122 56214 or connect with our engineering team in Agra.',
        'focus_keyword' => 'contact Shree Ji Gas, emergency oxygen Agra, hospital oxygen helpline, medical gas procurement',
        'og_title' => 'Contact Shree Ji Gas · 24/7 Priority Emergency Dispatch',
        'og_desc' => 'Direct access to biomedical engineers and cylinder dispatch officers for urgent hospital refills and bulk industrial orders.',
        'og_image' => 'assets/images/oxygen.webp',
        'canonical_url' => SITE_URL . '/contact',
        'robots' => 'index,follow',
    ],
    '/products' => [
        'meta_title' => 'Our Product Range | Medical & Industrial Gas Equipment Catalogue',
        'meta_desc' => 'Explore certified medical oxygen cylinders, B-type/D-type cylinders, gas regulators, flowmeters, and manifold pipeline systems in Agra.',
        'focus_keyword' => 'medical oxygen cylinders, gas equipment catalogue, oxygen regulator Agra, hospital gas manifold',
        'og_title' => 'Our Product Range · Essential Medical & Gas Equipment',
        'og_desc' => 'High-pressure certified cylinders, testing kits, regulators, and clinical manifolds built to perform for life-saving care.',
        'og_image' => 'assets/images/oxygen.webp',
        'canonical_url' => SITE_URL . '/products',
        'robots' => 'index,follow',
    ],
    '/use-cases' => [
        'meta_title' => 'Care Settings & Industrial Applications | Shree Ji Gas Industries',
        'meta_desc' => 'Tailored medical and industrial gas delivery configurations for intensive care units, emergency trauma, dental clinics, and industrial workshops.',
        'focus_keyword' => 'ICU oxygen supply, care settings, dental gas delivery, emergency medical gas, industrial gas uses',
        'og_title' => 'Care Settings · Specialized Gas Solutions for Every Context',
        'og_desc' => 'From bedside oxygen therapy to critical surgery wards, see how our gas systems adapt to specialized clinical environments.',
        'og_image' => 'assets/images/hospital.jpg',
        'canonical_url' => SITE_URL . '/use-cases',
        'robots' => 'index,follow',
    ],
    '/case-studies' => [
        'meta_title' => 'Clinical Case Studies & Hospital Installations | Shree Ji Gas',
        'meta_desc' => 'Real-world deployment records showing how Shree Ji Gas delivered continuous oxygen reliability and manifold modernization for UP hospitals.',
        'focus_keyword' => 'medical gas case studies, hospital oxygen setup, manifold installation case study Agra',
        'og_title' => 'Case Studies · Mission-Critical Gas Engineering in Practice',
        'og_desc' => 'Explore how leading hospitals achieved 100% uptime and regulatory compliance with Shree Ji Gas equipment and delivery.',
        'og_image' => 'assets/images/hospital.jpg',
        'canonical_url' => SITE_URL . '/case-studies',
        'robots' => 'index,follow',
    ],
    '/blog' => [
        'meta_title' => 'The Care Journal | Medical Gas Safety & Industry Insights',
        'meta_desc' => 'Expert guides on PESO cylinder safety, 5-year hydrostatic testing, medical oxygen purity standards, and manifold management protocols.',
        'focus_keyword' => 'medical gas safety blog, PESO cylinder rules, oxygen purity standards, medical gas news Agra',
        'og_title' => 'The Care Journal · Medical Gas Safety, Technology & Compliance',
        'og_desc' => 'Practical clinical insights, statutory standards, and safe handling guidelines for medical oxygen and compressed gas systems.',
        'og_image' => 'assets/images/factory.jpg',
        'canonical_url' => SITE_URL . '/blog',
        'robots' => 'index,follow',
    ],
    '/privacy' => [
        'meta_title' => 'Privacy Policy | Shree Ji Gas Industries (DPDP Act 2023)',
        'meta_desc' => 'Our comprehensive data protection policy detailing procurement data handling, encryption, and statutory consent in compliance with the DPDP Act 2023.',
        'focus_keyword' => 'privacy policy, DPDP Act 2023, data protection Shree Ji Gas',
        'og_title' => 'Privacy Policy · Shree Ji Gas Industries',
        'og_desc' => 'Transparency, explicit consent, and security in handling customer and healthcare partner information.',
        'og_image' => 'assets/images/hospital.jpg',
        'canonical_url' => SITE_URL . '/privacy',
        'robots' => 'index,follow',
    ],
    '/terms' => [
        'meta_title' => 'Terms & Conditions of Gas Supply | Shree Ji Gas Industries',
        'meta_desc' => 'Standard terms governing cylinder rotation, refundable security deposits, tare-weight verification, and institutional supply agreements in Agra.',
        'focus_keyword' => 'terms of supply, cylinder deposit rules, medical gas agreement Agra, Shree Ji Gas terms',
        'og_title' => 'Terms & Conditions of Gas Supply & Services',
        'og_desc' => 'Operating terms governing high-pressure cylinder supply, safety responsibilities, and commercial agreements.',
        'og_image' => 'assets/images/hospital.jpg',
        'canonical_url' => SITE_URL . '/terms',
        'robots' => 'index,follow',
    ],
    '/compliance' => [
        'meta_title' => 'Safety & Medical Gas Regulatory Compliance | Shree Ji Gas',
        'meta_desc' => 'Statutory compliance details covering PESO Gas Cylinders Rules 2016, 5-year hydro-testing certifications, and Indian Pharmacopoeia purity standards.',
        'focus_keyword' => 'PESO compliance, gas cylinder hydro testing, Indian Pharmacopoeia oxygen, cylinder safety standards',
        'og_title' => 'Safety & Regulatory Compliance · Certified Excellence',
        'og_desc' => 'Rigorous PESO standards, digital hydro-testing logs, and 99.5% medical oxygen purity validation.',
        'og_image' => 'assets/images/factory.jpg',
        'canonical_url' => SITE_URL . '/compliance',
        'robots' => 'index,follow',
    ],
    '/shipping-policy' => [
        'meta_title' => 'Emergency Delivery & Cylinder Transport Policy | Shree Ji Gas',
        'meta_desc' => 'Emergency 2-hour hospital SLA, hazmat transport protocols, cylinder deposit guidelines, and empty cylinder return procedures in Agra & NCR.',
        'focus_keyword' => 'cylinder delivery policy, emergency oxygen delivery Agra, safe gas transport, cylinder deposit refund',
        'og_title' => 'Emergency Delivery & Cylinder Transport Policy',
        'og_desc' => 'Rapid clinical response, certified transport vehicles, and transparent cylinder security deposit management.',
        'og_image' => 'assets/images/hospital.jpg',
        'canonical_url' => SITE_URL . '/shipping-policy',
        'robots' => 'index,follow',
    ],
];

$stmtSelect = $pdo->prepare('SELECT id FROM seo_meta WHERE page_key = ?');
$stmtInsert = $pdo->prepare('INSERT INTO seo_meta (page_key, meta_title, meta_desc, focus_keyword, og_title, og_desc, og_image, canonical_url, robots) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
$stmtUpdate = $pdo->prepare('UPDATE seo_meta SET meta_title = ?, meta_desc = ?, focus_keyword = ?, og_title = ?, og_desc = ?, og_image = ?, canonical_url = ?, robots = ? WHERE id = ?');

foreach ($corePages as $key => $data) {
    $stmtSelect->execute([$key]);
    $existingId = $stmtSelect->fetchColumn();
    if ($existingId) {
        $stmtUpdate->execute([
            $data['meta_title'],
            $data['meta_desc'],
            $data['focus_keyword'],
            $data['og_title'],
            $data['og_desc'],
            $data['og_image'],
            $data['canonical_url'],
            $data['robots'],
            (int)$existingId
        ]);
        echo "Updated SEO for: {$key} (ID: {$existingId})\n";
    } else {
        $stmtInsert->execute([
            $key,
            $data['meta_title'],
            $data['meta_desc'],
            $data['focus_keyword'],
            $data['og_title'],
            $data['og_desc'],
            $data['og_image'],
            $data['canonical_url'],
            $data['robots']
        ]);
        $newId = $pdo->lastInsertId();
        echo "Inserted SEO for: {$key} (ID: {$newId})\n";
    }
}

echo "Done! All 11 Core Website Pages seeded into seo_meta.\n";
