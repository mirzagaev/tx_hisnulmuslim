<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Webzadev\Hisnulmuslim\Domain\Repository\CategoryRepository;
use Webzadev\Hisnulmuslim\Domain\Repository\ChapterRepository;

class ApiController extends ActionController
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
    public function listAction(): ResponseInterface
    {
        // $categories = $this->categoryRepository->findTopLevel();
        $chapters = $this->chapterRepository->findAll();

        $result = [];

        // foreach ($categories as $category) {
        //     $subcategoriesData = [];
        //     $subcategories = $this->categoryRepository->findByParent($category->getUid());
        //     foreach ($subcategories as $sub) {
        //         $chaptersData = [];
        //         $chapters = $this->chapterRepository->findByCategory($sub->getUid());
        //         foreach ($chapters as $chapter) {
        //             $chaptersData[] = [
        //                 'chapterId' => $chapter->getChapterId(),
        //                 'title' => $chapter->getTitle(),
        //                 'titleAr' => $chapter->getTitleAr(),
        //                 'dua' => []
        //             ];
        //         }
        //         $subcategoriesData[] = [
        //             'uid' => $sub->getUid(),
        //             'title' => $sub->getTitle(),
        //             'chapters' => $chaptersData
        //         ];
        //     }
        //     $result[] = [
        //         'uid' => $category->getUid(),
        //         'title' => $category->getTitle(),
        //         'description' => $category->getDescription(),
        //         'subcategories' => $subcategoriesData
        //     ];
        // }

        $chaptersData = [];
        foreach ($chapters as $chapter) {
            $chaptersData[] = [
                'chapterId' => $chapter->getChapterId(),
                'title' => $chapter->getTitle(),
                'titleAr' => $chapter->getTitleAr(),
                'dua' => []
            ];
        }
        
        $data = ['categories' => $chaptersData];
        return new JsonResponse($data);
    }
}