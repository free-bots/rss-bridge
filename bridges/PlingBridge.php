<?php

class PlingBridge extends BridgeAbstract
{
    const NAME = 'Pling Bridge';
    const URI = 'https://www.pling.com/';
    const DESCRIPTION = 'Displays current Gnome themes';
    const MAINTAINER = 'free-bots';
    const PARAMETERS = array();
    const CACHE_TIMEOUT = 3600;

    public function getIcon()
    {
        return 'https://www.pling.com/favicon.ico';
    }

    public function getName()
    {
        return parent::getName();
    }

    public function collectData()
    {
        $queryUrl = $this->createQueryUrl();

        $html = getSimpleHTMLDOM($queryUrl) or returnServerError('Could not load themes');

        foreach ($html->find('.explore-product') as $element) {

            $icon = $element->find('img.explore-product-image', 0);
            $details = $element->find('div.explore-product-details', 0)->plaintext;

            $link = $element->find('div.explore-product-details', 0)->children(0)->children(0);
            $url = 'https://www.pling.com' . $link->href;

            $title = $link->plaintext;


            $item = array();

            $item['title'] = $title;
            $item['content'] = '<div>' . $icon . '<a href="' . $url . '">' . $details . '</a></div>';
            $item['uri'] = $url;

            $this->items[] = $item;
        }
    }

    private function createQueryUrl()
    {
        return 'https://www.pling.com/s/Gnome/browse/ord/latest/';
    }
}
