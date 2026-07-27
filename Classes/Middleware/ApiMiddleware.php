<?php

declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use Webzadev\Hisnulmuslim\Domain\Model\Category;
use Webzadev\Hisnulmuslim\Domain\Model\Chapter;
use Webzadev\Hisnulmuslim\Domain\Model\Dua;
use Webzadev\Hisnulmuslim\Domain\Model\DuaItem;
use Webzadev\Hisnulmuslim\Domain\Repository\CategoryRepository;
use Webzadev\Hisnulmuslim\Domain\Repository\ChapterRepository;
use Webzadev\Hisnulmuslim\Domain\Repository\DuaRepository;

/**
 * Standalone JSON API for app clients, based on the sys_category / Chapter / Dua / DuaItem
 * structure. Intercepts requests below /api/ before TYPO3 resolves a page, so it works without
 * any page, plugin or TypoScript setup.
 *
 * Endpoints:
 *   GET /api/                     full category tree (incl. chapters) - replaces old ?structure=1
 *   GET /api/structure             same as above
 *   GET /api/categories             flat list of all categories
 *   GET /api/categories/{uid}       one category with its direct children + chapters
 *   GET /api/chapters               all chapters (light, no dua content)
 *   GET /api/chapters?category={uid} chapters of one category
 *   GET /api/chapter/{uid}          one chapter incl. all dua + dua items
 *   GET /api/duas                   all dua incl. items, flat (chapterUid references the chapter)
 *   GET /api/duas?chapter={uid}     dua of one chapter
 *   GET /api/search?q=...           search chapters by title / titleAr
 */
class ApiMiddleware implements MiddlewareInterface
{
    private const PATH_PREFIX = '/api/';

    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ChapterRepository $chapterRepository,
        private readonly DuaRepository $duaRepository,
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();

        if (!str_starts_with($path, self::PATH_PREFIX)) {
            return $handler->handle($request);
        }

        if ($request->getMethod() === 'OPTIONS') {
            return $this->jsonResponse([]);
        }

        $relative = trim(substr($path, strlen(self::PATH_PREFIX)), '/');
        $segments = $relative === '' ? [] : explode('/', $relative);
        $endpoint = $segments[0] ?? '';
        $id = isset($segments[1]) && $segments[1] !== '' ? (int)$segments[1] : null;

