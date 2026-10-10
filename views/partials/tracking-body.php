<?php
// Public Front-End Google Tag Manager Body (NoScript) Dispatcher
$gtmBody = trim((string)setting('gtm_body'));
$gtmId = trim((string)setting('gtm_container_id'));

if (!empty($gtmBody)) {
    echo $gtmBody . "\n";
} elseif (!empty($gtmId) && preg_match('/^GTM-[A-Z0-9]+$/i', $gtmId)) {
    $cleanGtmId = htmlspecialchars($gtmId, ENT_QUOTES, 'UTF-8');
?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= $cleanGtmId ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
}
