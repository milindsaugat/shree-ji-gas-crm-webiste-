<?php
final class HomeController
{
    public function index(): void {
        $recent = static function(string $type, int $limit): array {
            $rows=(new ContentModel($type))->all(true);
            usort($rows, static fn($a,$b)=>(int)$b['id']<=>(int)$a['id']);
            return array_slice($rows,0,$limit);
        };
        $sections = Section::all(true);
        $ending = [];
        $sections = array_values(array_filter($sections, static function(array $section) use (&$ending): bool {
            if (in_array($section['section_key'] ?? '', ['contact', 'partnership'], true)) {
                return false;
            }
            if (($section['section_key'] ?? '') === 'cta') {
                $ending['cta'] = $section;
                return false;
            }
            return true;
        }));
        if (isset($ending['cta'])) $sections[] = $ending['cta'];
        view('home/index', ['title'=>'','sections'=>$sections,'categories'=>allCategories(),'products'=>$recent('products',6),'posts'=>$recent('blog_posts',3),'useCases'=>$recent('use_cases',3),'caseStudies'=>$recent('case_studies',4),'pageKey'=>'/']);
    }
}
