<?php
// Public Front-End Google Analytics & Tag Manager Head Dispatcher
$gtmHead = trim((string)setting('gtm_head'));
$gtmId = trim((string)setting('gtm_container_id'));
$gaId = trim((string)setting('google_analytics_id'));

// 1. Google Tag Manager Head Script
if (!empty($gtmHead)) {
    echo $gtmHead . "\n";
} elseif (!empty($gtmId) && preg_match('/^GTM-[A-Z0-9]+$/i', $gtmId)) {
    $cleanGtmId = htmlspecialchars($gtmId, ENT_QUOTES, 'UTF-8');
?>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?= $cleanGtmId ?>');</script>
<!-- End Google Tag Manager -->
<?php
}

// 2. Google Analytics (GA4) Tracking Snippet
if (!empty($gaId) && (!empty($gtmHead) ? !str_contains($gtmHead, $gaId) : true)) {
    if (preg_match('/^(G-[A-Z0-9]+|UA-[0-9]+-[0-9]+)$/i', $gaId)) {
        $cleanGaId = htmlspecialchars($gaId, ENT_QUOTES, 'UTF-8');
?>
<!-- Google Analytics (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $cleanGaId ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= $cleanGaId ?>');
</script>
<!-- End Google Analytics -->
<?php
    }
}
