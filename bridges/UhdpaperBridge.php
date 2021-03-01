<?php

class UhdpaperBridge extends BridgeAbstract
{
    const NAME = 'Uhdpaper Bridge';
    const URI = 'https://www.uhdpaper.com/';
    const DESCRIPTION = 'Displays wallpapers from uhdpaper';
    const MAINTAINER = 'free-bots';
    const PARAMETERS = array(array(
        'search' => array(
            'name' => 'search',
            'title' => 'Search tags like Abstract or Digital+Art. Add a + between multiple tags.',
            'exampleValue' => 'Digital+Art',
            'defaultValue' => 'Digital+Art',
            'type' => 'text',
            'required' => false
        )
    )
    );
    const CACHE_TIMEOUT = 3600;

    public function getIcon()
    {
        return 'https://www.uhdpaper.com/favicon.ico';
    }

    public function collectData()
    {
        $queryUrl = $this->createQueryUrl($this->getInput('search'));

        $html = getSimpleHTMLDOM($queryUrl) or returnServerError('Could not load themes');

        foreach ($html->find('.wp_box') as $element) {

            $image = $element->find('.lazy', 0);
            preg_match('/data-src=\'?((?:.(?!\'?\s+(?:\S+)=|\s*\/?\'))+.)\'?/', $image, $matches);
            $imageUrl = $matches[0];
            $imageUrl = str_replace("data-src='", "", $imageUrl);
            $imageUrl = str_replace("'", "", $imageUrl);
            $link = $element->find('a', 0)->href;

            $item = array();

            $item['content'] = $this->createContent($link, $imageUrl);

            $this->items[] = $item;
        }
    }

    private function createQueryUrl($search)
    {
        return "https://www.uhdpaper.com/search?q=" . $search . "&by-date=trues";
    }

    private function createContent($link, $imageUrl)
    {
        return "<a href='" . $link . "'><img src='" . $imageUrl . "' alt=''></a>";
    }
}
