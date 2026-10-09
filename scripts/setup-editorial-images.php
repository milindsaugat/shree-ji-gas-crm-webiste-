<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$brainDir = 'C:\\Users\\milin\\.gemini\\antigravity-ide\\brain\\553b5fc3-818e-43f4-bb50-0163d789dad2';
$targetDir = __DIR__ . '/../assets/images';

$imageCopies = [
    $brainDir . '/hospital_oxygen_manifold_1791483364005.jpg' => $targetDir . '/manifold.jpg',
    $brainDir . '/rural_oxygen_deployment_1791483386560.jpg' => $targetDir . '/rural-clinic.jpg',
    $brainDir . '/surgical_centre_instruments_1791483404773.jpg' => $targetDir . '/surgical-theatre.jpg',
    $brainDir . '/supervised_home_oxygen_1791483423460.jpg' => $targetDir . '/homecare-oxygen.jpg',
];

foreach ($imageCopies as $src => $dst) {
    if (file_exists($src)) {
        copy($src, $dst);
        echo "Copied " . basename($src) . " -> " . basename($dst) . " (" . filesize($dst) . " bytes)\n";
    } else {
        echo "WARNING: Source does not exist: $src\n";
    }
}

$db = Database::get();

// 1. Update blog posts
$blogUpdates = [
    1 => ['thumb' => 'assets/images/hospital.jpg', 'alt' => 'Healthcare facility equipment planning and clinical consultation'],
    2 => ['thumb' => 'assets/images/surgical-theatre.jpg', 'alt' => 'Clinical workspace and modern surgical suite equipment'],
    3 => ['thumb' => 'assets/images/homecare-oxygen.jpg', 'alt' => 'Medical oxygen equipment consultation and bedside setup'],
    4 => ['thumb' => 'assets/images/manifold.jpg', 'alt' => 'Hospital medical oxygen manifold installation and pipeline inspection'],
    6 => ['thumb' => 'assets/images/manifold.jpg', 'alt' => 'Certified oxygen manifold safety protocol and dual bank regulator setup'],
];

$stmt = $db->prepare("UPDATE blog_posts SET thumbnail = ?, image_alt = ? WHERE id = ?");
foreach ($blogUpdates as $id => $data) {
    $stmt->execute([$data['thumb'], $data['alt'], $id]);
    echo "Updated blog_posts #$id -> {$data['thumb']}\n";
}

// 2. Update case studies
$caseUpdates = [
    1 => ['thumb' => 'assets/images/manifold.jpg', 'alt' => 'Central oxygen manifold and pipeline installation at Agra multispecialty hospital'],
    2 => ['thumb' => 'assets/images/surgical-theatre.jpg', 'alt' => 'Surgical instrument upgrade and operating theatre suite at District Health Centre'],
    3 => ['thumb' => 'assets/images/rural-clinic.jpg', 'alt' => 'Emergency portable oxygen cylinder setup at rural clinic network'],
];

$stmt = $db->prepare("UPDATE case_studies SET thumbnail = ?, image_alt = ? WHERE id = ?");
foreach ($caseUpdates as $id => $data) {
    $stmt->execute([$data['thumb'], $data['alt'], $id]);
    echo "Updated case_studies #$id -> {$data['thumb']}\n";
}

// 3. Update use cases
$useUpdates = [
    1 => ['thumb' => 'assets/images/manifold.jpg', 'alt' => 'Hospital and critical care oxygen manifold supply system'],
    2 => ['thumb' => 'assets/images/surgical-theatre.jpg', 'alt' => 'Operating theatre surgical instruments and clinical gas supply'],
    3 => ['thumb' => 'assets/images/homecare-oxygen.jpg', 'alt' => 'Supervised home care medical oxygen cylinder and respiratory support'],
];

$stmt = $db->prepare("UPDATE use_cases SET thumbnail = ?, image_alt = ? WHERE id = ?");
foreach ($useUpdates as $id => $data) {
    $stmt->execute([$data['thumb'], $data['alt'], $id]);
    echo "Updated use_cases #$id -> {$data['thumb']}\n";
}

// 4. Populate Media library table so 'Choose from library' has rich options
$mediaFiles = [
    ['manifold.jpg', 'assets/images/manifold.jpg', 'Hospital oxygen manifold installation with dual cylinder banks and pressure regulators'],
    ['rural-clinic.jpg', 'assets/images/rural-clinic.jpg', 'Portable medical oxygen cylinder trolley at rural healthcare clinic'],
    ['surgical-theatre.jpg', 'assets/images/surgical-theatre.jpg', 'Modern surgical operating theatre suite with precision instruments'],
    ['homecare-oxygen.jpg', 'assets/images/homecare-oxygen.jpg', 'Supervised home care medical oxygen cylinder setup with humidifier'],
    ['hospital.jpg', 'assets/images/hospital.jpg', 'Modern healthcare hospital corridor and clinical care facility'],
    ['surgery.jpg', 'assets/images/surgery.jpg', 'Clinical surgical instruments and theatre illumination'],
    ['homecare.jpg', 'assets/images/homecare.jpg', 'Healthcare professional consultation'],
    ['facility.jpg', 'assets/images/facility.jpg', 'Shree Ji Gas Industries Agra depot logistics infrastructure'],
    ['oxygen.webp', 'assets/images/oxygen.webp', 'Medical oxygen cylinder with safety valve'],
    ['regulator.webp', 'assets/images/regulator.webp', 'Medical gas pressure regulator and flowmeter'],
    ['surgical.webp', 'assets/images/surgical.webp', 'Precision stainless steel surgical instrument set'],
    ['equipment.webp', 'assets/images/equipment.webp', 'Medical gas equipment and cylinder accessories catalog visual'],
];

$insertMedia = $db->prepare("INSERT INTO media (filename, original_name, path, image_alt, size, type, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$checkMedia = $db->prepare("SELECT COUNT(*) FROM media WHERE path = ?");

foreach ($mediaFiles as [$fn, $path, $alt]) {
    $checkMedia->execute([$path]);
    if ((int)$checkMedia->fetchColumn() === 0) {
        $fullPath = __DIR__ . '/../' . $path;
        $size = file_exists($fullPath) ? filesize($fullPath) : 102400;
        $mime = str_ends_with($fn, '.webp') ? 'image/webp' : (str_ends_with($fn, '.png') ? 'image/png' : 'image/jpeg');
        $insertMedia->execute([$fn, $fn, $path, $alt, $size, $mime]);
        echo "Inserted media: $fn\n";
    }
}

echo "Image setup and database sync complete!\n";
