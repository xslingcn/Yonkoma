<?php
/*
Pixmicat! : 圖咪貓貼圖版程式
http://pixmicat.openfoundry.org/
版權所有 © 2005-2015 Pixmicat! Development Team

版權聲明：
此程式是基於レッツPHP!<http://php.s3.to/>的gazou.php、
双葉ちゃん<http://www.2chan.net>的futaba.php所改寫之衍生著作程式，屬於自由軟體，
以Artistic License 2.0作為發佈授權條款。
您可以遵照Artistic License 2.0來自由使用、散播、修改或製成衍生著作。
更詳細的條款及定義請參考隨附"LICENSE"條款副本。

發佈這一程式的目的是希望它有用，但沒有任何擔保，甚至沒有適合特定目的而隱含的擔保。
關於此程式相關的問題請不要詢問レッツPHP!及双葉ちゃん。

如果您沒有隨著程式收到一份Artistic License 2.0副本，
請瀏覽http://pixmicat.openfoundry.org/license/以取得一份。

"Pixmicat!", "Pixmicat", 及"圖咪貓"是Pixmicat! Development Team的商標。
*/
/*---- Part 1：程式基本設定 ----*/
// 伺服器常態設定
require_once __DIR__. '/vendor/autoload.php';

    
if (!defined('DEBUG')) {
    define("DEBUG", false); // 是否產生詳細 DEBUG 訊息
}
define("ROOTPATH", dirname(__FILE__).DIRECTORY_SEPARATOR); // 主程式根目錄
$config['path'] = array(
    'root' => dirname(__FILE__).DIRECTORY_SEPARATOR
);

define("STORAGE_PATH", ROOTPATH); // 圖檔、快取儲存目錄 (需具有讀寫權限 777)
define("TIME_ZONE", '+8'); // 時區設定 (GMT時區，參照 http://wwp.greenwichmeantime.com/ )
define("PIXMICAT_LANGUAGE", 'zh_TW'); // 語系語定
define("HTTP_UPLOAD_DIFF", 50); // HTTP上傳所有位元組與實際位元組之允許誤差值
ini_set("memory_limit", '128M'); // PHP運行的最大記憶體使用量 (php內定128M/無限:-1)

// FileIO設定
define("FILEIO_BACKEND", 'normal'); // FileIO後端指定 (local, normal, ftp)
define("FILEIO_PARAMETER", ''); // FileIO參數 (本機端儲存)
//define("FILEIO_PARAMETER", serialize(array('ftp.example.com', 21, 'demo', 'demo', 'PASV', '/pwd/', 'http://www.example.com/~demo/pwd/', true))); // FileIO參數 (FTP)
//define("FILEIO_PARAMETER", serialize(array('00000000000000000000000000000000'))); // FileIO參數 (ImageShack)
//define("FILEIO_PARAMETER", serialize(array('http://www.example.com/~demo/satellite.cgi', true, '12345678', 'http://www.example.com/~demo/src/', true))); // FileIO參數 (Satellite)

// 資料庫設定
$config['db'] = array(
    'type' => 'sqlite', // 資料庫 engine
    //'user' => '',
    //'password' => '',
    //'server' => '',
    'db_folder' => 'database', // 放資料庫的資料夾
    'database' => 'yonkoma.db3', // 主資料庫
);
$config['path'] = array(
	'root' => dirname(__FILE__).DIRECTORY_SEPARATOR,
);
$config['url'] = array(
    'base' => '',
    'skip_segments' => 0, // 在 domain 底下幾層資料夾，例如 php 位置如果在 http://localhost/test/ 底下則 skip 要設為 1
    'script' => 'pixmicat.php',
);
$config['security'] = array(
    'idseed' => 'id種', // 生成ID之隨機種子
);
$config['boards'] = array(
	'00' => array(
		'title' => '綜合'
	),
);

