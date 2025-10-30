<?php
declare(strict_types=1);

namespace Webzadev\Hisnulmuslim\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

class ChapterRepository extends Repository
{
    public function findByCategory(int $categoryUid): QueryResultInterface
    {
        $query = $this->createQuery();
        $query->matching(
            $query->contains('categories', $categoryUid)
        );
        return $query->execute();
    }
    
    public function findBySearchTerm(string $term)
    {
        $query = $this->createQuery();

        // $constraints = [
        //     $query->like('title', '%' . $term . '%'),
        //     $query->like('titleAr', '%' . $term . '%'),
        // ];

        // $query->matching(
        //     $query->logicalOr(...$constraints)
        // );

        $query->matching(
            $query->like('title', '%' . $term . '%')
        );

        return $query->execute();
    }

}