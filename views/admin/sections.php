<?php
$sectionIcons = [
    'hero' => 'layout-top-line',
    'stats' => 'bar-chart-box-line',
    'categories' => 'apps-2-line',
    'products' => 'shopping-bag-3-line',
    'why' => 'shield-star-line',
    'process' => 'git-merge-line',
    'guidance' => 'compass-3-line',
    'partnership' => 'hand-heart-line',
    'case_studies' => 'article-line',
    'use_cases' => 'hospital-line',
    'blog' => 'book-read-line',
    'testimonials' => 'chat-quote-line',
    'faq' => 'question-answer-line',
    'logos' => 'gallery-line',
    'contact' => 'map-pin-2-line',
    'cta' => 'megaphone-line',
    'footer' => 'layout-bottom-line',
];
$dynamicTags = [
    'case_studies' => ['label' => 'Dynamic Case Studies', 'count' => $counts['case_studies'] ?? 0],
    'use_cases' => ['label' => 'Dynamic Use Cases', 'count' => $counts['use_cases'] ?? 0],
    'blog' => ['label' => 'Dynamic Journal Posts', 'count' => $counts['blog_posts'] ?? 0],
    'products' => ['label' => 'Dynamic Products', 'count' => $counts['products'] ?? 0],
    'categories' => ['label' => 'Dynamic Categories', 'count' => $counts['categories'] ?? 0],
    'testimonials' => ['label' => 'Dynamic Testimonials', 'count' => $counts['testimonials'] ?? 0],
    'logos' => ['label' => 'Dynamic Partner Logos', 'count' => $counts['client_logos'] ?? 0],
];
?>

<div class="admin-page-heading">
    <div>
        <span class="eyebrow">HOMEPAGE ARCHITECTURE</span>
        <h1>Homepage Sections</h1>
        <p>Reorder sections with drag and drop, or edit layout, copy, media and dynamic collections.</p>
    </div>
    <div class="heading-actions">
        <a class="btn btn-outline" href="<?= url() ?>" target="_blank" rel="noopener">
            View live website <?= icon('arrow-right-up-line') ?>
        </a>
    </div>
</div>

<div class="admin-panel sections-control-panel">
    <div class="admin-table-toolbar">
        <div class="search-field">
            <?= icon('search-line') ?>
            <input type="search" class="admin-table-search sections-search-input" placeholder="Search section by name or tag…">
        </div>
        <div class="sections-summary-strip">
            <span class="summary-pill"><strong><?= count($sections) ?></strong> total sections</span>
            <span class="summary-pill summary-pill--live"><strong><?= count(array_filter($sections, fn($s)=>$s['is_active'])) ?></strong> live</span>
            <span class="summary-pill summary-pill--dynamic"><strong>7</strong> dynamic collections</span>
        </div>
    </div>

    <div class="sections-list" data-order-url="<?= url('admin/sections') ?>">
        <?php foreach($sections as $index=>$section): 
            $sKey = $section['section_key'];
            if ($sKey === 'partnership' || $sKey === 'contact') continue;
            $sIcon = $sectionIcons[$sKey] ?? 'layout-grid-line';
            $bg = $section['fields']['background'] ?? '';
            $isDyn = isset($dynamicTags[$sKey]);
        ?>
            <div class="section-list-row <?= $sKey==='footer'?'section-list-row--fixed':'' ?>" draggable="<?= $sKey==='footer'?'false':'true' ?>" data-key="<?= e($sKey) ?>">
                <span class="drag-handle" aria-hidden="true" title="Drag to reorder"><?= icon('draggable') ?></span>
                
                <span class="section-number"><?= str_pad((string)($index+1),2,'0',STR_PAD_LEFT) ?></span>
                
                <div class="section-icon-badge">
                    <?= icon($sIcon) ?>
                </div>

                <div class="section-info">
                    <div class="section-info__title-row">
                        <h2><?= e($section['title']) ?></h2>
                        <?php if($isDyn): ?>
                            <span class="dynamic-badge-pill" title="Pulls records dynamically from database">
                                <?= icon('sparkling-2-line') ?> <?= e($dynamicTags[$sKey]['label']) ?> (<?= (int)$dynamicTags[$sKey]['count'] ?>)
                            </span>
                        <?php endif ?>
                    </div>
                    <p><?= e($section['fields']['heading'] ?? ($section['fields']['eyebrow'] ?? 'Manage copy and repeatable items')) ?></p>
                </div>

                <?php if($bg): ?>
                    <span class="bg-tag" style="background-color: <?= e($bg) ?>;" title="Background: <?= e($bg) ?>"></span>
                <?php endif ?>

                <span class="status-badge <?= $section['is_active']?'':'status-badge--muted' ?>">
                    <?= $section['is_active']?'Live':'Hidden' ?>
                </span>

                <div class="reorder-buttons">
                    <button type="button" data-move="up" aria-label="Move <?= e($section['title']) ?> up" <?= $index===0?'disabled':'' ?>><?= icon('arrow-up-s-line') ?></button>
                    <button type="button" data-move="down" aria-label="Move <?= e($section['title']) ?> down" <?= $index===count($sections)-1||$sKey==='footer'?'disabled':'' ?>><?= icon('arrow-down-s-line') ?></button>
                </div>

                <a class="btn btn-outline btn-sm section-edit-btn" href="<?= url('admin/sections/'.$sKey) ?>">
                    Edit <?= icon('edit-line') ?>
                </a>
            </div>
        <?php endforeach ?>
    </div>

    <div class="admin-search-empty" hidden>
        <?= icon('search-line') ?>
        <p>No homepage sections match your search.</p>
    </div>
</div>

<p class="admin-help">
    <?= icon('information-line') ?> Header and navigation remain fixed at the top of every page, and footer remains at the bottom. Dynamic sections (case studies, use cases, blog articles, featured products, categories, testimonials, client logos) render directly from active database records without mock data.
</p>
