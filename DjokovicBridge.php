<?php

class DjokovicBridge extends BridgeAbstract
{
    const NAME = 'Telegraf novosti vezane za Novaka Đokovića';
    const URI = 'https://www.telegraf.rs/teme/novak-djokovic';
    const DESCRIPTION = 'Vraća novosti vezane za tenisera Novaka Đokovića sa čitavim tekstovima';
    const MAINTAINER = 'Luka Zeljko';
    const CACHE_TIMEOUT = 10;

	public $seen = [];

    public function collectData()
    {
		$url = 'https://www.telegraf.rs/teme/novak-djokovic';
		$html = getSimpleHTMLDOM($url, $CACHE_TIMEOUT);

		$page_content = $html->find('div[class="page-content"]', 0);
		$main_section = $page_content->find('div[class="main-section-item"]', 0);
		$middle_section = $page_content->find('div[class="middle-section-item"]', 0);
		$list_items1 = $page_content->find('div[class="list-item"]', 0);
		$list_items2 = $html->find('div[class="list-item"]', 1);

		foreach($main_section->find('a') as $href) {
			$this->fetchContent($href);
		}
		foreach($middle_section->find('a') as $href) {
			$this->fetchContent($href);
		}
		foreach($list_items1->find('a') as $href) {
			$this->fetchContent($href);
		}
		foreach($list_items2->find('a') as $href) {
			$this->fetchContent($href);
		}
    }

	public function fetchContent($href)
	{
		if ($href->href === '' || isset($this->seen[$href->href])) return;
        $this->seen[$href->href] = true;

		$html_article = getSimpleHTMLDOM($href->href, $CACHE_TIMEOUT);
		$image = $html_article->find('picture', 0);
		$content = $html_article->find('div[class="article-content"]', 0);
		if ($content !== null) {
    		foreach ($content->find('script') as $script) {
        		$script->outertext = '';
    		}
    		foreach ($content->find('div.related-widget-wrapper.recommended-widget-wrapper') as $script) {
        		$script->outertext = '';
    		}
    		foreach ($content->find('div#related-focus-news-widget') as $script) {
        		$script->outertext = '';
    		}
		}
		$this->items[] = [
			'title' => $href->title,
			'uri' => $href->href,
			'content' => $image . $content,
			'uid' => $href->href,
		];
	}
}
