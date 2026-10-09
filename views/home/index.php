<?php foreach($sections as $section): if($section['section_key']==='footer') continue; $s=$section['fields']; require ROOT.'/views/home/section.php'; endforeach ?>
