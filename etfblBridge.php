<?php

class etfblBridge extends BridgeAbstract
{
    const NAME = 'ETFBL oglasi';
    const URI = 'https://efee.etf.unibl.org/oglasi/';
    const DESCRIPTION = 'Vraca oglase sa sajta Elektrotehnickog fakulteta u Banjaluci';
    const MAINTAINER = 'Luka Zeljko';
    const CACHE_TIMEOUT = 0;

    const PARAMETERS = [
        [
            'sekcija' => [
                'type' => 'list',
                'name' => 'sekcija',
                'title' => 'Izaberi sekciju',
                'values' => [
                    'Prva godina' => '/1',
                    'Druga godina' => '/2',
                    'Treca godina' => '/3',
                    'Cetvrta godina' => '/4',
                    'Drugi ciklus' => '/20',
                    'Treci ciklus' => '/21',
                    'Postdiplomski studij' => '/30',
                    'Zavrsni radovi' => '/102'
                ],
            ]
	]
    ];

    public function collectData()
    {
	$url = 'https://efee.etf.unibl.org:8443/api/public/oglasne-ploce';
        $url = $url . $this->getInput('sekcija');
        $json = Json::decode(getContents($url));

	foreach ($json as $index => $entry) {
    		if (!is_array($entry)) continue;

    		$title  = $entry['naslov']  ?? null;
    		$author = $entry['potpis'] ?? null;
		$content = $entry['sadrzaj'] ?? null;	
		$uid	= $entry['id'] ?? null;
		$uri	= 'https://efee.etf.unibl.org/oglasi/' ?? null;
		$timestamp = $entry['vrijemeKreiranja'] ?? null;
		$item = [
			'uid' => $uid,
    			'title' => $title,
    			'author' => $author,
    			'content' => $content,
			'uri' => $uri . $uid,
			'timestamp' => $timestamp
		];

    		$this->items[] = $item;
	}
    }

    public function getURI()
    {
        $uri = parent::getURI();
        return $uri;
    }
}
