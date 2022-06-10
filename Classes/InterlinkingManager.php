<?php

namespace App\Service\Creator\InterLinking;

use App\Entity\Domain;
use App\Service\Creator\Configurator\DomainConfigurator;
use App\Service\Creator\Structure\StructureManager;
use Exception;

class InterLinkingManager
{
    //  public const TAGLIST = ['authority', 'mozlink', 'external', 'category', 'internal', 'curtag', 'tagcloud'];

    protected array $mainStructure;
    protected array $directories;
    protected Domain $domain;

    public function __construct(private StructureManager $structureManager)
    {
        $this->setMainStructure($this->structureManager->getMainStructure());
        $this->directories = $this->structureManager->getDirectoryList();
    }

    public function findCategoryChildren(int $current_id, string $pagetype = 'category', int $max = 0): array
    {
        $childrenFiltered = array_filter($this->structureManager->getMainStructure(), function ($var) use ($current_id, $pagetype) {
            if ($current_id == $var['parent_id'] && ($pagetype == $var['type'] || '' == $var['type'])) {
                return $var;
            }
        });

        $childrenFiltered = $this->getMaxResults($childrenFiltered, $max);

        if (!empty($childrenFiltered)) {
            return $this->appendUrlField($childrenFiltered);
        }

        return [];
    }

    public function findRandom(int $max = 10): array
    {
        $randomLinks = $this->getMaxResults($this->structureManager->getMainStructure(), $max);

        return $this->appendUrlField($randomLinks);
    }

    public function findParentLevel(int $parent_id): array|null
    {
        $parentFiltered = array_filter($this->structureManager->getMainStructure(), function ($var) use ($parent_id) {
            if ($parent_id == $var['id']) {
                return $var;
            }
        });

        try {
            if (count($parentFiltered) > 0) {
                $newFiltered[$parent_id] = $parentFiltered[$parent_id];
                $newFiltered[$parent_id]['url'] = DomainConfigurator::DOMAIN_PREFIX.$this->getDomain().'/'.$this->getChildUrl($parentFiltered[$parent_id]['id']);
            } else {
                $newFiltered[$parent_id] = [
                    'url' => '/',
                    'mainKeyword' => $this->getRandomKeyword(),
                    'name' => $this->getRandomKeyword(),
                ];
            }
        } catch (Exception $e) {
            echo $e->getMessage().' '.$e->getLine();
            var_dump($parentFiltered);
        }
        return $newFiltered;
    }

    public function findCurrentLevel(int $parent_id, string $pagetype = 'category', int $max = 20): array
    {
        $childrenFiltered = array_filter($this->structureManager->getMainStructure(), function ($var) use ($parent_id, $pagetype) {
            if ($parent_id == $var['parent_id'] && ($pagetype == $var['type'] || '' == $var['type'])) {
                return $var;
            }
        });
        $childrenFiltered = $this->getMaxResults($childrenFiltered, $max);

        return $this->appendUrlField($childrenFiltered);
    }

    public function getStructure(): array
    {
        return $this->getStructure();
    }

    public function getDomain(): Domain
    {
        return $this->domain;
    }

    public function setDomain(Domain $domain): void
    {
        $this->domain = $domain;
    }

    public function setMainStructure(array $structureManager): void
    {
        $this->mainStructure = $structureManager;
    }

    public function getPageLinkData(): array
    {
        return [];
    }

    public function getCategoryLinksCollection(array $page, $max = 0): array
    {
        $parentLink = $this->findParentLevel($page['parent_id']);

        $categoryChildren = $this->findCategoryChildren($page['id'], 'category', $max);

        if (count($parentLink) > 0 && count($categoryChildren) > 0) {
            return $parentLink + $categoryChildren;
        }

        return $parentLink;
    }

    public function getRandomLinks(array $page, $max = 0): array
    {
        $parentLink = $this->findParentLevel($page['parent_id']);

        $categoryChildren = $this->findCategoryChildren($page['id'], 'category', $max);

        if (count($parentLink) > 0 && count($categoryChildren) > 0) {
            return $parentLink + $categoryChildren;
        }

        return $parentLink;
    }

    public function getRandomKeyword()
    {
        $randompage_id = array_rand($this->structureManager->getMainStructure());

        return $this->structureManager->getMainStructure()[$randompage_id]['mainKeyword'];
    }

    private function getMaxResults(array $linksArray, int $max = 10): array
    {
        shuffle($linksArray);

        return array_slice($linksArray, 0, $max);
    }

    private function appendUrlField($linksArray): array
    {
        try {
            foreach ($linksArray as $var) {
                $var['url'] = DomainConfigurator::DOMAIN_PREFIX.$this->getDomain().'/'.$this->getChildUrl($var['id']);
                $extraFilter[] = $var;
            }
        } catch (Exception $e) {
            echo 'extrafilter is empty? : '.$e->getMessage();
        }

        return $extraFilter;
    }

    private function getChildUrl(int $current_id)
    {
        return $this->directories[$current_id];
    }
}
