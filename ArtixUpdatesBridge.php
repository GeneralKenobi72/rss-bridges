<?php

class ArtixUpdatesBridge extends BridgeAbstract
{
    const NAME = 'Artix checkupdates web';
    const URI = 'https://checkupdates.artixlinux.org/';
    const DESCRIPTION = 'Returns packages that need attention and the action required by a maintainer for Artix Linux';
    const MAINTAINER = 'Luka Zeljko';
    const CACHE_TIMEOUT = 700;

    const PARAMETERS = [
        [
            'maintainer' => [
                'type' => 'list',
                'name' => 'maintainer',
                'title' => 'Choose maintainer',
                'values' => [
                    'aartoni' => '/aartoni',
					'arc-d3v' => '/arc-d3v',
                    'artist' => '/artist',
                    'artoo' => '/artoo',
					'capezotte' => '/capezotte',
                    'corysanin' => '/corysanin',
                    'dovloran' => '/dovloran',
                    'dudemanguy' => '/dudemanguy',
                    'kenobi' => '/kenobi',
                    'konimex' => '/konimex',
                    'lemonpie' => '/lemonpie',
                    'marzosh' => '/marzosh',
                    'nathan' => '/nathan',
                    'nikolar' => '/nikolar',
                    'nous' => '/nous',
                    'rustystriker' => '/rustystriker',
                    'orphan' => '/orphan'
                ],
            ]
	]
    ];

    public function collectData()
    {
	$url = sprintf('https://checkupdates.artixlinux.org/maintainer%s', $this->getInput('maintainer'));
	$html = getSimpleHTMLDOM($url, $CACHE_TIMEOUT);

	$table = $html->find('table', 0);
	$first = 1;
	foreach($table->find('tr') as $pkgaction) {
		if ($first != 1) {
			$pkg = $pkgaction->find('a', 0);
			$urlgitea = sprintf('https://gitea.artixlinux.org/packages/%s/commits/branch/master', $pkg->plaintext);
			$urlarch_packages = sprintf('https://archlinux.org/packages/?q=%s', $pkg->plaintext);
			//$htmlarch = getSimpleHTMLDOM($urlarch_packages, $CACHE_TIMEOUT);
			//$arch_pkg_href = $htmlarch->find('td a', 0);
			//$arch_link = "https://archlinux.org" . $arch_pkg_href->href;
			$htmlgitea = getSimpleHTMLDOM($urlgitea, $CACHE_TIMEOUT);
			$lastcommit = $htmlgitea->find('a[rel="nofollow"]', 1);
			$action = $pkgaction->find('td', 2);
			$gitlab = sprintf('https://gitlab.archlinux.org/archlinux/packaging/packages/%s/-/commits/main', $pkg->plaintext);
			$gitea = sprintf('https://gitea.artixlinux.org/packages/%s/commits/branch/master', $pkg->plaintext);
			$content1 = sprintf("Package <b>%s</b> needs <b>%sing</b>", $pkg->plaintext, strtolower(substr($action->plaintext, 0, -1)));
			$content2 = "Some usefult links:";
			$content3 = sprintf("Arch <b>GitLab</b> repo link: <a href=%s>%s</a>", $gitlab, $gitlab);
			$content4 = sprintf("Arch <b>Package search</b> link: <a href=%s>%s</a>", $urlarch_packages, $urlarch_packages);
			$content5 = sprintf("Artix <b>Gitea</b> repo link: <a href=%s>%s</a>", $gitea, $gitea);
	
			$content = $content1 . "<br><br>" . $content2 . "<br>" . $content3 . "<br>" . $content4 . "<br>" . $content5;
			$id = $pkg . $action . $lastcommit->plaintext;

			$this->items[] =  [
				'title' => $pkg->plaintext . ' ' . strtolower($action->plaintext),
				'uri' => $pkg->href,
				'content' => $content,
				'uid' => $id,
			];
		}
		$first = 2;
	}
    }
}
