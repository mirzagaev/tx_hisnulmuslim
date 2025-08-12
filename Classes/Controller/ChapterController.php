<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Webzadev\Hisnulmuslim\Domain\Repository\ChapterRepository;

class ChapterController extends ActionController
{

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
        $chapters = $this->chapterRepository->findAll();

        $this->view->assign('chapters', $chapters);

        return $this->htmlResponse();
    }

    /**
     * Show action
     *
     * @param \Webzadev\Hisnulmuslim\Domain\Model\Chapter $chapter The chapter to be shown
     * @return string The rendered HTML string
     */
    public function showAction(\Webzadev\Hisnulmuslim\Domain\Model\Chapter $chapter)
    {
        $this->view->assign('chapter', $chapter);
    }
}