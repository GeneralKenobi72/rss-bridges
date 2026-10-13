# RSS-Bridges
<a href="https://github.com/rss-bridge/rss-bridge">RSS-Bridge</a> is a PHP web app for creating RSS feeds
for websites that don't support them.

This repo provides 3 RSS bridges for <a href="https://rss-bridge.org/">RSS-Bridge</a> app.

* etfblBridge - Bridge that takes announcements from notice board site 
of <a href="https://efee.etf.unibl.org/oglasi/">Faculty Of Electrical Engineering in Banja Luka</a>. 
You can choose which section of announcements to keep track of (1st year, 2nd year, masters degree, etc...).
* ArtixUpdatesBridge.php - Bridge that takes packages updates and moves based on Artix 
Linux maintainer. Uses <a href="https://checkupdates.artixlinux.org/">checkupdates site</a>.
* DjokovicBridge.php - Bridge that takes news articles about Novak Đoković 
from <a href = "https://telegraf.rs">Telegraf.rs</a>. They provide rss feeds for Novak 
Đoković, but those feeds don't provide entire text content of the article, but only a 
small part of the beginning. DjokovicBridge pulls entire text content of the articles.