/*---- Part 2：板面各項細部功能設定 ----*/
define("IMG_DIR", STORAGE_PATH . 'src/'); // 圖片存放目錄
define("THUMB_DIR", STORAGE_PATH . 'thumb/'); // 預覽圖存放目錄
define("PHP_SELF2", 'index.htm'); // 入口檔名
define("PHP_EXT", '.htm'); // 第一頁以後生成檔案之副檔名
define("TITLE", 'Pixmicat!-PIO'); // 網頁標題
define("HOME", '../'); // 回首頁的連結
define("TOP_LINKS", ''); // 頁面右上方的額外連結，請直接以[<a href="網址" target="_blank" rel="noreferrer">名稱</a>]格式鍵入，如果不需要開新視窗可刪除target一段
define("ADMIN_HASH", 'TO-BE-COMPUTED-BY-GENHASH'); // 管理者密碼 (Hash 加入 Salt)
define("IDSEED", 'id種'); // 生成ID之隨機種子

// 管理員キャップ(Cap)設定 (啟用條件：開啟使用；名稱輸入識別名稱，E-mail輸入#啟動密碼)
define("CAP_ENABLE", 1); // 是否使用管理員キャップ (使用：1 不使用：0)
define("CAP_NAME", 'futaba'); // 管理員キャップ識別名稱
define("CAP_PASS", 'futaba'); // 管理員キャップ啟動密碼 (在E-mail一欄輸入#啟動密碼)
define("CAP_SUFFIX", ' ★'); // 管理員キャップ後綴字元 (請務必有★以便程式防止偽造，或可自行修改程式的防偽造部份)
define("CAP_ISHTML", 1); // 管理員キャップ啟動後內文是否接受HTML標籤 (是：1 否：0)

// 功能切換
define("USE_SEARCH", 1); // 開放搜尋功能 (是：1 否：0)
define("USE_UPSERIES", 1); // 是否啟用連貼機能 [開主題後自動指向到主題下以方便連貼] (是：1 否：0)
define("RESIMG", 1); // 回應附加圖檔機能 (開啟：1 關閉：0)
define("AUTO_LINK", 1); // 討論串文字內的URL是否自動作成超連結 (是：1 否：0)
define("KILL_INCOMPLETE_UPLOAD", 1); // 自動刪除上傳不完整附加圖檔 (是：1 否：0)
define("ALLOW_NONAME", 1); // 是否接受匿名發送 (強制砍名：2 是：1 否：0)
define("DISP_ID", 2); // 顯示ID (強制顯示：2 選擇性顯示：1 永遠不顯示：0)
define("CLEAR_SAGE", 0); // 使用不推文模式時清除E-mail中的「sage」關鍵字 (是：1 否：0)
define("USE_QUOTESYSTEM", 1); // 是否打開引用瀏覽系統 [自動轉換>>No.xxx文字成連結並導引] (是：1 否：0)
define("SHOW_IMGWH", 1); // 是否顯示附加圖檔之原檔長寬尺寸 (是：1 否：0)
define("USE_RE_CACHE", 1); // 是否使用回應頁面顯示快取功能 (是：1 否：0)
define("TRUST_HTTP_X_FORWARDED_FOR", 0); // 是否利用HTTP_X_FORWARDED_FOR抓取Proxy後的真實IP。注意檔頭可能被偽造，若無特別需要請勿開啟 (是：1 否：0)
$PROXYHEADERlist=array(//如啓用TRUST_HTTP_X_FORWARDED_FOR，我們將相信的Header,越上越優先相信。
        'HTTP_CLIENT_IP',
        'HTTP_X_REAL_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED');

// 模組載入
$ModuleList = array();
$ModuleList[] = 'mod_ajax';
//$ModuleList[] = 'mod_rss';
//$ModuleList[] = 'mod_catalog';

