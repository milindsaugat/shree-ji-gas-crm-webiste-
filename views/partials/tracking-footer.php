<?php
// Public Front-End Footer Custom Scripts Dispatcher
$footerScripts = trim((string)setting('footer_scripts'));

if (!empty($footerScripts)) {
    echo $footerScripts . "\n";
}
