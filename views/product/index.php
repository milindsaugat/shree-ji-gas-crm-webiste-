<?php
$cEyebrow = $catEyebrow ?? (setting('catalogue_eyebrow') ?: 'EQUIPMENT FOR BETTER CARE');
$cTitle = $catTitle ?? ($category ? $title : (setting('catalogue_title') ?: $title));
$cDesc = $catDesc ?? (setting('catalogue_desc') ?: 'Explore the essentials. Find your fit. Let’s discuss the details.');
$cGuideTitle = $catGuideTitle ?? (setting('catalogue_guidance_title') ?: 'A little guidance?');
$cGuideDesc = $catGuideDesc ?? (setting('catalogue_guidance_desc') ?: 'Our team can help you explore the right options.');
$cGuideBtn = $catGuideBtn ?? (setting('catalogue_guidance_btn') ?: 'Talk to us');
?>
<div class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= url() ?>"><?= icon('home-4-line') ?> Home</a>
            <span>/</span>
            <span>Products<?= $category ? ' / '.e($category['name']) : '' ?></span>
        </nav>
        <span class="eyebrow" data-preview="catalogue_eyebrow"><?= e($cEyebrow) ?></span>
        <h1><span data-preview="catalogue_title"><?= e($cTitle) ?></span><span>.</span></h1>
        <p data-preview="catalogue_desc"><?= e($cDesc) ?></p>
    </div>
</div>

<section class="section">
    <div class="container catalogue-layout" id="productCatalogue">
        <aside class="filter-panel">
            <div class="filter-panel__title">
                <h2>Find your equipment</h2>
                <button type="button" class="filter-clear" id="clearFilters">Reset</button>
            </div>
            
            <label for="productSearch">Search products</label>
            <div class="search-field">
                <?= icon('search-line') ?>
                <input type="search" id="productSearch" placeholder="What are you looking for?" value="<?= e($_GET['q'] ?? '') ?>">
            </div>

            <fieldset>
                <legend>Product categories</legend>
                <?php foreach($categories as $cat): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" class="category-filter" value="<?= (int)$cat['id'] ?>" <?= $category && $category['id'] === $cat['id'] ? 'checked' : '' ?>>
                        <span><?= e($cat['name']) ?></span>
                    </label>
                <?php endforeach ?>
            </fieldset>

            <fieldset>
                <legend>Application</legend>
                <select id="tagFilter" aria-label="Filter by application">
                    <option value="">All applications</option>
                    <?php 
                    $tags = []; 
                    foreach($products as $p) {
                        foreach(explode(',', $p['tags'] ?? '') as $t) {
                            $t = trim($t);
                            if ($t !== '') $tags[$t] = true;
                        }
                    }
                    foreach(array_keys($tags) as $tag): 
                    ?>
                        <option value="<?= e(strtolower($tag)) ?>"><?= e($tag) ?></option>
                    <?php endforeach ?>
                </select>
            </fieldset>

            <div class="filter-help">
                <?= icon('customer-service-2-line') ?>
                <h3 data-preview="catalogue_guidance_title"><?= e($cGuideTitle) ?></h3>
                <p data-preview="catalogue_guidance_desc"><?= e($cGuideDesc) ?></p>
                <a class="text-link" href="<?= url('contact') ?>" data-preview="catalogue_guidance_btn"><?= e($cGuideBtn) ?> <?= icon('arrow-right-line') ?></a>
            </div>
        </aside>

        <div class="catalogue-results">
            <!-- Quick Category Pill Filter Navigation -->
            <div class="catalogue-category-pills" aria-label="Category shortcuts">
                <a href="<?= url('products') ?>" class="cat-pill <?= !$category ? 'is-active' : '' ?>">
                    <span>All Products</span>
                </a>
                <?php foreach($categories as $cat): ?>
                    <a href="<?= url('categories/'.$cat['slug']) ?>" class="cat-pill <?= ($category && $category['id'] === $cat['id']) ? 'is-active' : '' ?>">
                        <?= icon($cat['icon'] ?? 'apps-2-line') ?>
                        <span><?= e($cat['name']) ?></span>
                    </a>
                <?php endforeach ?>
            </div>

            <div class="results-bar">
                <span><strong id="resultCount"><?= count($products) ?></strong> products to explore</span>
                <label>Sort by 
                    <select id="productSort">
                        <option value="default">Recommended</option>
                        <option value="az">Name: A–Z</option>
                        <option value="za">Name: Z–A</option>
                    </select>
                </label>
            </div>

            <div class="product-grid" id="productGrid">
                <?php foreach($products as $product) productCard($product); ?>
            </div>

            <div class="empty-state" id="noProducts" hidden>
                <?= icon('search-line') ?>
                <h2>No matching equipment</h2>
                <p>Try a different search or reset your filters.</p>
                <button class="btn btn-outline" type="button" id="resetEmpty">Reset filters</button>
            </div>

            <p class="product-footnote">
                <?= icon('information-line') ?> Illustrative product images. Models, specifications and availability are confirmed on enquiry.
            </p>
        </div>
    </div>
</section>