        try {
            if ($endpoint === '' || $endpoint === 'structure') {
                return $this->jsonResponse($this->structure());
            }
            if ($endpoint === 'categories' && $id === null) {
                return $this->jsonResponse($this->categoriesList());
            }
            if ($endpoint === 'categories' && $id !== null) {
                return $this->categoryDetail($id);
            }
            if ($endpoint === 'chapters') {
                return $this->jsonResponse($this->chaptersList($request));
            }
            if ($endpoint === 'chapter' && $id !== null) {
                return $this->chapterDetail($id);
            }
            if ($endpoint === 'duas') {
                return $this->jsonResponse($this->duasList($request));
            }
            if ($endpoint === 'search') {
                return $this->jsonResponse($this->searchChapters($request));
            }

            return $this->jsonResponse(['error' => 'Unknown endpoint: ' . $endpoint], 404);
        } catch (\Throwable $e) {
            return $this->jsonResponse(['error' => $e->getMessage()], 500);
        }
    }

    private function structure(): array
    {
        $topCategories = $this->toArray($this->categoryRepository->findTopLevel());

        return [
            'categories' => array_map(fn (Category $category) => $this->categoryToArray($category, -1), $topCategories),
        ];
    }

    private function categoriesList(): array
    {
        $all = $this->toArray($this->categoryRepository->findAll());

        return [
            'categories' => array_map(fn (Category $category) => $this->categoryToArray($category, 0), $all),
        ];
    }

    private function categoryDetail(int $uid): ResponseInterface
    {
        $category = $this->categoryRepository->findByUid($uid);
        if (!$category instanceof Category) {
            return $this->jsonResponse(['error' => 'Category not found'], 404);
        }

        return $this->jsonResponse([
            'category' => $this->categoryToArray($category, 1),
        ]);
    }

    private function chaptersList(ServerRequestInterface $request): array
    {
        $categoryUid = $request->getQueryParams()['category'] ?? null;

        $chapters = $categoryUid !== null
            ? $this->chapterRepository->findByCategory((int)$categoryUid)
            : $this->chapterRepository->findAll();

        return [
            'chapters' => array_map(fn (Chapter $chapter) => $this->chapterToArray($chapter, false), $this->toArray($chapters)),
        ];
    }

    private function chapterDetail(int $uid): ResponseInterface
    {
        $chapter = $this->chapterRepository->findByUid($uid);
        if (!$chapter instanceof Chapter) {
            return $this->jsonResponse(['error' => 'Chapter not found'], 404);
        }

        return $this->jsonResponse([
            'chapter' => $this->chapterToArray($chapter, true),
        ]);
    }

    private function duasList(ServerRequestInterface $request): array
    {
        $chapterUid = $request->getQueryParams()['chapter'] ?? null;

        $duas = $chapterUid !== null
            ? $this->duaRepository->findByChapter((int)$chapterUid)
            : $this->duaRepository->findAll();

        return [
            'duas' => array_map(fn (Dua $dua) => $this->duaToArray($dua), $this->toArray($duas)),
        ];
    }

    private function searchChapters(ServerRequestInterface $request): array
    {
        $query = trim((string)($request->getQueryParams()['q'] ?? ''));
        if ($query === '') {
            return ['query' => $query, 'chapters' => []];
        }

        $chapters = $this->chapterRepository->findBySearchTerm($query);

        return [
            'query' => $query,
            'chapters' => array_map(fn (Chapter $chapter) => $this->chapterToArray($chapter, false), $this->toArray($chapters)),
        ];
    }

    /**
     * @param int $depth -1 = unlimited recursion, 0 = no children, N = N levels of children
     */
    private function categoryToArray(Category $category, int $depth): array
    {
        $children = [];
        if ($depth !== 0) {
            $childCategories = $this->categoryRepository->findByParent((int)$category->getUid());
            $children = array_map(
                fn (Category $child) => $this->categoryToArray($child, $depth > 0 ? $depth - 1 : $depth),
                $this->toArray($childCategories)
            );
        }

        // Ein Chapter kann in der MM-Relation sowohl auf diese Kategorie als auch auf eine
        // ihrer Unterkategorien verweisen (z.B. Migrationsartefakt aus altem kategorie+
        // unterkategorie-Feldpaar). Damit es nicht doppelt auftaucht, gewinnt die spezifischere
        // (Unter-)Kategorie - hier direkt zugeordnete Chapter werden herausgefiltert.
        $descendantChapterUids = $this->collectChapterUids($children);
        $ownChapters = array_values(array_filter(
            $this->toArray($this->chapterRepository->findByCategory((int)$category->getUid())),
            fn (Chapter $chapter) => !in_array($chapter->getUid(), $descendantChapterUids, true)
        ));

        $data = [
            'uid' => $category->getUid(),
            'parent' => $this->getParentUid($category),
            'title' => $category->getTitle(),
            'description' => $category->getDescription(),
            'color' => $category->getColor(),
            'icon' => $this->resolveIconUrl($category),
            'chapters' => array_map(fn (Chapter $chapter) => $this->chapterToArray($chapter, false), $ownChapters),
        ];

        if ($depth !== 0) {
            $data['children'] = $children;
        }

        return $data;
    }

    /**
     * @param array<int, array{chapters: array<int, array{uid: int}>, children?: array}> $categoryNodes
     * @return array<int, int>
     */
    private function collectChapterUids(array $categoryNodes): array
    {
        $uids = [];
        foreach ($categoryNodes as $node) {
            foreach ($node['chapters'] as $chapter) {
                $uids[] = $chapter['uid'];
            }
            if (isset($node['children'])) {
                $uids = array_merge($uids, $this->collectChapterUids($node['children']));
            }
        }
        return $uids;
    }

    private function chapterToArray(Chapter $chapter, bool $withDuas): array
    {
        $data = [
            'uid' => $chapter->getUid(),
            'chapterId' => $chapter->getChapterId(),
            'title' => $chapter->getTitle(),
            'titleAr' => $chapter->getTitleAr(),
            'slug' => $chapter->getSlug(),
        ];

        if ($withDuas) {
            $data['duas'] = array_map(fn (Dua $dua) => $this->duaToArray($dua), $this->toArray($chapter->getDua()));
        }

        return $data;
    }

    private function duaToArray(Dua $dua): array
    {
        return [
            'uid' => $dua->getUid(),
            'duaId' => $dua->getDuaId(),
            'chapterUid' => $dua->getChapter()?->getUid(),
            'items' => array_map(fn (DuaItem $item) => $this->duaItemToArray($item), $this->toArray($dua->getItems())),
        ];
    }

    private function duaItemToArray(DuaItem $item): array
    {
        return [
            'uid' => $item->getUid(),
            'sorting' => $item->getSorting(),
            'type' => $item->getType(),
            'content' => $item->getContent(),
        ];
    }

    private function getParentUid(Category $category): ?int
    {
        $parent = $category->getParent();

        return $parent instanceof Category ? $parent->getUid() : null;
    }

    private function resolveIconUrl(Category $category): ?string
    {
        $image = $category->getFirstImage();
        if ($image === null) {
            return null;
        }

        $resource = $image->getOriginalResource();
        if ($resource === null) {
            return null;
        }

        $publicUrl = $resource->getPublicUrl();

        return $publicUrl !== null ? GeneralUtility::locationHeaderUrl($publicUrl) : null;
    }

    /**
     * @return array<int, object>
     */
    private function toArray(QueryResultInterface|\Iterator|iterable $result): array
    {
        return $result instanceof \Traversable ? iterator_to_array($result, false) : (array)$result;
    }

    private function jsonResponse(array $data, int $status = 200): JsonResponse
    {
        $response = new JsonResponse($data, $status);

        return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', '*');
    }
}
