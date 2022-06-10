<?php

namespace App\Service\Creator\Replacer;

use App\Service\Creator\Configurator\Configurator;
use App\Service\Creator\Configurator\DomainConfigurator;
use App\Service\Creator\Directory\DirectoryManager;
use App\Service\Creator\Image\ImageManager;
use App\Service\Creator\InterLinking\InterLinkingManager;
use Exception;

class ImageTagReplacer extends TagReplacer
{
    private $directoryManager;
    private $category;
    private $domain;
    private $interlinkingManager;
    private DomainConfigurator $domainConfigurator;

    public function __construct(string $source)
    {
        $this->setTagList(DomainConfigurator::IMAGETAGS);
        $this->directoryManager = new DirectoryManager(Configurator::PROJECT_DIRECTORY);
        parent::__construct($source);
    }

    public function tagIterator(): string
    {
        foreach ($this->tagList as $tag) {
            switch ($tag) {
                default:
                    if (null == $this->getCategory()) {
                        $this->source = str_replace('['.strtoupper($tag).']', '#', $this->source);

                        throw new Exception('Category has not been set!');
                    }
                    $this->source = preg_replace_callback(
                        '/(\\['.strtoupper($tag).'\\])/',
                        ['self', 'replacerCallback'],
                        $this->source
                    );
            }
        }

        return $this->source;
    }

    public function replacerCallback($matches): string
    {
        $imagemanager = new ImageManager($this->getCategory(), Configurator::PROJECT_DIRECTORY);
        $this->domainConfigurator = new DomainConfigurator($this->domain);

        $imagemanager->setImageFolder($this->category);
        $randomImage = $imagemanager->getRandomImage();
        $img_webpath = '/'.$this->domainConfigurator->getImageTargetFolder().'/'.
            $imagemanager->generateNewImageFileName($randomImage, $this->interlinkingManager->getRandomKeyword());

        $newImage = $this->directoryManager->getBaseDir().'/'.$this->domain.$img_webpath;

        $dir = Configurator::PROJECT_DIRECTORY;

        copy($randomImage, $dir.$newImage);
        return $img_webpath;
    }

    public function setCategory($category): void
    {
        $this->category = $category;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function setDomain($domain): void
    {
        $this->domain = $domain;
    }

    public function setInterlinkingManager(InterLinkingManager $interLinkingManager): void
    {
        $this->interlinkingManager = $interLinkingManager;
    }
}
