<?php
$meta=Seo::meta($pageKey??('/'.trim($GLOBALS['path']??'','/')),$title??'',$description??'',$seoImage??'');
$imageUrl=preg_match('#^https://#',$meta['image'])?$meta['image']:SITE_URL.'/'.ltrim($meta['image'],'/');
?>
<title><?= e($meta['title']) ?></title>
<meta name="description" content="<?= e($meta['description']) ?>">
<?php if(!empty($meta['keywords'])): ?><meta name="keywords" content="<?= e($meta['keywords']) ?>"><?php endif ?>
<meta name="robots" content="<?= e($meta['robots']) ?>">
<link rel="canonical" href="<?= e($meta['canonical']) ?>">
<meta property="og:type" content="<?= isset($item)&&($type??'')==='blog'?'article':'website' ?>"><meta property="og:site_name" content="<?= e(SITE_NAME) ?>"><meta property="og:title" content="<?= e($meta['og_title']) ?>"><meta property="og:description" content="<?= e($meta['og_desc']) ?>"><meta property="og:image" content="<?= e($imageUrl) ?>"><meta property="og:url" content="<?= e($meta['canonical']) ?>"><meta name="twitter:card" content="summary_large_image">
<?php
$schema=['@context'=>'https://schema.org','@type'=>['Organization','LocalBusiness'],'@id'=>SITE_URL.'/#organization','name'=>SITE_NAME,'url'=>SITE_URL,'telephone'=>setting('phone'),'email'=>setting('email'),'address'=>['@type'=>'PostalAddress','streetAddress'=>setting('address'),'addressLocality'=>'Agra','addressRegion'=>'Uttar Pradesh','addressCountry'=>'IN'],'image'=>SITE_URL.'/'.setting('logo')];
$schemas=[$schema];
if(isset($product)) $schemas[]=['@context'=>'https://schema.org','@type'=>'Product','name'=>$product['name'],'description'=>$product['short_desc'],'image'=>$imageUrl,'brand'=>['@type'=>'Brand','name'=>SITE_NAME]];
if(isset($item['title'])&&in_array($type??'',['blog','case-studies'])) $schemas[]=['@context'=>'https://schema.org','@type'=>'Article','headline'=>$item['title'],'image'=>$imageUrl,'author'=>['@type'=>'Organization','name'=>$item['author']??SITE_NAME],'datePublished'=>date(DATE_ATOM,strtotime($item['published_at']??'now'))];
if(($pageKey??'/')!=='/') $schemas[]=['@context'=>'https://schema.org','@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>SITE_URL.'/'],['@type'=>'ListItem','position'=>2,'name'=>$title??'','item'=>$meta['canonical']]]];
foreach($schemas as $schema): ?><script type="application/ld+json"><?= json_encode($schema,JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) ?></script><?php endforeach ?>
