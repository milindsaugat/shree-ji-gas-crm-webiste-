<?php
final class ProductController
{
    public function dispatch(string $path): void {
        $parts = explode('/', $path); $categories = allCategories();
        if ($parts[0] === 'products' && isset($parts[1])) {
            $product = (new ContentModel('products'))->bySlug($parts[1]);
            if (!$product) { notFound(); }
            $category = (new ContentModel('categories'))->find((int)$product['cat_id']);
            $related = array_values(array_filter((new ContentModel('products'))->all(true),fn($p)=>$p['cat_id']===$product['cat_id'] && $p['id']!==$product['id']));
            view('product/detail', ['title'=>$product['name'],'description'=>$product['short_desc'],'product'=>$product,'category'=>$category,'related'=>$related,'pageKey'=>'/'.$path,'seoImage'=>$product['thumbnail']]); return;
        }
        $category = isset($parts[1]) ? (new ContentModel('categories'))->bySlug($parts[1]) : null;
        if (isset($parts[1]) && !$category) { notFound(); }
        $all = (new ContentModel('products'))->all(true);
        $products = $category ? array_values(array_filter($all,fn($p)=>$p['cat_id']==$category['id'])) : $all;
        view('product/index',['title'=>$category['name'] ?? 'Our product range','products'=>$products,'categories'=>$categories,'category'=>$category,'pageKey'=>'/'.$path]);
    }
    public function catalogue(): void {
        $pdf = setting('catalogue_pdf');
        if ($pdf && preg_match('#^assets/uploads/[a-zA-Z0-9._-]+\.pdf$#', $pdf) && is_file(ROOT . '/' . $pdf)) { header('Content-Type: application/pdf'); header('Content-Disposition: attachment; filename="shree-ji-gas-catalogue.pdf"'); readfile(ROOT . '/' . $pdf); exit; }
        view('product/catalogue',['title'=>'Product catalogue','products'=>(new ContentModel('products'))->all(true),'pageKey'=>'/catalogue']);
    }
}
