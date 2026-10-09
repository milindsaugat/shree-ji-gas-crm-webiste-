<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$brainDir = 'C:\\Users\\milin\\.gemini\\antigravity-ide\\brain\\553b5fc3-818e-43f4-bb50-0163d789dad2';
$targetDir = __DIR__ . '/../assets/images';

$imageCopies = [
    $brainDir . '/dr_rajesh_sharma_1791484553646.jpg' => $targetDir . '/testimonial-dr-rajesh.jpg',
    $brainDir . '/dr_meenakshi_verma_1791484577592.jpg' => $targetDir . '/testimonial-dr-meenakshi.jpg',
    $brainDir . '/er_vikramaditya_singh_1791484594787.jpg' => $targetDir . '/testimonial-er-vikram.jpg',
    $brainDir . '/dr_sanjay_gupta_1791484617284.jpg' => $targetDir . '/testimonial-dr-sanjay.jpg',
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

// Check if avatar column exists in testimonials
$hasAvatar = false;
if (DB_DRIVER === 'mysql') {
    $stmt = $db->query("SHOW COLUMNS FROM testimonials LIKE 'avatar'");
    $hasAvatar = (bool)$stmt->fetch();
} else {
    foreach ($db->query("PRAGMA table_info(testimonials)")->fetchAll() as $col) {
        if (($col['name'] ?? '') === 'avatar') $hasAvatar = true;
    }
}

if (!$hasAvatar) {
    $sql = DB_DRIVER === 'mysql' 
        ? "ALTER TABLE testimonials ADD COLUMN avatar TEXT NULL AFTER organization"
        : "ALTER TABLE testimonials ADD COLUMN avatar TEXT";
    $db->exec($sql);
    echo "Added 'avatar' column to testimonials table.\n";
} else {
    echo "'avatar' column already exists in testimonials.\n";
}

// Update the 4 testimonials with avatar paths
$testimonialAvatars = [
    1 => 'assets/images/testimonial-dr-rajesh.jpg',
    2 => 'assets/images/testimonial-dr-meenakshi.jpg',
    3 => 'assets/images/testimonial-er-vikram.jpg',
    4 => 'assets/images/testimonial-dr-sanjay.jpg',
];

$stmt = $db->prepare("UPDATE testimonials SET avatar = ? WHERE id = ?");
foreach ($testimonialAvatars as $id => $path) {
    $stmt->execute([$path, $id]);
    echo "Updated testimonial #$id avatar to '$path'\n";
}

// Insert into media table
$insertMedia = $db->prepare("INSERT INTO media (filename, original_name, path, image_alt, size, type, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$checkMedia = $db->prepare("SELECT COUNT(*) FROM media WHERE path = ?");

$newMedia = [
    ['testimonial-dr-rajesh.jpg', 'assets/images/testimonial-dr-rajesh.jpg', 'Dr. Rajesh Sharma, Medical Director portrait'],
    ['testimonial-dr-meenakshi.jpg', 'assets/images/testimonial-dr-meenakshi.jpg', 'Dr. Meenakshi Verma, Head of Surgery portrait'],
    ['testimonial-er-vikram.jpg', 'assets/images/testimonial-er-vikram.jpg', 'Er. Vikramaditya Singh, Biomedical Engineering In-charge portrait'],
    ['testimonial-dr-sanjay.jpg', 'assets/images/testimonial-dr-sanjay.jpg', 'Dr. Sanjay Gupta, Senior Consultant portrait'],
];

foreach ($newMedia as [$fn, $path, $alt]) {
    $checkMedia->execute([$path]);
    if ((int)$checkMedia->fetchColumn() === 0) {
        $fullPath = __DIR__ . '/../' . $path;
        $size = file_exists($fullPath) ? filesize($fullPath) : 102400;
        $insertMedia->execute([$fn, $fn, $path, $alt, $size, 'image/jpeg']);
        echo "Inserted media: $fn\n";
    }
}

echo "Testimonials setup completed successfully!\n";
