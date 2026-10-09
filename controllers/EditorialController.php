<?php
final class EditorialController
{
    public function dispatch(string $type, ?string $slug): void {
        $table = ['blog'=>'blog_posts','case-studies'=>'case_studies','use-cases'=>'use_cases'][$type];
        $model = new ContentModel($table);
        $titles=['blog'=>'The care journal.','case-studies'=>'Solutions in practice.','use-cases'=>'Care has many settings.'];
        if ($slug) {
            $item = $model->bySlug($slug);
            if (!$item) { notFound(); }
            $ids = json_decode($item['related_products'] ?? $item['products_used'] ?? '[]',true) ?: [];
            $products = array_filter((new ContentModel('products'))->all(true),fn($p)=>in_array($p['id'],$ids));
            view('editorial/detail',['title'=>$item['title'],'description'=>strip_tags($item['excerpt']??$item['application_context']??$item['challenge']??''),'item'=>$item,'type'=>$type,'products'=>$products,'related'=>array_slice(array_values(array_filter($model->all(true),fn($i)=>$i['id']!==$item['id'])),0,3),'pageKey'=>'/'.$type.'/'.$slug,'seoImage'=>$item['thumbnail']]);
        } else {
            $q=trim((string)($_GET['q']??'')); $cat=(int)($_GET['category']??0); $tag=trim((string)($_GET['tag']??''));
            $allItems=$model->all(true);
            $items=array_values(array_filter($allItems,fn($i)=>($q===''||stripos($i['title'].' '.strip_tags($i['excerpt']??$i['application_context']??$i['challenge']??''),$q)!==false)&&(!$cat||(int)($i['cat_id']??0)===$cat)&&(!$tag||in_array(mb_strtolower($tag),array_map(fn($t)=>mb_strtolower(trim($t)),explode(',',$i['tags']??'')),true))));
            $blogCategories=[];$recentPosts=[];$tagCloud=[];
            if($type==='blog'){
                $blogCategories=(new ContentModel('blog_categories'))->all();
                foreach($blogCategories as &$category)$category['post_count']=count(array_filter($allItems,fn($i)=>(int)$i['cat_id']===(int)$category['id']));unset($category);
                usort($allItems,fn($a,$b)=>strcmp($b['published_at']??'',$a['published_at']??''));$recentPosts=array_slice($allItems,0,3);
                foreach($allItems as $post)foreach(explode(',',$post['tags']??'') as $label){$label=trim($label);if($label!=='')$tagCloud[$label]=($tagCloud[$label]??0)+1;}uksort($tagCloud,'strnatcasecmp');
            }
            $pages=max(1,(int)ceil(count($items)/9)); $page=max(1,min($pages,(int)($_GET['page']??1)));
            view('editorial/index',['title'=>$titles[$type],'items'=>array_slice($items,($page-1)*9,9),'type'=>$type,'pageKey'=>'/'.$type,'query'=>$q,'pages'=>$pages,'page'=>$page,'categoryId'=>$cat,'tag'=>$tag,'blogCategories'=>$blogCategories,'recentPosts'=>$recentPosts,'tagCloud'=>$tagCloud,'resultCount'=>count($items)]);
        }
    }
}
