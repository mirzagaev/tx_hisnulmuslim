<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;

class CategoryRepository extends Repository
{
    // protected array $defaultOrderings = ['title' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];

    public function initializeObject(): void
    {
        $qs = $this->createQuery()->getQuerySettings();
        $qs->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($qs);
    }
}