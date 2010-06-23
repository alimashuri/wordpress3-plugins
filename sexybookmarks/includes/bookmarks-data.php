<?php

// dynamic mister wong link generator
$wong_tld = '.com';

if(WPLANG == 'de_DE')
	$wong_tld = '.de';

elseif(WPLANG == 'zh_CN' || WPLANG == 'zh_HK' || WPLANG == 'zh_TW')
	$wong_tld = '.cn';

elseif(WPLANG == 'es_CL'  || WPLANG == 'es_ES' || WPLANG == 'es_PE' || WPLANG == 'es_VE')
	$wong_tld = '.es';

elseif(WPLANG == 'fr_FR' || WPLANG == 'fr_BE')
	$wong_tld = '.fr';

elseif(WPLANG =='ru_RU' || WPLANG == 'ru_MA')
	$wong_tld = '.ru';

$checkthis_text = __('Check this box to include %s in your bookmarking menu', 'shrsb');

// array of bookmarks
$shrsb_bookmarks_data=array(
	'shr-scriptstyle'=>array(
		'check'=>sprintf($checkthis_text, 'Script &amp; Style'),
		'share'=>__('Submit this to ', 'shrsb').'Script &amp; Style',
		'baseUrl'=>'http://scriptandstyle.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'shr-blinklist'=>array(
		'check'=>sprintf($checkthis_text, 'Blinklist'),
		'share'=>__('Share this on ', 'shrsb').'Blinklist',
		'baseUrl'=>'http://www.blinklist.com/index.php?Action=Blink/addblink.php&amp;Url=PERMALINK&amp;Title=TITLE',
	),
	'shr-delicious'=>array(
		'check'=>sprintf($checkthis_text,'Delicious'),
		'share'=>__('Share this on ', 'shrsb').'del.icio.us',
		'baseUrl'=>'http://delicious.com/post?url=PERMALINK&amp;title=TITLE',
	),
	'shr-digg'=>array(
		'check'=>sprintf($checkthis_text,'Digg'),
		'share'=>__('Digg this!', 'shrsb'),
		'baseUrl'=>'http://digg.com/submit?phase=2&amp;url=PERMALINK&amp;title=TITLE',
	),
	'shr-diigo'=>array(
		'check'=>sprintf($checkthis_text,'Diigo'),
		'share'=>__('Post this on ', 'shrsb').'Diigo',
		'baseUrl'=>'http://www.diigo.com/post?url=PERMALINK&amp;title=TITLE&amp;desc=SEXY_TEASER',
	),
	'shr-reddit'=>array(
		'check'=>sprintf($checkthis_text,'Reddit'),
		'share'=>__('Share this on ', 'shrsb').'Reddit',
		'baseUrl'=>'http://reddit.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'shr-yahoobuzz'=>array(
		'check'=>sprintf($checkthis_text,'Yahoo! Buzz'),
		'share'=>__('Buzz up!', 'shrsb'),
		'baseUrl'=>'http://buzz.yahoo.com/submit/?submitUrl=PERMALINK&amp;submitHeadline=TITLE&amp;submitSummary=YAHOOTEASER&amp;submitCategory=YAHOOCATEGORY&amp;submitAssetType=YAHOOMEDIATYPE',
	),
	'shr-stumbleupon'=>array(
		'check'=>sprintf($checkthis_text,'Stumbleupon'),
		'share'=>__('Stumble upon something good? Share it on StumbleUpon', 'shrsb'),
		'baseUrl'=>'http://www.stumbleupon.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'shr-technorati'=>array(
		'check'=>sprintf($checkthis_text,'Technorati'),
		'share'=>__('Share this on ', 'shrsb').'Technorati',
		'baseUrl'=>'http://technorati.com/faves?add=PERMALINK',
	),
	'shr-mixx'=>array(
		'check'=>sprintf($checkthis_text,'Mixx'),
		'share'=>__('Share this on ', 'shrsb').'Mixx',
		'baseUrl'=>'http://www.mixx.com/submit?page_url=PERMALINK&amp;title=TITLE',
	),
	'shr-myspace'=>array(
		'check'=>sprintf($checkthis_text,'MySpace'),
		'share'=>__('Post this to ', 'shrsb').'MySpace',
		'baseUrl'=>'http://www.myspace.com/Modules/PostTo/Pages/?u=PERMALINK&amp;t=TITLE',
	),
	'shr-designfloat'=>array(
		'check'=>sprintf($checkthis_text,'DesignFloat'),
		'share'=>__('Submit this to ', 'shrsb').'DesignFloat',
		'baseUrl'=>'http://www.designfloat.com/submit.php?url=PERMALINK&amp;title=TITLE',
	),
	'shr-facebook'=>array(
		'check'=>sprintf($checkthis_text,'Facebook'),
		'share'=>__('Share this on ', 'shrsb').'Facebook',
		'baseUrl'=>'http://www.facebook.com/share.php?v=4&amp;src=bm&amp;u=PERMALINK&amp;t=TITLE',
	),
	'shr-twitter'=>array(
		'check'=>sprintf($checkthis_text,'Twitter'),
		'share'=>__('Tweet This!', 'shrsb'),
		'baseUrl'=>'http://twitter.com/home?status=',
	),
	'shr-mail'=>array(
		'check'=>sprintf($checkthis_text, __("an 'Email to a Friend' link", 'shrsb')),
		'share'=>__('Email this to a friend?', 'shrsb'),
      'baseUrl'=>'mailto:?subject=%22TITLE%22&amp;body=Link: PERMALINK '.__('(sent via shareaholic)', 'shrsb').'%0D%0A%0D%0A----%0D%0A POST_SUMMARY',
	),
	'shr-tomuse'=>array(
		'check'=>sprintf($checkthis_text,'ToMuse'),
		'share'=>__('Suggest this article to ', 'shrsb').'ToMuse',
      'baseUrl'=>'mailto:tips@tomuse.com?subject='.urlencode( __('New tip submitted via the SexyBookmarks Plugin!', 'shrsb') ).'&amp;body=Link: PERMALINK %0D%0A%0D%0A POST_SUMMARY',
	),
	'shr-comfeed'=>array(
		'check'=>sprintf($checkthis_text, __("a 'Subscribe to Comments' link", 'shrsb')),
		'share'=>__('Subscribe to the comments for this post?', 'shrsb'),
		'baseUrl'=>'PERMALINK',
	),
	'shr-linkedin'=>array(
		'check'=>sprintf($checkthis_text,'LinkedIn'),
		'share'=>__('Share this on ', 'shrsb').'LinkedIn',
		'baseUrl'=>'http://www.linkedin.com/shareArticle?mini=true&amp;url=PERMALINK&amp;title=TITLE&amp;summary=POST_SUMMARY&amp;source=SITE_NAME',
	),
	'shr-newsvine'=>array(
		'check'=>sprintf($checkthis_text,'Newsvine'),
		'share'=>__('Seed this on ', 'shrsb').'Newsvine',
		'baseUrl'=>'http://www.newsvine.com/_tools/seed&amp;save?u=PERMALINK&amp;h=TITLE',
	),
	'shr-googlebookmarks'=>array(
		'check'=>sprintf($checkthis_text,'Google Bookmarks'),
		'share'=>__('Add this to ', 'shrsb').'Google Bookmarks',
		'baseUrl'=>'http://www.google.com/bookmarks/mark?op=add&amp;bkmk=PERMALINK&amp;title=TITLE',
	),
	'shr-googlereader'=>array(
		'check'=>sprintf($checkthis_text,'Google Reader'),
		'share'=>__('Add this to ', 'shrsb').'Google Reader',
		'baseUrl'=>'http://www.google.com/reader/link?url=PERMALINK&amp;title=TITLE&amp;srcUrl=PERMALINK&amp;srcTitle=TITLE&amp;snippet=POST_SUMMARY',
	),
	'shr-googlebuzz'=>array(
		'check'=>sprintf($checkthis_text,'Google Buzz'),
		'share'=>__('Post on Google Buzz', 'shrsb'),
		'baseUrl'=>'http://www.google.com/buzz/post?url=PERMALINK&amp;imageurl=',
	),
	'shr-misterwong'=>array(
		'check'=>sprintf($checkthis_text,'Mister Wong'),
		'share'=>__('Add this to ', 'shrsb').'Mister Wong',
		'baseUrl'=>'http://www.mister-wong'.$wong_tld.'/addurl/?bm_url=PERMALINK&amp;bm_description=TITLE&amp;plugin=sexybookmarks',
	),
	'shr-izeby'=>array(
		'check'=>sprintf($checkthis_text,'Izeby'),
		'share'=>__('Add this to ', 'shrsb').'Izeby',
		'baseUrl'=>'http://izeby.com/submit.php?url=PERMALINK',
	),
	'shr-tipd'=>array(
		'check'=>sprintf($checkthis_text,'Tipd'),
		'share'=>__('Share this on ', 'shrsb').'Tipd',
		'baseUrl'=>'http://tipd.com/submit.php?url=PERMALINK',
	),
	'shr-pfbuzz'=>array(
		'check'=>sprintf($checkthis_text,'PFBuzz'),
		'share'=>__('Share this on ', 'shrsb').'PFBuzz',
		'baseUrl'=>'http://pfbuzz.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'shr-friendfeed'=>array(
		'check'=>sprintf($checkthis_text,'FriendFeed'),
		'share'=>__('Share this on ', 'shrsb').'FriendFeed',
		'baseUrl'=>'http://www.friendfeed.com/share?title=TITLE&amp;link=PERMALINK',
	),
	'shr-blogmarks'=>array(
		'check'=>sprintf($checkthis_text,'BlogMarks'),
		'share'=>__('Mark this on ', 'shrsb').'BlogMarks',
		'baseUrl'=>'http://blogmarks.net/my/new.php?mini=1&amp;simple=1&amp;url=PERMALINK&amp;title=TITLE',
	),
	'shr-twittley'=>array(
		'check'=>sprintf($checkthis_text,'Twittley'),
		'share'=>__('Submit this to ', 'shrsb').'Twittley',
		'baseUrl'=>'http://twittley.com/submit/?title=TITLE&amp;url=PERMALINK&amp;desc=POST_SUMMARY&amp;pcat=TWITT_CAT&amp;tags=DEFAULT_TAGS',
	),
	'shr-fwisp'=>array(
		'check'=>sprintf($checkthis_text,'Fwisp'),
		'share'=>__('Share this on ', 'shrsb').'Fwisp',
		'baseUrl'=>'http://fwisp.com/submit?url=PERMALINK',
	),
	'shr-bobrdobr'=>array(
		'check'=>sprintf($checkthis_text,'BobrDobr').__(' (Russian)', 'shrsb'),
		'share'=>__('Share this on ', 'shrsb').'BobrDobr',
		'baseUrl'=>'http://bobrdobr.ru/addext.html?url=PERMALINK&amp;title=TITLE',
	),
	'shr-yandex'=>array(
		'check'=>sprintf($checkthis_text,'Yandex.Bookmarks').__(' (Russian)', 'shrsb'),
		'share'=>__('Add this to ', 'shrsb').'Yandex.Bookmarks',
		'baseUrl'=>'http://zakladki.yandex.ru/userarea/links/addfromfav.asp?bAddLink_x=1&amp;lurl=PERMALINK&amp;lname=TITLE',
	),
	'shr-memoryru'=>array(
		'check'=>sprintf($checkthis_text,'Memory.ru').__(' (Russian)', 'shrsb'),
		'share'=>__('Add this to ', 'shrsb').'Memory.ru',
		'baseUrl'=>'http://memori.ru/link/?sm=1&amp;u_data[url]=PERMALINK&amp;u_data[name]=TITLE',
	),
	'shr-100zakladok'=>array(
		'check'=>sprintf($checkthis_text,'100 bookmarks').__(' (Russian)', 'shrsb'),
		'share'=>__('Add this to ', 'shrsb').'100 bookmarks',
		'baseUrl'=>'http://www.100zakladok.ru/save/?bmurl=PERMALINK&amp;bmtitle=TITLE',
	),
	'shr-moemesto'=>array(
		'check'=>sprintf($checkthis_text,'MyPlace').__(' (Russian)', 'shrsb'),
		'share'=>__('Add this to ', 'shrsb').'MyPlace',
		'baseUrl'=>'http://moemesto.ru/post.php?url=PERMALINK&amp;title=TITLE',
	),
	'shr-hackernews'=>array(
		'check'=>sprintf($checkthis_text,'Hacker News'),
		'share'=>__('Submit this to ', 'shrsb').'Hacker News',
		'baseUrl'=>'http://news.ycombinator.com/submitlink?u=PERMALINK&amp;t=TITLE',
	),
	'shr-printfriendly'=>array(
		'check'=>sprintf($checkthis_text,'Print Friendly'),
		'share'=>__('Send this page to ', 'shrsb').'Print Friendly',
		'baseUrl'=>'http://www.printfriendly.com/print?url=PERMALINK',
	),
	'shr-designbump'=>array(
		'check'=>sprintf($checkthis_text,'Design Bump'),
		'share'=>__('Bump this on ', 'shrsb').'DesignBump',
		'baseUrl'=>'http://designbump.com/submit?url=PERMALINK&amp;title=TITLE&amp;body=POST_SUMMARY',
	),
	'shr-ning'=>array(
		'check'=>sprintf($checkthis_text,'Ning'),
		'share'=>__('Add this to ', 'shrsb').'Ning',
		'baseUrl'=>'http://bookmarks.ning.com/addItem.php?url=PERMALINK&amp;T=TITLE',
	),
	'shr-identica'=>array(
		'check'=>sprintf($checkthis_text,'Identica'),
		'share'=>__('Post this to ', 'shrsb').'Identica',
		'baseUrl'=>'http://identi.ca//index.php?action=newnotice&amp;status_textarea=Reading:+&quot;SHORT_TITLE&quot;+-+from+FETCH_URL',
	),
	'shr-xerpi'=>array(
		'check'=>sprintf($checkthis_text,'Xerpi'),
		'share'=>__('Save this to ', 'shrsb').'Xerpi',
		'baseUrl'=>'http://www.xerpi.com/block/add_link_from_extension?url=PERMALINK&amp;title=TITLE',
	),
	'shr-wikio'=>array(
		'check'=>sprintf($checkthis_text,'Wikio'),
		'share'=>__('Share this on ', 'shrsb').'Wikio',
		'baseUrl'=>'http://www.wikio.com/sharethis?url=PERMALINK&amp;title=TITLE',
	),
	'shr-techmeme'=>array(
		'check'=>sprintf($checkthis_text,'TechMeme'),
		'share'=>__('Tip this to ', 'shrsb').'TechMeme',
		'baseUrl'=>'http://twitter.com/home/?status=Tip+@Techmeme+PERMALINK+&quot;TITLE&quot;&amp;source=shareaholic',
	),
	'shr-sphinn'=>array(
		'check'=>sprintf($checkthis_text,'Sphinn'),
		'share'=>__('Sphinn this on ', 'shrsb').'Sphinn',
		'baseUrl'=>'http://sphinn.com/index.php?c=post&amp;m=submit&amp;link=PERMALINK',
	),
	'shr-posterous'=>array(
		'check'=>sprintf($checkthis_text,'Posterous'),
		'share'=>__('Post this to ', 'shrsb').'Posterous',
		'baseUrl'=>'http://posterous.com/share?linkto=PERMALINK&amp;title=TITLE&amp;selection=POST_SUMMARY',
	),
	'shr-globalgrind'=>array(
		'check'=>sprintf($checkthis_text,'Global Grind'),
		'share'=>__('Grind this! on ', 'shrsb').'Global Grind',
		'baseUrl'=>'http://globalgrind.com/submission/submit.aspx?url=PERMALINK&amp;type=Article&amp;title=TITLE',
	),
	'shr-pingfm'=>array(
		'check'=>sprintf($checkthis_text,'Ping.fm'),
		'share'=>__('Ping this on ', 'shrsb').'Ping.fm',
		'baseUrl'=>'http://ping.fm/ref/?link=PERMALINK&amp;title=TITLE&amp;body=POST_SUMMARY',
	),
	'shr-nujij'=>array(
		'check'=>sprintf($checkthis_text,'NUjij').__(' (Dutch)', 'shrsb'),
		'share'=>__('Submit this to ', 'shrsb').'NUjij',
		'baseUrl'=>'http://nujij.nl/jij.lynkx?t=TITLE&amp;u=PERMALINK&amp;b=POST_SUMMARY',
	),
	'shr-ekudos'=>array(
		'check'=>sprintf($checkthis_text,'eKudos').__(' (Dutch)', 'shrsb'),
		'share'=>__('Submit this to ', 'shrsb').'eKudos',
		'baseUrl'=>'http://www.ekudos.nl/artikel/nieuw?url=PERMALINK&amp;title=TITLE&amp;desc=POST_SUMMARY',
	),
	'shr-netvouz'=>array(
		'check'=>sprintf($checkthis_text,'Netvouz'),
		'share'=>__('Submit this to ', 'shrsb').'Netvouz',
		'baseUrl'=>'http://www.netvouz.com/action/submitBookmark?url=PERMALINK&amp;title=TITLE&amp;popup=no',
	),
	'shr-netvibes'=>array(
		'check'=>sprintf($checkthis_text,'Netvibes'),
		'share'=>__('Submit this to ', 'shrsb').'Netvibes',
		'baseUrl'=>'http://www.netvibes.com/share?title=TITLE&amp;url=PERMALINK',
	),
	'shr-webblend'=>array(
		'check'=>sprintf($checkthis_text,'Web Blend'),
		'share'=>__('Blend this!', 'shrsb'),
		'baseUrl'=>'http://thewebblend.com/submit?url=PERMALINK&amp;title=TITLE&amp;body=POST_SUMMARY',
	),
	'shr-wykop'=>array(
		'check'=>sprintf($checkthis_text,'Wykop').__(' (Polish)', 'shrsb'),
		'share'=>__('Add this to Wykop!', 'shrsb'),
		'baseUrl'=>'http://www.wykop.pl/dodaj?url=PERMALINK&amp;title=TITLE',
	),
	'shr-blogengage'=>array(
		'check'=>sprintf($checkthis_text,'BlogEngage'),
		'share'=>__('Engage with this article!', 'shrsb'),
		'baseUrl'=>'http://www.blogengage.com/submit.php?url=PERMALINK',
	),
	'shr-hyves'=>array(
		'check'=>sprintf($checkthis_text,'Hyves'),
		'share'=>__('Share this on ', 'shrsb').'Hyves',
		'baseUrl'=>'http://www.hyves.nl/profilemanage/add/tips/?name=TITLE&amp;text=POST_SUMMARY+-+PERMALINK&amp;rating=5',
	),
	'shr-pusha'=>array(
		'check'=>sprintf($checkthis_text,'Pusha').__(' (Swedish)', 'shrsb'),
		'share'=>__('Push this on ', 'shrsb').'Pusha',
		'baseUrl'=>'http://www.pusha.se/posta?url=PERMALINK&amp;title=TITLE',
	),
	'shr-hatena'=>array(
		'check'=>sprintf($checkthis_text,'Hatena Bookmarks').__(' (Japanese)', 'shrsb'),
		'share'=>__('Bookmarks this on ', 'shrsb').'Hatena Bookmarks',
		'baseUrl'=>'http://b.hatena.ne.jp/add?mode=confirm&amp;url=PERMALINK&amp;title=TITLE',
	),
	'shr-mylinkvault'=>array(
		'check'=>sprintf($checkthis_text,'MyLinkVault'),
		'share'=>__('Store this link on ', 'shrsb').'MyLinkVault',
		'baseUrl'=>'http://www.mylinkvault.com/link-page.php?u=PERMALINK&amp;n=TITLE',
	),
	'shr-slashdot'=>array(
		'check'=>sprintf($checkthis_text,'SlashDot'),
		'share'=>__('Submit this to ', 'shrsb').'SlashDot',
		'baseUrl'=>'http://slashdot.org/bookmark.pl?url=PERMALINK&amp;title=TITLE',
	),
	'shr-squidoo'=>array(
		'check'=>sprintf($checkthis_text,'Squidoo'),
		'share'=>__('Add to a lense on ', 'shrsb').'Squidoo',
		'baseUrl'=>'http://www.squidoo.com/lensmaster/bookmark?PERMALINK',
	),
	'shr-propeller'=>array(
		'check'=>sprintf($checkthis_text,'Propeller'),
		'share'=>__('Submit this story to ', 'shrsb').'Propeller',
		'baseUrl'=>'http://www.propeller.com/submit/?url=PERMALINK',
	),
	'shr-faqpal'=>array(
		'check'=>sprintf($checkthis_text,'FAQpal'),
		'share'=>__('Submit this to ', 'shrsb').'FAQpal',
		'baseUrl'=>'http://www.faqpal.com/submit?url=PERMALINK',
	),
	'shr-evernote'=>array(
		'check'=>sprintf($checkthis_text,'Evernote'),
		'share'=>__('Clip this to ', 'shrsb').'Evernote',
		'baseUrl'=>'http://www.evernote.com/clip.action?url=PERMALINK&amp;title=TITLE',
	),
	'shr-meneame'=>array(
		'check'=>sprintf($checkthis_text,'Meneame').__(' (Spanish)', 'shrsb'),
		'share'=>__('Submit this to ', 'shrsb').'Meneame',
		'baseUrl'=>'http://meneame.net/submit.php?url=PERMALINK',
	),
	'shr-bitacoras'=>array(
		'check'=>sprintf($checkthis_text,'Bitacoras').__(' (Spanish)', 'shrsb'),
		'share'=>__('Submit this to ', 'shrsb').'Bitacoras',
		'baseUrl'=>'http://bitacoras.com/anotaciones/PERMALINK',
	),
	'shr-jumptags'=>array(
		'check'=>sprintf($checkthis_text,'JumpTags'),
		'share'=>__('Submit this link to ', 'shrsb').'JumpTags',
		'baseUrl'=>'http://www.jumptags.com/add/?url=PERMALINK&amp;title=TITLE',
	),
	'shr-bebo'=>array(
		'check'=>sprintf($checkthis_text,'Bebo'),
		'share'=>__('Share this on ', 'shrsb').'Bebo',
		'baseUrl'=>'http://www.bebo.com/c/share?Url=PERMALINK&amp;Title=TITLE',
	),
	'shr-n4g'=>array(
		'check'=>sprintf($checkthis_text,'N4G'),
		'share'=>__('Submit tip to ', 'shrsb').'N4G',
		'baseUrl'=>'http://www.n4g.com/tips.aspx?url=PERMALINK&amp;title=TITLE',
	),
	'shr-strands'=>array(
		'check'=>sprintf($checkthis_text,'Strands'),
		'share'=>__('Submit this to ', 'shrsb').'Strands',
		'baseUrl'=>'http://www.strands.com/tools/share/webpage?title=TITLE&amp;url=PERMALINK',
	),
	'shr-orkut'=>array(
		'check'=>sprintf($checkthis_text,'Orkut'),
		'share'=>__('Promote this on ', 'shrsb').'Orkut',
		'baseUrl'=>'http://promote.orkut.com/preview?nt=orkut.com&amp;tt=TITLE&amp;du=PERMALINK&amp;cn=POST_SUMMARY',
	),
	'shr-tumblr'=>array(
		'check'=>sprintf($checkthis_text,'Tumblr'),
		'share'=>__('Share this on ', 'shrsb').'Tumblr',
		'baseUrl'=>'http://www.tumblr.com/share?v=3&amp;u=PERMALINK&amp;t=TITLE',
	),
	'shr-stumpedia'=>array(
		'check'=>sprintf($checkthis_text,'Stumpedia'),
		'share'=>__('Add this to ', 'shrsb').'Stumpedia',
		'baseUrl'=>'http://www.stumpedia.com/submit?url=PERMALINK&amp;title=TITLE',
	),
	'shr-current'=>array(
		'check'=>sprintf($checkthis_text,'Current'),
		'share'=>__('Post this to ', 'shrsb').'Current',
		'baseUrl'=>'http://current.com/clipper.htm?url=PERMALINK&amp;title=TITLE',
	),
	'shr-blogger'=>array(
		'check'=>sprintf($checkthis_text,'Blogger'),
		'share'=>__('Blog this on ', 'shrsb').'Blogger',
		'baseUrl'=>'http://www.blogger.com/blog_this.pyra?t&amp;u=PERMALINK&amp;n=TITLE&amp;pli=1',
	),
	'shr-plurk'=>array(
		'check'=>sprintf($checkthis_text,'Plurk'),
		'share'=>__('Share this on ', 'shrsb').'Plurk',
		'baseUrl'=>'http://www.plurk.com/m?content=TITLE+-+PERMALINK&amp;qualifier=shares',
	),
	'shr-dzone'=>array(
		'check'=>sprintf($checkthis_text,'DZone'),
		'share'=>__('Add this to ', 'shrsb').'DZone',
		'baseUrl'=>'http://www.dzone.com/links/add.html?url=PERMALINK&amp;title=TITLE&amp;description=POST_SUMMARY',
	),	
	'shr-kaevur'=>array(
		'check'=>sprintf($checkthis_text,'Kaevur').__(' (Estonian)', 'shrsb'),
		'share'=>__('Share this on ', 'shrsb').'Kaevur',
		'baseUrl'=>'http://kaevur.com/submit.php?url=PERMALINK',
	),
	'shr-virb'=>array(
		'check'=>sprintf($checkthis_text,'Virb'),
		'share'=>__('Share this on ', 'shrsb').'Virb',
		'baseUrl'=>'http://virb.com/share?external&amp;v=2&amp;url=PERMALINK&amp;title=TITLE',
	),	
	'shr-boxnet'=>array(
		'check'=>sprintf($checkthis_text,'Box.net'),
		'share'=>__('Add this link to ', 'shrsb').'Box.net',
		'baseUrl'=>'https://www.box.net/api/1.0/import?url=PERMALINK&amp;name=TITLE&amp;description=POST_SUMMARY&amp;import_as=link',
	),
	'shr-oknotizie'=>array(
		'check'=>sprintf($checkthis_text,'OkNotizie').__('(Italian)', 'shrsb'),
		'share'=>__('Share this on ', 'shrsb').'OkNotizie',
		'baseUrl'=>'http://oknotizie.virgilio.it/post?url=PERMALINK&amp;title=TITLE',
	),
	'shr-bonzobox'=>array(
		'check'=>sprintf($checkthis_text,'BonzoBox'),
		'share'=>__('Add this to ', 'shrsb').'BonzoBox',
		'baseUrl'=>'http://bonzobox.com/toolbar/add?pop=1&amp;u=PERMALINK&amp;t=TITLE&amp;d=POST_SUMMARY',
	),
	'shr-plaxo'=>array(
		'check'=>sprintf($checkthis_text,'Plaxo'),
		'share'=>__('Share this on ', 'shrsb').'Plaxo',
		'baseUrl'=>'http://www.plaxo.com/?share_link=PERMALINK',
	),
	'shr-springpad'=>array(
		'check'=>sprintf($checkthis_text,'SpringPad'),
		'share'=>__('Spring this on ', 'shrsb').'SpringPad',
		'baseUrl'=>'http://springpadit.com/clip.action?body=POST_SUMMARY&amp;url=PERMALINK&amp;format=microclip&amp;title=TITLE&amp;isSelected=true',
	),
	'shr-zabox'=>array(
		'check'=>sprintf($checkthis_text,'Zabox'),
		'share'=>__('Box this on ', 'shrsb').'Zabox',
		'baseUrl'=>'http://www.zabox.net/submit.php?url=PERMALINK',
	),
	'shr-viadeo'=>array(
		'check'=>sprintf($checkthis_text,'Viadeo'),
		'share'=>__('Share this on ', 'shrsb').'Viadeo',
		'baseUrl'=>'http://www.viadeo.com/shareit/share/?url=PERMALINK&amp;title=TITLE&amp;urlaffiliate=31138',
	),
	'shr-gmail'=>array(
		'check'=>sprintf($checkthis_text,'Gmail'),
		'share'=>__('Email this via ', 'shrsb').'Gmail',
		'baseUrl'=>'https://mail.google.com/mail/?ui=2&amp;view=cm&amp;fs=1&amp;tf=1&amp;su=TITLE&amp;body=Link: PERMALINK '.__('(sent via shareaholic)', 'shrsb').'%0D%0A%0D%0A----%0D%0A POST_SUMMARY',
	),
	'shr-hotmail'=>array(
		'check'=>sprintf($checkthis_text,'Hotmail'),
		'share'=>__('Email this via ', 'shrsb').'Hotmail',
		'baseUrl'=>'http://mail.live.com/?rru=compose?subject=TITLE&amp;body=Link: PERMALINK '.__('(sent via shareaholic)', 'shrsb').'%0D%0A%0D%0A----%0D%0A POST_SUMMARY',
	),
	'shr-yahoomail'=>array(
		'check'=>sprintf($checkthis_text,'Yahoo! Mail'),
		'share'=>__('Email this via ', 'shrsb').'Yahoo! Mail',
		'baseUrl'=>'http://compose.mail.yahoo.com/?Subject=TITLE&amp;body=Link: PERMALINK '.__('(sent via shareaholic)', 'shrsb').'%0D%0A%0D%0A----%0D%0A POST_SUMMARY',
	),
	'shr-buzzster'=>array(
		'check'=>sprintf($checkthis_text,'Buzzster!'),
		'share'=>__('Share this via ', 'shrsb').'Buzzster!',
		'baseUrl'=>"javascript:var%20s=document.createElement('script');s.src='http://www.buzzster.com/javascripts/bzz_adv.js';s.type='text/javascript';void(document.getElementsByTagName('head')[0].appendChild(s));",
	),
);
ksort($shrsb_bookmarks_data, SORT_STRING); //sort array by keys
?>