// 封鎖設定
define("BAN_CHECK", 0); // 綜合性封鎖檢查功能 (關閉：0, 開啟：1)
$BANPATTERN = array(); // IP/Hostname封鎖黑名單
$DNSBLservers = array(0, 'sbl-xbl.spamhaus.org', 'list.dsbl.org', 'bl.blbl.org', 'bl.spamcop.net'); // DNSBL伺服器列表 (首項：使用伺服器個數)
$DNSBLWHlist = array(); // DNSBL白名單 (請輸入IP位置)
$BAD_STRING = array("dummy_string","dummy_string2"); // 限制出現之文字
$BAD_FILEMD5 = array("dummy","dummy2"); // 限制上傳附加圖檔之MD5檢查碼

// 附加圖檔限制
define("MAX_KB", 2000); // 附加圖檔上傳容量限制KB (php內定為最高2MB)
define("ALLOW_UPLOAD_EXT", 'GIF|JPG|JPEG|PNG|BMP|SWF|WEBM'); // 接受之附加圖檔副檔名 (送出前表單檢查用，用 | 分隔)
define("USE_WEBM", 1); //開啟WEBM上傳
define("ALLOW_WEBM_AUDIO", 1); //WEBM可不可以有音軌

// 連續投稿時間限制
define("RENZOKU", 60); // 連續投稿間隔秒數
define("RENZOKU2", 60); // 連續貼圖間隔秒數

// 預覽圖片相關限制
define("USE_THUMB", 1); // 使用預覽圖機能 (使用：1 不使用：0) [gd, imagemagick, imagick, magickwand, repng2jpeg]
define("MAX_W", 250); // 討論串本文預覽圖片寬度 (超過則自動縮小)
define("MAX_H", 250); // 討論串本文預覽圖片高度
define("MAX_RW", 125); // 討論串回應預覽圖片寬度 (超過則自動縮小)
define("MAX_RH", 125); // 討論串回應預覽圖片高度
$THUMB_SETTING = array( // 預覽圖生成設定
    'Format' => 'jpg',
    'Quality' => 75
);

// 外觀設定
$ADDITION_INFO = ""; // 可在表單下顯示額外文字
$LIMIT_SENSOR = array('ByPostCountCondition'=>500); // 文章自動刪除機制設定
define("PAGE_DEF", 15); // 一頁顯示幾篇討論串
define("ADMIN_PAGE_DEF", 20); // 管理模式下，一頁顯示幾筆資料
define("RE_DEF", 10); // 一篇討論串最多顯示之回應筆數 (超過則自動隱藏，全部隱藏：0)
define("MAX_RES", 30); // 回應筆數超過多少則不自動推文 (關閉：0)
define("MAX_AGE_TIME", 0); // 討論串可接受推文的時間範圍 (單位：小時，討論串存在超過此時間則回應皆不再自動推文 關閉：0)
define("COMM_MAX", 2000); // 內文接受字數 (UTF-8)
define("INPUT_MAX", 100); // 除了內文外其他欄位的字數上限
define("BR_CHECK", 0); // 文字換行行數上限 (不限：0)
define("STATIC_HTML_UNTIL", 10); // 更新文章時自動生成的靜態網頁至第幾頁止 (全部生成：-1 僅入口頁：0)
define("DEFAULT_NOTITLE", '無標題'); // 預設文章標題
define("DEFAULT_NONAME", '無名氏'); // 預設文章名稱
define("DEFAULT_NOCOMMENT", '無內文'); // 預設文章內文

/*---- Part 3：Anti-SPAM 防止垃圾訊息機器人發文 ----*/
/* 欄位陷阱 (Field Trap)
介紹：
機器人會針對常見的欄位名稱送出垃圾資料，將這些常見的欄位製成陷阱，
另設名稱怪異的欄位為正確欄位，以避免直接的攻擊。
防止機器人學習的可能，請隔一段時間修改底下欄位值，建議英數大小寫隨機6～10個 (避免特殊符號、第一位不能為數字)。
*/
define("FT_NAME", 'bvUFbdrIC'); // 名稱欄位
define("FT_EMAIL", 'ObHGyhdTR'); // E-mail欄位
define("FT_SUBJECT", 'SJBgiFbhj'); // 標題欄位
define("FT_COMMENT", 'pOBvrtyJK'); // 內文欄位
