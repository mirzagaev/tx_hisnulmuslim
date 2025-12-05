<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use Webzadev\Hisnulmuslim\Domain\Repository\CategoryRepository;
use Webzadev\Hisnulmuslim\Domain\Model\Chapter;
use Webzadev\Hisnulmuslim\Domain\Repository\ChapterRepository;
use Webzadev\Hisnulmuslim\Domain\Repository\DuaItemRepository;

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
     * @var DuaItemRepository
     */
    private $duaItemRepository;

    /**
     * Inject the duaItemRepository repository
     *
     * @param \Webzadev\Hisnulmuslim\Domain\Repository\DuaItemRepository $duaItemRepository
     */
    public function injectDuaItemRepository(DuaItemRepository $duaItemRepository)
    {
        $this->duaItemRepository = $duaItemRepository;
    }

    /**
     * index Action
     *
     * @return string
     */
    public function indexAction(): ResponseInterface
    {
        // Alle Top-Kategorien holen
        $topCategories = $this->categoryRepository->findTopLevel();

        $this->view->assign('topCategories', $topCategories);

        return $this->htmlResponse();
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
    public function showAction(Chapter $chapter): JsonResponse
    {
        // JSON Response vorbereiten
        $jsonData = [
            'success' => true,
            'chapter' => [
                'uid' => $chapter->getUid(),
                'title' => $chapter->getTitle(),
                'chapterId' => $chapter->getChapterId(),
            ],
            'duas' => []
        ];

        foreach ($chapter->getDua() as $dua) {
            $q = $this->duaItemRepository->createQuery();

            // IRRE (1:n): DuaItem hat Feld "dua" als foreign_field
            $items = $q->matching(
                    $q->equals('dua', $dua)
                )
                ->setOrderings(['sorting' => QueryInterface::ORDER_ASCENDING])
                ->execute();

            $itemsByDua[$dua->getUid()] = $items;
        }

        foreach ($chapter->getDua() as $dua) {
            $duaData = [
                'uid' => $dua->getUid(),
                'duaId' => $dua->getDuaId(),
                'items' => []
            ];

            // $q = $this->duaItemRepository->createQuery();

            // IRRE (1:n): DuaItem hat Feld "dua" als foreign_field
            // $items = $q->matching(
            //         $q->equals('dua', $dua)
            //     )
            //     ->setOrderings(['sorting' => QueryInterface::ORDER_ASCENDING])
            //     ->execute(true);

                
            // $duaData['items'] = $items;

            // $rows = $q->execute()->fetchAll();
            // print_r( $items);

            // foreach ($items as $item) {
                // $duaData['items'][] = [
                //     'uid' => $item->getUid(),
                //     'type' => $item->getType(),
                //     'content' => $item->getContent(),
                // ];
            // }



            foreach ($dua->getItems() as $item) {
                $duaData['items'][] = [
                    'uid' => $item->getUid(),
                    'type' => $item->getType(),
                    'content' => $item->getContent(),
                ];
            }

            $jsonData['duas'][] = $duaData;
        }

        return new JsonResponse($jsonData);
    }


    /**
     * Search action
     *
     * @param string $query Suchbegriff
     * @return ResponseInterface
     */
    public function searchAction(string $query = ''): ResponseInterface
    {
        $results = [];

        if (!empty($query)) {
            // Beispiel: Suche in Chapter-Titeln
            $results = $this->chapterRepository->findBySearchTerm($query);
        }

        $this->view->assignMultiple([
            'query' => $query,
            'results' => $results,
        ]);

        return $this->htmlResponse();
    }

}