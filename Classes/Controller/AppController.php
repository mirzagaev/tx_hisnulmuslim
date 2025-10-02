<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Webzadev\Hisnulmuslim\Domain\Repository\CategoryRepository;
use Webzadev\Hisnulmuslim\Domain\Model\Chapter;
use Webzadev\Hisnulmuslim\Domain\Repository\ChapterRepository;

class AppController extends ActionController
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    public function injectCategoryRepository(CategoryRepository $categoryRepository): void
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @var ChapterRepository
     */
    private $chapterRepository;

    /**
     * Inject the chapterRepository repository
     *
     * @param \Webzadev\Hisnulmuslim\Domain\Repository\ChapterRepository $chapterRepository
     */
    public function injectChapterRepository(ChapterRepository $chapterRepository)
    {
        $this->chapterRepository = $chapterRepository;
    }

    /**
     * list Action
     *
     * @return string
     */
    public function listAction(int $categoryUid = 1): ResponseInterface
    {
        // Alle Top-Kategorien holen
        $topCategories = $this->categoryRepository->findTopLevel();

        // Standard: UID 1, wenn kein Argument
        $topCategory = $this->categoryRepository->findByUid($categoryUid);

        // $categoriesWithChildrenAndChapters = [];

        if ($topCategory) {
            $subcategoriesData = [];
            $subcategories = $this->categoryRepository->findByParent($topCategory->getUid());

            foreach ($subcategories as $sub) {
                $subcategoriesData[] = [
                    'subcategory' => $sub,
                    'chapters' => $this->chapterRepository->findByCategory($sub->getUid())
                ];
            }

            // $categoriesWithChildrenAndChapters[] = [
            //     'category' => $topCategory,
            //     'subcategories' => $subcategoriesData
            // ];
        }

        $this->view->assign('topCategories', $topCategories);
        $this->view->assign('category', $topCategory);
        $this->view->assign('subcategories', $subcategoriesData);
        $this->view->assign('activeCategoryUid', $topCategory?->getUid());

        return $this->htmlResponse();
    }

    /**
     * bycategory Action
     *
     * @return string
     */
    public function bycategoryAction(): ResponseInterface
    {
        $topCategories = $this->categoryRepository->findTopLevel();
        $categoriesWithChildrenAndChapters = [];

        foreach ($topCategories as $topCategory) {
            $subcategoriesData = [];
            $subcategories = $this->categoryRepository->findByParent($topCategory->getUid());

            foreach ($subcategories as $sub) {
                $subcategoriesData[] = [
                    'subcategory' => $sub,
                    'chapters' => $this->chapterRepository->findByCategory($sub->getUid())
                ];
            }

            $categoriesWithChildrenAndChapters[] = [
                'category' => $topCategory,
                'subcategories' => $subcategoriesData
            ];
        }

        $this->view->assign('categories', $categoriesWithChildrenAndChapters);
        return $this->htmlResponse();
    }

    /**
     * Show action
     *
     * @param Chapter $chapter The chapter to be shown
     * @return string The rendered HTML string
     */
    public function showAction(Chapter $chapter): ResponseInterface
    {
        $this->view->assign('chapter', $chapter);
        return $this->htmlResponse();
    }
}