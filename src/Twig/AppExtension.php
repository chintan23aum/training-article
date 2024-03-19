<?php

namespace App\Twig;

use App\Service\CategoryService;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $categoryService;
    private $router;
    public function __construct(CategoryService $categoryService,UrlGeneratorInterface $router)
    {
        $this->categoryService = $categoryService;
        $this->router = $router;
    }
    public function getFilters(): array
    {
        return [
            new TwigFilter('html_entity_decode', [$this, 'htmlEntityDecode']),
            new TwigFilter('substr', [$this, 'substr']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('fullMenu', [$this, 'getFullMenu']),
            new TwigFunction('leftMenuNested', [$this, 'leftMenuNested']),
            new TwigFunction('getAllCategory', [$this, 'getAllCategory']),
        ];
    }

    public function htmlEntityDecode(string $value): string
    {
        return html_entity_decode($value);
    }

    public function substr(string $value): string
    {
        return substr($value,0, 60);
    }

    public function getFullMenu(): array
    {
        return $this->categoryService->getAllCategoryArr();
    }

    public function leftMenuNested(int $category_id, array $menu): string
    {
        $html="";
        foreach($menu as $childRow) {
            $html .= '<ul class="collapse" id="inbox'.$category_id.'">';
            if(empty($childRow['child'])){
                $html .='<li class="nav-item">
<a class="nav-link ajaxLoadArticle" href="'.$this->router->generate('app_ajax_article',['id'=>$childRow['id'],'parentIds'=>json_encode($childRow['parent_ids'])]).'">
<span class="media-body">'.$childRow['name'].'</span>
</a>';
            } else {
                $html .='<li class="nav-item">';
                $html .='<a class="nav-link" data-toggle="collapse" href="#inbox'.$childRow['id'].'" role="button" aria-expanded="false" aria-controls="inbox'.$childRow['id'].'">';
                $html .='<i class="material-icons pmd-list-icon pmd-sm">'.$childRow['name'].'</i></a>';
                $html.= $this->leftMenuNested($childRow['id'],$childRow['child']);
            }
            $html.='</ul>';
        }
        return $html;
    }

    public function getAllCategory(): array
    {
        return $this->categoryService->getAllCategory();
    }


}
