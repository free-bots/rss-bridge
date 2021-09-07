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

        $pattern = "/(?<=var productBrowseDataEncoded = ').*?(?=';*)/"; // base64

        $html = getContents($queryUrl) or returnServerError('Could not load themes');

        preg_match($pattern, $html, $matches);

        if (empty($matches)) {
            return;
        }

        $json = json_decode(base64_decode($matches[0]));
        $products = $json -> products;

        foreach ($products as $product) {

            $icon = $this -> createIcon($product -> image_small);
            $details = $product -> description;
            $url = $this -> createUrl($product);
            $title = $product -> title;
            $author = $product -> username;
            $content = $this -> createContent($product, $url);

            $item = array();

            $item['title'] = $title;
            $item['author'] = $author;
            $item['content'] = $content;
            $item['uri'] = $url;

            $this->items[] = $item;
        }
    }

    private function createQueryUrl()
    {
        return 'https://www.pling.com/s/Gnome/browse/ord/latest/';
    }

    private function createIcon($icon)
    {
        return '<img src="' . 'https://cdn.pling.com/cache/350x350-2/img/' . $icon . '" />';
    }

    private function createUrl($product)
    {
        return 'https://www.pling.com/s/Gnome/p/' . $product -> project_id;
    }

    private function createContent($product, $url)
    {
        $icon = $this -> createIcon($product -> image_small);
        $details = $product -> description;
        return '<div>' . $icon . '<a href="' . $url . '"><p>' . $details . '</p></a></div>';
    }
}
