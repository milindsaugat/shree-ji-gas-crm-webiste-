<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <?php require ROOT.'/views/partials/seo-head.php'; ?>
    <?php require ROOT.'/views/partials/tracking-head.php'; ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= asset('images/favicon.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= asset('images/favicon-192.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= asset('images/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/svg+xml" href="<?= asset('images/logo.svg') ?>">
    <link href="<?= asset('vendor/fonts/fonts.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?= asset('vendor/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/remixicon.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/swiper-bundle.min.css') ?>">
    <link rel="stylesheet" href="<?= asset('vendor/glightbox.min.css') ?>">
    <?php if(($pageKey??'')==='/'): ?><link rel="stylesheet" href="<?= asset('vendor/aos.css') ?>"><?php endif ?>
    <link rel="stylesheet" href="<?= asset('css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/refinements.css') ?>">
    <link rel="stylesheet" href="<?= asset('css/storytelling.css') ?>">
    <script defer src="<?= asset('vendor/bootstrap.bundle.min.js') ?>"></script>
    <script defer src="<?= asset('vendor/alpine.min.js') ?>"></script>
    <script defer src="<?= asset('vendor/swiper-bundle.min.js') ?>"></script>
    <script defer src="<?= asset('vendor/glightbox.min.js') ?>"></script>
    <?php if(($pageKey??'')==='/'): ?><script defer src="<?= asset('vendor/gsap.min.js') ?>"></script><script defer src="<?= asset('vendor/ScrollTrigger.min.js') ?>"></script><script defer src="<?= asset('vendor/aos.js') ?>"></script><script defer src="<?= asset('vendor/countUp.umd.js') ?>"></script><?php endif ?>
    <script defer src="<?= asset('js/main.js') ?>"></script>
</head>
<body>
    <?php require ROOT.'/views/partials/tracking-body.php'; ?>
    <a class="skip-link" href="#main">Skip to content</a>
    <?php require ROOT.'/views/partials/header.php'; ?>
    <?php if(isset($_SESSION['flash'])): $flash=$_SESSION['flash']; unset($_SESSION['flash']); ?><div class="container flash flash--<?= e($flash['type']) ?>" role="status"><?= e($flash['message']) ?></div><?php endif ?>
    <main id="main"><?= $content ?></main>
    <?php require ROOT.'/views/partials/footer.php'; ?>
    <a class="whatsapp-float" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><?= icon('whatsapp-line') ?><span>Let’s talk</span></a>
    <div class="modal fade" id="enquiryModal" tabindex="-1" aria-labelledby="enquiryTitle" aria-hidden="true"><div class="modal-dialog modal-dialog-centered modal-lg"><div class="modal-content"><div class="modal-header"><div><span class="eyebrow">WE’RE HERE TO HELP</span><h2 id="enquiryTitle">Tell us what you need.</h2></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div><div class="modal-body"><?php $formId='modal'; require ROOT.'/views/partials/enquiry-form.php'; ?></div></div></div>
    <?php require ROOT.'/views/partials/tracking-footer.php'; ?>
</body>
</html>
