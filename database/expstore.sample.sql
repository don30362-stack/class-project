-- Public sample database for Home Fitness.
-- Contains schema and catalog/location seed data only; member, address, cart, order, and admin rows are excluded.

CREATE DATABASE IF NOT EXISTS `expstore` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `expstore`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2026-09-12 15:58:27
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `expstore`
--

-- --------------------------------------------------------

--
-- 資料表結構 `addbook`
--

CREATE TABLE `addbook` (
  `addressid` int(10) NOT NULL COMMENT '地址ID',
  `setdefault` tinyint(1) NOT NULL DEFAULT 0 COMMENT '預設收件人',
  `emailid` int(10) NOT NULL COMMENT '會員編號',
  `cname` varchar(30) NOT NULL COMMENT '收件者姓名',
  `mobile` varchar(20) NOT NULL COMMENT '收件者電話',
  `myZip` varchar(10) DEFAULT NULL COMMENT '郵遞區號',
  `address` varchar(200) NOT NULL COMMENT '收件地址',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `addbook`
--


-- --------------------------------------------------------

--
-- 資料表結構 `admin`
--

CREATE TABLE `admin` (
  `aid` varchar(4) NOT NULL,
  `aname` varchar(20) NOT NULL,
  `passwd` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 傾印資料表的資料 `admin`
--


-- --------------------------------------------------------

--
-- 資料表結構 `carousel`
--

CREATE TABLE `carousel` (
  `caro_id` int(3) NOT NULL COMMENT '輪播編號',
  `caro_title` varchar(50) DEFAULT NULL COMMENT '輪播標題',
  `caro_content` varchar(100) DEFAULT NULL COMMENT '輪播內容介紹',
  `caro_online` tinyint(1) NOT NULL DEFAULT 1 COMMENT '上下架',
  `caro_sort` int(3) NOT NULL COMMENT '輪播排序',
  `caro_pic` varchar(50) NOT NULL COMMENT '輪播圖檔名稱',
  `p_id` int(10) NOT NULL COMMENT '產品編號',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `carousel`
--

INSERT INTO `carousel` (`caro_id`, `caro_title`, `caro_content`, `caro_online`, `caro_sort`, `caro_pic`, `p_id`, `create_date`) VALUES
(1, 'HOME FIT 24KG 調節式啞鈴', '預購-【HOME FIT 24KG 調節式啞鈴】贈專用0.5kg鋼片(NT$1280)', 1, 1, 'carousel-WT-ADB-001_01.png', 1, '2026-08-10 08:10:08'),
(2, 'HOME FIT 40KG 調節式啞鈴', '【HOME FIT 40KG 調節式啞鈴】組合滿2萬贈磁吸式配重塊', 1, 2, 'carousel-WT-ADB-002_01.png', 2, '2026-08-10 08:11:08'),
(3, 'HOME FIT 磁控健身車', '【HOME FIT 磁控健身車】 送延長保固', 1, 3, 'carousel-CD-BIK-001_01.png', 7, '2026-08-10 08:11:51');

-- --------------------------------------------------------

--
-- 資料表結構 `cart`
--

CREATE TABLE `cart` (
  `cartid` int(10) NOT NULL COMMENT '購物車編號',
  `emailid` int(10) DEFAULT NULL COMMENT '會員編號',
  `p_id` int(10) NOT NULL COMMENT '產品編號',
  `qty` int(3) NOT NULL COMMENT '產品數量',
  `orderid` varchar(30) DEFAULT NULL COMMENT '訂單編號',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '訂單處理狀態',
  `ip` varchar(200) NOT NULL COMMENT '訂購者的IP',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '加入購物車時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `cart`
--


-- --------------------------------------------------------

--
-- 資料表結構 `city`
--

CREATE TABLE `city` (
  `AutoNo` int(10) NOT NULL COMMENT '城市編號',
  `Name` varchar(150) NOT NULL COMMENT '城市名稱',
  `cityOrder` tinyint(2) NOT NULL COMMENT '標記',
  `State` smallint(6) NOT NULL COMMENT '狀態'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `city`
--

INSERT INTO `city` (`AutoNo`, `Name`, `cityOrder`, `State`) VALUES
(1, '臺北市', 0, 0),
(2, '基隆市', 0, 0),
(3, '新北市', 0, 0),
(4, '宜蘭縣', 0, 0),
(5, '新竹市', 0, 0),
(6, '新竹縣', 0, 0),
(7, '桃園市', 0, 0),
(8, '苗栗縣', 0, 0),
(9, '台中市', 0, 0),
(10, '彰化縣', 0, 0),
(11, '南投縣', 0, 0),
(12, '雲林縣', 0, 0),
(13, '嘉義市', 0, 0),
(14, '嘉義縣', 0, 0),
(15, '台南市', 0, 0),
(16, '高雄市', 0, 0),
(17, '南海諸島', 0, 0),
(18, '澎湖縣', 0, 0),
(19, '屏東縣', 0, 0),
(20, '台東縣', 0, 0),
(21, '花蓮縣', 0, 0),
(22, '金門縣', 0, 0),
(23, '連江縣', 0, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `hot`
--

CREATE TABLE `hot` (
  `h_id` int(3) NOT NULL COMMENT '熱銷商品流水號',
  `p_id` int(10) NOT NULL COMMENT '產品編號',
  `h_sort` int(3) DEFAULT NULL COMMENT '熱銷商品排名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `hot`
--

INSERT INTO `hot` (`h_id`, `p_id`, `h_sort`) VALUES
(1, 1, 1),
(2, 3, 2),
(3, 5, 3),
(4, 7, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `member`
--

CREATE TABLE `member` (
  `emailid` int(11) NOT NULL COMMENT 'email流水號',
  `email` varchar(100) NOT NULL COMMENT 'email帳號',
  `pw1` varchar(50) NOT NULL COMMENT '密碼',
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否啟動',
  `cname` varchar(30) NOT NULL COMMENT '中文姓名',
  `tssn` varchar(20) NOT NULL COMMENT '身份證字號',
  `birthday` date NOT NULL COMMENT '生日',
  `imgname` text DEFAULT NULL COMMENT '相片檔名',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `member`
--


-- --------------------------------------------------------

--
-- 資料表結構 `multiselect`
--

CREATE TABLE `multiselect` (
  `msid` int(5) NOT NULL COMMENT '多功能選擇ID',
  `mslevel` int(2) NOT NULL COMMENT '多功能選擇層級',
  `msuplink` int(4) NOT NULL COMMENT '上層連結',
  `opcode` varchar(10) DEFAULT NULL COMMENT '外掛參數',
  `msname` varchar(50) NOT NULL COMMENT '多功能選擇名稱',
  `msort` int(11) DEFAULT NULL COMMENT '各功能列表排序',
  `url1` varchar(200) DEFAULT NULL COMMENT '外掛網址1',
  `url2` varchar(200) DEFAULT NULL COMMENT '外掛網址2',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立日期',
  `update_date` varchar(50) DEFAULT NULL COMMENT '修改日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `multiselect`
--

INSERT INTO `multiselect` (`msid`, `mslevel`, `msuplink`, `opcode`, `msname`, `msort`, `url1`, `url2`, `create_date`, `update_date`) VALUES
(1, 1, 0, NULL, '付款方式', 0, NULL, NULL, '2023-08-11 09:46:53', '2023-08-17 03:42:28'),
(2, 1, 0, NULL, '訂單處理狀態', 0, NULL, NULL, '2023-08-11 09:52:29', '2023-08-17 03:42:41'),
(3, 2, 1, NULL, '貨到付款', 1, NULL, NULL, '2023-08-11 09:55:45', '2023-08-17 03:43:37'),
(4, 2, 1, NULL, '信用卡付款', 2, NULL, NULL, '2023-08-11 09:55:45', '2023-08-17 03:43:54'),
(5, 2, 1, NULL, '銀行轉帳', 3, NULL, NULL, '2023-08-11 09:55:45', '2023-08-17 03:44:37'),
(6, 2, 1, NULL, '電子支付', 4, NULL, NULL, '2023-08-11 09:55:45', '2023-08-17 03:44:51'),
(7, 2, 2, NULL, '處理中', 1, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 03:45:03'),
(8, 2, 2, NULL, '待出貨', 2, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 03:45:32'),
(9, 2, 2, NULL, '運送中', 3, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 03:45:45'),
(10, 2, 2, NULL, '收貨完成', 4, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 03:46:10'),
(11, 2, 2, NULL, '退貨中', 5, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 03:46:24'),
(12, 2, 2, NULL, '已關閉訂單', 6, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 03:46:35'),
(13, 2, 2, NULL, '無效訂單', 7, NULL, NULL, '2023-08-11 10:06:42', '2023-08-17 05:39:26'),
(14, 2, 2, NULL, '訂單確認', 8, NULL, NULL, '2023-08-18 06:13:47', '2023-10-23 12:37:36'),
(15, 2, 2, NULL, '平台出貨', 9, NULL, NULL, '2023-08-18 06:13:47', '2023-10-25 08:29:31'),
(34, 1, 0, NULL, '付款處理狀態', 0, NULL, NULL, '2023-08-11 09:52:29', '2023-08-17 03:42:41'),
(35, 2, 34, NULL, '侍貨到付款', 1, NULL, NULL, '2023-08-11 09:55:45', '2023-10-25 08:39:12'),
(36, 2, 34, NULL, '完成付款', 2, NULL, NULL, '2023-08-11 09:55:45', '2023-10-25 08:39:12'),
(37, 2, 34, NULL, '未完成付款', 3, NULL, NULL, '2023-08-11 09:55:45', '2023-10-25 08:39:12'),
(38, 2, 34, NULL, '貨到付款已完成', 4, NULL, NULL, '2023-08-11 09:55:45', '2023-10-25 08:39:12');

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `p_id` int(10) NOT NULL COMMENT '產品編號',
  `classid` int(3) NOT NULL COMMENT '產品類別',
  `p_name` varchar(200) NOT NULL COMMENT '產品名稱',
  `p_intro` varchar(200) DEFAULT NULL COMMENT '產品簡介',
  `p_price` int(11) DEFAULT NULL COMMENT '產品單價',
  `p_open` tinyint(1) NOT NULL DEFAULT 1 COMMENT '上架',
  `p_content` text DEFAULT NULL COMMENT '產品詳細規格',
  `p_date` timestamp NULL DEFAULT current_timestamp() COMMENT '產品輸入日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `product`
--

INSERT INTO `product` (`p_id`, `classid`, `p_name`, `p_intro`, `p_price`, `p_open`, `p_content`, `p_date`) VALUES
(1, 5, 'HOME FIT 24KG 調節式啞鈴', '快速切換重量，節省居家收納空間，適合胸、背、肩、手臂與腿部等全身訓練。\r\n', 8980, 1, '<section class=\"product-detail-section\">\r\n                            <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n                            <div class=\"product-detail-description\">\r\n                                <p>\r\n                                    HOME FIT 24KG 調節式啞鈴專為居家重量訓練設計，將多種重量整合於單一啞鈴之中，可依照不同訓練動作及個人能力調整重量。\r\n                                </p>\r\n\r\n                                <p>\r\n                                    無論是胸推、肩推、划船、二頭彎舉、深蹲或弓箭步，都能透過重量調整安排不同強度的訓練。相較傳統多組固定式啞鈴，可有效減少器材占用空間，更適合居家健身環境使用。\r\n                                </p>\r\n                            </div>\r\n                        </section>\r\n\r\n\r\n                        <!-- 詳細介紹圖片 -->\r\n                        <section class=\"product-detail-section product-detail-image\">\r\n                            <img\r\n                                src=\"product_img/WT-ADB-001_intro_01.png\"\r\n                                alt=\"HOME FIT 24KG 調節式啞鈴\"\r\n                                class=\"img-fluid\">\r\n                        </section>\r\n\r\n\r\n                        <!-- 產品特色 -->\r\n                        <section class=\"product-detail-section\">\r\n                            <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n                            <ul class=\"product-feature-list\">\r\n                                <li>最大重量 24KG，適合多種居家重量訓練</li>\r\n                                <li>可依訓練需求快速調整重量</li>\r\n                                <li>一組取代多組傳統固定式啞鈴</li>\r\n                                <li>節省居家健身器材收納空間</li>\r\n                                <li>適用胸、背、肩、手臂及腿部等多種訓練</li>\r\n                            </ul>\r\n                        </section>\r\n\r\n\r\n                        <!-- 商品規格 -->\r\n                        <section class=\"product-detail-section\">\r\n                            <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n                            <div class=\"table-responsive\">\r\n                                <table class=\"product-spec-table\">\r\n                                    <tbody>\r\n                                        <tr>\r\n                                            <th>品牌</th>\r\n                                            <td>HOME FIT</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>商品類型</th>\r\n                                            <td>調節式啞鈴</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>重量範圍</th>\r\n                                            <td>3－24 KG</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>重量段數</th>\r\n                                            <td>8 段</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>主要材質</th>\r\n                                            <td>鑄鐵、尼龍塑料、鋼製握把</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>商品尺寸</th>\r\n                                            <td>約 42 × 21 × 22 cm</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>底座尺寸</th>\r\n                                            <td>約 45 × 23 × 6 cm</td>\r\n                                        </tr>\r\n\r\n                                        <tr>\r\n                                            <th>保固期限</th>\r\n                                            <td>1 年</td>\r\n                                        </tr>\r\n                                    </tbody>\r\n                                </table>\r\n                            </div>\r\n                        </section>', '2026-08-11 01:01:07'),
(2, 5, 'HOME FIT 40KG 調節式啞鈴\r\n', '提供更高負重範圍與多段重量選擇，適合已有訓練基礎者進行進階肌力訓練。\r\n', 12800, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 40KG 調節式啞鈴提供更高的重量範圍，適合已有重量訓練基礎，希望進一步提升訓練強度的使用者。\r\n        </p>\r\n\r\n        <p>\r\n            透過調節式重量設計，可依照不同肌群及訓練動作彈性調整負重。從手臂訓練到胸推、划船及下肢動作，都能利用單一器材完成漸進式重量訓練。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/WT-ADB-002_intro_01.png\" alt=\"HOME FIT 40KG 調節式啞鈴\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>最大重量達 40KG</li>\r\n        <li>適合中高強度居家重量訓練</li>\r\n        <li>多段重量調整，方便安排漸進式訓練</li>\r\n        <li>減少大量固定式啞鈴所需空間</li>\r\n        <li>可應用於全身多肌群訓練</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>調節式啞鈴</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>重量範圍</th>\r\n                    <td>5－40 KG</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>重量段數</th>\r\n                    <td>16 段</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>主要材質</th>\r\n                    <td>鑄鐵、尼龍塑料、高強度尼龍</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品尺寸</th>\r\n                    <td>約 48 × 24 × 25 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>底座尺寸</th>\r\n                    <td>約 51 × 27 × 7 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:02:03'),
(3, 6, 'HOME FIT 20KG 調節式槓鈴組', '配重可依需求自由調整，適合硬舉、划船、深蹲與肩推等多種居家重量訓練。', 6980, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 20KG 調節式槓鈴組適合剛開始建立居家重量訓練習慣的使用者，可依照不同訓練需求自由配置槓片重量。\r\n        </p>\r\n\r\n        <p>\r\n            槓鈴可應用於深蹲、硬舉、划船、肩推及臥推等多種複合式動作，一組器材即可涵蓋上半身與下半身的基礎肌力訓練。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/WT-ABB-001_intro_01.png\" alt=\"HOME FIT 20KG 調節式槓鈴組\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>20KG 重量配置</li>\r\n        <li>槓片可依訓練需求自由調整</li>\r\n        <li>適合居家入門重量訓練</li>\r\n        <li>可進行多種全身性複合動作</li>\r\n        <li>拆卸後方便整理與收納</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>調節式槓鈴組</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>重量範圍</th>\r\n                    <td>20 KG</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>槓片配置</th>\r\n                    <td>1.5 KG × 4、3 KG × 4</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>槓鈴桿長度</th>\r\n                    <td>約 140 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>槓片材質</th>\r\n                    <td>水泥混合填充＋PE 外殼</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>固定方式</th>\r\n                    <td>螺旋式安全鎖扣</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:03:07'),
(4, 6, 'HOME FIT 30KG 調節式槓鈴組\r\n', '提升負重範圍與訓練彈性，適合需要更高阻力的全身肌力訓練。\r\n', 9680, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 30KG 調節式槓鈴組提供更充足的重量配置，適合希望逐步提升肌力及增加訓練負荷的居家健身使用者。\r\n        </p>\r\n\r\n        <p>\r\n            透過不同槓片組合，可依個人能力調整負重，進行深蹲、硬舉、臥推、肩推與划船等訓練，建立更加完整的自由重量訓練內容。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/WT-ABB-002_intro_01.png\" alt=\"HOME FIT 30KG 調節式槓鈴組\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>30KG 重量配置</li>\r\n        <li>可自由增減槓片調整訓練重量</li>\r\n        <li>適合全身肌力與重量訓練</li>\r\n        <li>支援多種槓鈴複合式動作</li>\r\n        <li>適合居家進階重量訓練使用</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>調節式槓鈴組</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>重量範圍</th>\r\n                    <td>30 KG</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>槓片配置</th>\r\n                    <td>2.5 KG × 4、3.75 KG × 4</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>槓鈴桿長度</th>\r\n                    <td>約 160 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>槓片材質</th>\r\n                    <td>鑄鐵＋橡膠包覆</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>固定方式</th>\r\n                    <td>快拆式安全鎖扣</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:03:07'),
(5, 7, 'HOME FIT 摺疊跑步機 Lite\r\n', '輕巧摺疊設計，適合居家步行、快走與慢跑，使用後可節省收納空間。\r\n', 18800, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 摺疊跑步機 Lite 以居家日常運動及空間利用為主要設計方向，適合健走、快走與輕度跑步使用。\r\n        </p>\r\n\r\n        <p>\r\n            可摺疊設計讓運動結束後更容易整理及收納，適合房間、公寓及空間有限的居家環境。不受天氣影響，在家即可安排固定的有氧運動時間。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/CD-TRD-001_intro_01.png\" alt=\"HOME FIT 摺疊跑步機 Lite\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>適合健走、快走及居家有氧運動</li>\r\n        <li>可摺疊設計，節省收納空間</li>\r\n        <li>操作簡單，適合日常使用</li>\r\n        <li>居家即可進行規律心肺訓練</li>\r\n        <li>適合入門健身使用者</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>摺疊式電動跑步機</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>速度範圍</th>\r\n                    <td>1－12 km/h</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>馬達規格</th>\r\n                    <td>1.5 HP 持續馬力</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>跑帶尺寸</th>\r\n                    <td>約 110 × 42 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>展開尺寸</th>\r\n                    <td>約 145 × 68 × 120 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>顯示資訊</th>\r\n                    <td>時間、速度、距離、卡路里</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>馬達 2 年／整機 1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:03:51'),
(6, 7, 'HOME FIT 智能跑步機 Pro\r\n', '具備多段速度調整與運動資訊顯示，適合日常有氧、耐力與跑步訓練。\r\n', 28800, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 智能跑步機 Pro 適合希望在家進行完整有氧與跑步訓練的使用者，可依不同體能及訓練計畫調整運動強度。\r\n        </p>\r\n\r\n        <p>\r\n            搭配運動資訊顯示功能，可在訓練過程掌握運動狀態。從日常快走、慢跑到較高強度的跑步訓練，都能依照個人目標安排。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/CD-TRD-002_intro_01.png\" alt=\"HOME FIT 智能跑步機 Pro\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>適合快走、慢跑及跑步訓練</li>\r\n        <li>運動資訊顯示</li>\r\n        <li>多段速度調整</li>\r\n        <li>提供完整居家有氧訓練環境</li>\r\n        <li>Pro 系列適合較高訓練需求</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>智能電動跑步機</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>速度範圍</th>\r\n                    <td>1－18 km/h</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>馬達規格</th>\r\n                    <td>2.5 HP 持續馬力</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>跑帶尺寸</th>\r\n                    <td>約 135 × 50 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>展開尺寸</th>\r\n                    <td>約 175 × 78 × 135 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>顯示資訊</th>\r\n                    <td>時間、速度、距離、坡度、卡路里、心率</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>馬達 3 年／整機 1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:04:10'),
(7, 8, 'HOME FIT 磁控健身車\r\n', '低噪音磁控阻力設計，提供平順踩踏體驗，適合居家日常心肺與燃脂訓練。\r\n', 12800, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 磁控健身車適合在家進行日常有氧及下肢訓練，透過穩定踩踏提升活動量與心肺耐力。\r\n        </p>\r\n\r\n        <p>\r\n            可依照個人體能調整適合的阻力，不論暖身、一般有氧或中低強度耐力訓練皆能使用。固定式運動方式不需要大量活動空間，是居家健身常見且實用的有氧器材。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/CD-BIK-001_intro_01.png\" alt=\"HOME FIT 磁控健身車\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>磁控阻力設計</li>\r\n        <li>可依體能調整運動強度</li>\r\n        <li>適合居家心肺與下肢訓練</li>\r\n        <li>占用空間較小</li>\r\n        <li>適合日常規律有氧運動</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>磁控健身車</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>阻力方式</th>\r\n                    <td>磁控阻力</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>阻力段數</th>\r\n                    <td>8 段</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>座椅調整</th>\r\n                    <td>上下 7 段調整</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>顯示資訊</th>\r\n                    <td>時間、速度、距離、卡路里</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品尺寸</th>\r\n                    <td>約 95 × 52 × 125 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:04:48'),
(8, 8, 'HOME FIT 飛輪健身車\r\n', '穩定飛輪與多段阻力調整，適合高強度間歇、心肺及腿部耐力訓練。\r\n', 16800, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 飛輪健身車適合喜歡較高強度室內騎乘的使用者，可進行穩定有氧、節奏騎乘及間歇訓練。\r\n        </p>\r\n\r\n        <p>\r\n            飛輪結構提供連續順暢的踩踏感，可依照個人體能調整阻力，適合用來提升心肺能力、腿部耐力與整體體能。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/CD-BIK-002_intro_01.png\" alt=\"HOME FIT 飛輪健身車\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>飛輪式騎乘結構</li>\r\n        <li>適合中高強度有氧訓練</li>\r\n        <li>阻力可依訓練需求調整</li>\r\n        <li>強化腿部耐力及心肺能力</li>\r\n        <li>居家即可進行室內單車訓練</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>室內飛輪健身車</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>阻力方式</th>\r\n                    <td>無段式摩擦阻力</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>傳動方式</th>\r\n                    <td>靜音皮帶傳動</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>座椅調整</th>\r\n                    <td>上下、前後可調</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>顯示資訊</th>\r\n                    <td>時間、速度、距離、卡路里、轉速</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品尺寸</th>\r\n                    <td>約 110 × 53 × 115 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:04:48'),
(9, 9, 'HOME FIT 平板健身椅\r\n', '穩固平板結構，適合搭配啞鈴進行臥推、划船、核心與多種基礎訓練。\r\n', 3680, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 平板健身椅提供穩定的水平訓練平台，可搭配啞鈴、槓鈴及其他自由重量器材進行多種肌力訓練。\r\n        </p>\r\n\r\n        <p>\r\n            適合啞鈴臥推、飛鳥、單臂划船及核心訓練等動作。固定式結構操作簡單，是建立居家基礎重量訓練空間的實用器材。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/EQ-BEN-001_intro_01.png\" alt=\"HOME FIT 平板健身椅\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>固定平板式設計</li>\r\n        <li>適合多種自由重量訓練</li>\r\n        <li>穩定支撐訓練姿勢</li>\r\n        <li>可搭配啞鈴與槓鈴使用</li>\r\n        <li>結構簡單，適合居家使用</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>平板健身椅</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>椅背形式</th>\r\n                    <td>固定式平板</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品尺寸</th>\r\n                    <td>約 115 × 48 × 45 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>最大承重</th>\r\n                    <td>250 KG</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>骨架材質</th>\r\n                    <td>加厚鋼管</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>椅墊材質</th>\r\n                    <td>高密度泡棉＋耐磨 PU 皮革</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:05:43'),
(10, 9, 'HOME FIT 可調式健身椅 Pro\r\n', '支援多段椅背角度調整，可進行平板、上斜臥推與肩推等多樣化訓練。\r\n', 5980, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 可調式健身椅 Pro 提供多角度椅背調整，可依照不同重量訓練動作改變支撐角度。\r\n        </p>\r\n\r\n        <p>\r\n            從平板臥推、上斜胸推到坐姿肩推，都能透過角度變化增加訓練內容。搭配啞鈴即可在居家環境完成更多樣化的上半身重量訓練。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/EQ-BEN-002_intro_01.png\" alt=\"HOME FIT 可調式健身椅 Pro\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>多段椅背角度調整</li>\r\n        <li>支援平板、上斜及坐姿訓練</li>\r\n        <li>適合搭配啞鈴進行重量訓練</li>\r\n        <li>一張健身椅提供多種訓練方式</li>\r\n        <li>Pro 系列適合完整居家重訓空間</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>可調式健身椅</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>椅背調整</th>\r\n                    <td>7 段</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>座墊調整</th>\r\n                    <td>3 段</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>椅背角度</th>\r\n                    <td>-15°～85°</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品尺寸</th>\r\n                    <td>約 135 × 55 × 45 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>骨架材質</th>\r\n                    <td>高強度加厚鋼材</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:05:43'),
(11, 10, 'HOME FIT 拼接器材地墊 6入組\r\n', '高密度防震材質，可保護地板並降低器材移動及落地時產生的噪音。\r\n', 1280, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 拼接器材地墊 6 入組適合鋪設於居家健身區域，可依照使用空間自由排列及組合。\r\n        </p>\r\n\r\n        <p>\r\n            地墊能在健身器材與地板之間形成緩衝，適合搭配啞鈴、健身椅、健身車及其他居家訓練設備使用。拼接式設計也方便日後重新配置健身空間。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/EQ-MAT-001_intro_01.png\" alt=\"HOME FIT 拼接器材地墊 6 入組\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>6 片拼接式設計</li>\r\n        <li>可依空間自由排列</li>\r\n        <li>提供器材與地面間的緩衝</li>\r\n        <li>拆裝及收納方便</li>\r\n        <li>適用多種居家健身器材</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>拼接式器材地墊</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>單片尺寸</th>\r\n                    <td>60 × 60 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>單片厚度</th>\r\n                    <td>1.0 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>完整鋪設面積</th>\r\n                    <td>約 2.16 m²</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>主要材質</th>\r\n                    <td>高密度 EVA 發泡材質</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>表面設計</th>\r\n                    <td>防滑紋理</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>清潔方式</th>\r\n                    <td>濕布擦拭後自然風乾</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:06:24'),
(12, 10, 'HOME FIT 加厚防震地墊 12入組\r\n', '加厚緩衝設計，適合較大範圍居家健身空間，可搭配啞鈴、健身椅與其他器材使用。\r\n', 2280, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 加厚防震地墊 12 入組適合較完整的居家健身空間，可鋪設於重量訓練器材、有氧器材及健身椅周圍。\r\n        </p>\r\n\r\n        <p>\r\n            加厚設計提供器材與地板之間更完整的緩衝，12 片組合可覆蓋較大的使用範圍，並能依不同房間格局自由調整配置。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/EQ-MAT-002_intro_01.png\" alt=\"HOME FIT 加厚防震地墊 12 入組\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>12 片大範圍拼接配置</li>\r\n        <li>加厚緩衝設計</li>\r\n        <li>適合重量及有氧器材區</li>\r\n        <li>可依居家空間自由組合</li>\r\n        <li>幫助減少器材直接接觸地板</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>加厚拼接防震地墊</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>單片尺寸</th>\r\n                    <td>60 × 60 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>單片厚度</th>\r\n                    <td>2.0 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>完整鋪設面積</th>\r\n                    <td>約 4.32 m²</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>主要材質</th>\r\n                    <td>高密度 EVA＋橡膠複合材質</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>表面設計</th>\r\n                    <td>防滑耐磨紋理</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>清潔方式</th>\r\n                    <td>濕布擦拭後自然風乾</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:06:24'),
(13, 11, 'HOME FIT 彈性護膝一對\r\n', '提供膝關節包覆與穩定支撐，適合深蹲、弓箭步及一般下肢訓練。\r\n', 680, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 彈性護膝適合重量訓練與一般健身活動使用，透過彈性包覆提供膝部訓練時的輔助支撐。\r\n        </p>\r\n\r\n        <p>\r\n            貼合腿部的設計同時保留活動所需的靈活度，可搭配深蹲、弓箭步及多種下肢訓練使用。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/AC-SUP-001_intro_01.png\" alt=\"HOME FIT 彈性護膝\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>彈性包覆設計</li>\r\n        <li>提供膝部訓練時的輔助支撐</li>\r\n        <li>適合深蹲及下肢重量訓練</li>\r\n        <li>輕巧方便攜帶</li>\r\n        <li>適合居家及健身房使用</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>彈性運動護膝</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>尺寸</th>\r\n                    <td>M／L／XL</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>主要材質</th>\r\n                    <td>尼龍、聚酯纖維、彈性纖維</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>厚度</th>\r\n                    <td>約 5 mm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>結構</th>\r\n                    <td>彈性套筒式</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>防滑設計</th>\r\n                    <td>上緣矽膠防滑條</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>清潔方式</th>\r\n                    <td>冷水手洗、自然晾乾</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:07:06'),
(14, 11, 'HOME FIT 重訓護腕一對\r\n', '增加手腕支撐與穩定度，適合臥推、肩推及其他推舉類重量訓練。\r\n', 580, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 重訓護腕適合啞鈴、槓鈴及各類自由重量訓練，在推舉及上肢訓練時提供手腕包覆與輔助支撐。\r\n        </p>\r\n\r\n        <p>\r\n            可調式纏繞設計能依照個人需求調整鬆緊程度，適合臥推、肩推等需要手腕承受較大負荷的重量訓練。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/AC-SUP-002_intro_01.png\" alt=\"HOME FIT 重訓護腕\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>可調式纏繞設計</li>\r\n        <li>提供手腕包覆與輔助支撐</li>\r\n        <li>適合臥推、肩推等重量訓練</li>\r\n        <li>可依需求調整鬆緊</li>\r\n        <li>輕巧易攜帶與收納</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>重訓護腕</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>單條長度</th>\r\n                    <td>約 45 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>寬度</th>\r\n                    <td>約 8 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>主要材質</th>\r\n                    <td>聚酯纖維、彈性纖維、棉</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>固定方式</th>\r\n                    <td>魔鬼氈纏繞式</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>拇指固定環</th>\r\n                    <td>有</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>清潔方式</th>\r\n                    <td>建議手洗、自然晾乾</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:07:06'),
(15, 12, 'HOME FIT 筋膜按摩槍 Mini\r\n', '多段震動模式與輕巧機身，適合訓練後進行局部肌肉按摩與放鬆。\r\n', 2480, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 筋膜按摩槍 Mini 採用輕巧便攜設計，適合在運動後進行日常肌肉放鬆。\r\n        </p>\r\n\r\n        <p>\r\n            可搭配不同按摩頭使用於腿部、肩部、手臂等常用肌群，小型化機身方便單手操作及攜帶，可放置於居家健身區或隨身健身包中。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/AC-REC-001_intro_01.png\" alt=\"HOME FIT 筋膜按摩槍 Mini\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>Mini 輕巧機身設計</li>\r\n        <li>適合運動後肌肉放鬆</li>\r\n        <li>多種按摩頭搭配使用</li>\r\n        <li>方便單手握持</li>\r\n        <li>易於攜帶與收納</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>筋膜按摩槍</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>震動段數</th>\r\n                    <td>4 段</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>震動頻率</th>\r\n                    <td>約 1,800－3,000 次／分鐘</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>電池容量</th>\r\n                    <td>2,000 mAh</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>使用時間</th>\r\n                    <td>約 3－5 小時</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品重量</th>\r\n                    <td>480 g</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>保固期限</th>\r\n                    <td>1 年</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:07:40'),
(16, 12, 'HOME FIT 高密度按摩滾筒\r\n', '高密度泡棉結構，適合腿部、臀部及背部筋膜放鬆與運動後恢復。\r\n', 880, 1, '<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品介紹</h3>\r\n\r\n    <div class=\"product-detail-description\">\r\n        <p>\r\n            HOME FIT 高密度按摩滾筒適合運動前後伸展與日常肌肉放鬆，可應用於腿部、臀部、背部等較大肌群。\r\n        </p>\r\n\r\n        <p>\r\n            高密度筒身提供穩定支撐，不需電源即可使用，可搭配瑜珈墊進行多種伸展及恢復動作，是簡單且容易融入日常訓練的放鬆工具。\r\n        </p>\r\n    </div>\r\n</section>\r\n\r\n\r\n<!-- 詳細介紹圖片 -->\r\n<section class=\"product-detail-section product-detail-image\">\r\n    <img src=\"product_img/AC-REC-002_intro_01.png\" alt=\"HOME FIT 高密度按摩滾筒\" class=\"img-fluid\">\r\n</section>\r\n\r\n\r\n<!-- 產品特色 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">產品特色</h3>\r\n\r\n    <ul class=\"product-feature-list\">\r\n        <li>高密度筒身設計</li>\r\n        <li>適合運動前後肌肉放鬆</li>\r\n        <li>可應用於腿部、臀部及背部</li>\r\n        <li>不需電源即可使用</li>\r\n        <li>適合居家與健身房攜帶使用</li>\r\n    </ul>\r\n</section>\r\n\r\n\r\n<!-- 商品規格 -->\r\n<section class=\"product-detail-section\">\r\n    <h3 class=\"product-detail-title\">商品規格</h3>\r\n\r\n    <div class=\"table-responsive\">\r\n        <table class=\"product-spec-table\">\r\n            <tbody>\r\n                <tr>\r\n                    <th>品牌</th>\r\n                    <td>HOME FIT</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品類型</th>\r\n                    <td>高密度按摩滾筒</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>商品尺寸</th>\r\n                    <td>約 Ø14 × 33 cm</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>內管材質</th>\r\n                    <td>高強度 PP</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>外層材質</th>\r\n                    <td>高密度 EVA 發泡材質</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>硬度</th>\r\n                    <td>高硬度</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>最大建議承重</th>\r\n                    <td>150 KG</td>\r\n                </tr>\r\n\r\n                <tr>\r\n                    <th>清潔方式</th>\r\n                    <td>濕布擦拭、自然風乾</td>\r\n                </tr>\r\n            </tbody>\r\n        </table>\r\n    </div>\r\n</section>', '2026-08-11 01:07:40');

-- --------------------------------------------------------

--
-- 資料表結構 `product_img`
--

CREATE TABLE `product_img` (
  `img_id` int(11) NOT NULL COMMENT '圖檔編號',
  `p_id` int(10) NOT NULL COMMENT '產品編號',
  `img_file` varchar(100) NOT NULL COMMENT '圖檔名稱',
  `sort` int(2) NOT NULL COMMENT '圖片順序',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '建立日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `product_img`
--

INSERT INTO `product_img` (`img_id`, `p_id`, `img_file`, `sort`, `create_date`) VALUES
(1, 1, 'WT-ADB-001_gallery_01.png', 1, '2026-08-11 01:10:28'),
(2, 1, 'WT-ADB-001_gallery_02.png', 2, '2026-08-11 01:10:28'),
(3, 1, 'WT-ADB-001_detail_01.png', 3, '2026-08-11 01:10:28'),
(4, 2, 'WT-ADB-002_gallery_01.png', 1, '2026-08-11 01:16:32'),
(5, 2, 'WT-ADB-002_gallery_02.png', 2, '2026-08-11 01:16:32'),
(6, 2, 'WT-ADB-002_detail_01.png', 3, '2026-08-11 01:16:32'),
(7, 3, 'WT-ABB-001_gallery_01.png', 1, '2026-08-11 01:17:30'),
(8, 3, 'WT-ABB-001_gallery_02.png', 2, '2026-08-11 01:17:30'),
(9, 3, 'WT-ABB-001_detail_01.png', 3, '2026-08-11 01:17:30'),
(10, 4, 'WT-ABB-002_gallery_01.png', 1, '2026-08-11 01:18:15'),
(11, 4, 'WT-ABB-002_gallery_02.png', 2, '2026-08-11 01:18:15'),
(12, 4, 'WT-ABB-002_detail_01.png', 3, '2026-08-11 01:18:15'),
(13, 5, 'CD-TRD-001_gallery_01.png', 1, '2026-08-11 01:19:01'),
(14, 5, 'CD-TRD-001_gallery_02.png', 2, '2026-08-11 01:19:01'),
(15, 5, 'CD-TRD-001_detail_01.png', 3, '2026-08-11 01:19:01'),
(16, 6, 'CD-TRD-002_gallery_01.png', 1, '2026-08-11 01:19:43'),
(17, 6, 'CD-TRD-002_gallery_02.png', 2, '2026-08-11 01:19:43'),
(18, 6, 'CD-TRD-002_detail_01.png', 3, '2026-08-11 01:19:43'),
(19, 7, 'CD-BIK-001_gallery_01.png', 1, '2026-08-11 01:20:25'),
(20, 7, 'CD-BIK-001_gallery_02.png', 2, '2026-08-11 01:20:25'),
(21, 7, 'CD-BIK-001_detail_01.png', 3, '2026-08-11 01:20:25'),
(22, 8, 'CD-BIK-002_gallery_01.png', 1, '2026-08-11 01:21:10'),
(23, 8, 'CD-BIK-002_gallery_02.png', 2, '2026-08-11 01:21:10'),
(24, 8, 'CD-BIK-002_detail_01.png', 3, '2026-08-11 01:21:10'),
(25, 9, 'EQ-BEN-001_gallery_01.png', 1, '2026-08-11 01:21:47'),
(26, 9, 'EQ-BEN-001_gallery_02.png', 2, '2026-08-11 01:21:47'),
(27, 9, 'EQ-BEN-001_detail_01.png', 3, '2026-08-11 01:21:47'),
(28, 10, 'EQ-BEN-002_gallery_01.png', 1, '2026-08-11 01:22:38'),
(29, 10, 'EQ-BEN-002_gallery_02.png', 2, '2026-08-11 01:22:38'),
(30, 10, 'EQ-BEN-002_detail_01.png', 3, '2026-08-11 01:22:38'),
(31, 11, 'EQ-MAT-001_gallery_01.png', 1, '2026-08-11 01:23:22'),
(32, 11, 'EQ-MAT-001_gallery_02.png', 2, '2026-08-11 01:23:22'),
(33, 11, 'EQ-MAT-001_detail_01.png', 3, '2026-08-11 01:23:22'),
(34, 12, 'EQ-MAT-002_gallery_01.png', 1, '2026-08-11 01:24:00'),
(35, 12, 'EQ-MAT-002_gallery_02.png', 2, '2026-08-11 01:24:00'),
(36, 12, 'EQ-MAT-002_detail_01.png', 3, '2026-08-11 01:24:00'),
(37, 13, 'AC-SUP-001_gallery_01.png', 1, '2026-08-11 01:24:37'),
(38, 13, 'AC-SUP-001_gallery_02.png', 2, '2026-08-11 01:24:37'),
(39, 13, 'AC-SUP-001_detail_01.png', 3, '2026-08-11 01:24:37'),
(40, 14, 'AC-SUP-002_gallery_01.png', 1, '2026-08-11 01:25:18'),
(41, 14, 'AC-SUP-002_gallery_02.png', 2, '2026-08-11 01:25:18'),
(42, 14, 'AC-SUP-002_detail_01.png', 3, '2026-08-11 01:25:18'),
(43, 15, 'AC-REC-001_gallery_01.png', 1, '2026-08-11 01:25:57'),
(44, 15, 'AC-REC-001_gallery_02.png', 2, '2026-08-11 01:25:57'),
(45, 15, 'AC-REC-001_detail_01.png', 3, '2026-08-11 01:25:57'),
(46, 16, 'AC-REC-002_gallery_01.png', 1, '2026-08-11 01:26:42'),
(47, 16, 'AC-REC-002_gallery_02.png', 2, '2026-08-11 01:26:42'),
(48, 16, 'AC-REC-002_detail_01.png', 3, '2026-08-11 01:26:42');

-- --------------------------------------------------------

--
-- 資料表結構 `pyclass`
--

CREATE TABLE `pyclass` (
  `classid` int(3) NOT NULL COMMENT '產品類別',
  `level` int(2) NOT NULL COMMENT '所在層級',
  `fonticon` varchar(30) NOT NULL COMMENT '字型圖示',
  `cname` varchar(30) NOT NULL COMMENT '類別名稱',
  `sort` int(3) NOT NULL COMMENT '列表排序',
  `uplink` int(3) NOT NULL COMMENT '上層連結',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT '建立時間與更新時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- 傾印資料表的資料 `pyclass`
--

INSERT INTO `pyclass` (`classid`, `level`, `fonticon`, `cname`, `sort`, `uplink`, `create_date`) VALUES
(1, 1, 'fa-solid fa-dumbbell', '負重訓練', 1, 0, '2026-08-10 07:48:06'),
(2, 1, 'fa-solid fa-person-running', '有氧訓練', 2, 0, '2026-08-10 07:49:06'),
(3, 1, 'fa-solid fa-fire-flame-simple', '訓練器材', 3, 0, '2026-08-11 05:31:32'),
(4, 1, 'fa-solid fa-spa', '訓練配件', 4, 0, '2026-08-10 07:51:31'),
(5, 2, 'fa-solid fa-dumbbell', '可調式啞鈴', 1, 1, '2026-08-11 02:23:56'),
(6, 2, 'fa-solid fa-dumbbell', '可調式槓鈴', 2, 1, '2026-08-11 02:24:04'),
(7, 2, 'fa-solid fa-person-running', '跑步機', 1, 2, '2026-08-10 07:57:37'),
(8, 2, 'fa-solid fa-person-running', '健身車', 2, 2, '2026-08-10 07:58:26'),
(9, 2, 'fa-solid fa-fire-flame-simple', '健身椅', 1, 3, '2026-08-11 05:31:37'),
(10, 2, 'fa-solid fa-fire-flame-simple', '器材地墊', 2, 3, '2026-08-11 05:31:44'),
(11, 2, 'fa-solid fa-spa', '健身護具', 1, 4, '2026-08-10 08:00:59'),
(12, 2, 'fa-solid fa-spa', '按摩恢復', 2, 4, '2026-08-10 08:01:40');

-- --------------------------------------------------------

--
-- 資料表結構 `town`
--

CREATE TABLE `town` (
  `townNo` bigint(20) NOT NULL COMMENT '鄕鎮市編號',
  `Name` varchar(150) NOT NULL COMMENT '鄕鎮市名稱',
  `Post` varchar(10) NOT NULL COMMENT '郵遞區號',
  `State` smallint(6) NOT NULL COMMENT '狀態',
  `AutoNo` int(10) NOT NULL COMMENT '上層城市編號連結'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 傾印資料表的資料 `town`
--

INSERT INTO `town` (`townNo`, `Name`, `Post`, `State`, `AutoNo`) VALUES
(1, '中正區', '100', 0, 1),
(2, '大同區', '103', 0, 1),
(3, '中山區', '104', 0, 1),
(4, '松山區', '105', 0, 1),
(5, '大安區', '106', 0, 1),
(6, '萬華區', '108', 0, 1),
(7, '信義區', '110', 0, 1),
(8, '士林區', '111', 0, 1),
(9, '北投區', '112', 0, 1),
(10, '內湖區', '114', 0, 1),
(11, '南港區', '115', 0, 1),
(12, '文山區', '116', 0, 1),
(13, '仁愛區', '200', 0, 2),
(14, '信義區', '201', 0, 2),
(15, '中正區', '202', 0, 2),
(16, '中山區', '203', 0, 2),
(17, '安樂區', '204', 0, 2),
(18, '暖暖區', '205', 0, 2),
(19, '七堵區', '206', 0, 2),
(20, '萬里區', '207', 0, 3),
(21, '金山區', '208', 0, 3),
(22, '板橋區', '220', 0, 3),
(23, '汐止區', '221', 0, 3),
(24, '深坑區', '222', 0, 3),
(25, '石碇區', '223', 0, 3),
(26, '瑞芳區', '224', 0, 3),
(27, '平溪區', '226', 0, 3),
(28, '雙溪區', '227', 0, 3),
(29, '貢寮區', '228', 0, 3),
(30, '新店區', '231', 0, 3),
(31, '坪林區', '232', 0, 3),
(32, '烏來區', '233', 0, 3),
(33, '永和區', '234', 0, 3),
(34, '中和區', '235', 0, 3),
(35, '土城區', '236', 0, 3),
(36, '三峽區', '237', 0, 3),
(37, '樹林區', '238', 0, 3),
(38, '鶯歌區', '239', 0, 3),
(39, '三重區', '241', 0, 3),
(40, '新莊區', '242', 0, 3),
(41, '泰山區', '243', 0, 3),
(42, '林口區', '244', 0, 3),
(43, '蘆洲區', '247', 0, 3),
(44, '五股區', '248', 0, 3),
(45, '八里區', '249', 0, 3),
(46, '淡水區', '251', 0, 3),
(47, '三芝區', '252', 0, 3),
(48, '石門區', '253', 0, 3),
(49, '宜蘭市', '260', 0, 4),
(50, '頭城鎮', '261', 0, 4),
(51, '礁溪鄉', '262', 0, 4),
(52, '壯圍鄉', '263', 0, 4),
(53, '員山鄉', '264', 0, 4),
(54, '羅東鎮', '265', 0, 4),
(55, '三星鄉', '266', 0, 4),
(56, '大同鄉', '267', 0, 4),
(57, '五結鄉', '268', 0, 4),
(58, '冬山鄉', '269', 0, 4),
(59, '蘇澳鎮', '270', 0, 4),
(60, '南澳鄉', '272', 0, 4),
(61, '釣魚台列嶼', '290', 0, 4),
(62, '新竹市(東區)', '300', 0, 5),
(63, '竹北市', '302', 0, 6),
(64, '湖口鄉', '303', 0, 6),
(65, '新豐鄉', '304', 0, 6),
(66, '新埔鎮', '305', 0, 6),
(67, '關西鎮', '306', 0, 6),
(68, '芎林鄉', '307', 0, 6),
(69, '寶山鄉', '308', 0, 6),
(70, '竹東鎮', '310', 0, 6),
(71, '五峰鄉', '311', 0, 6),
(72, '橫山鄉', '312', 0, 6),
(73, '尖石鄉', '313', 0, 6),
(74, '北埔鄉', '314', 0, 6),
(75, '峨眉鄉', '315', 0, 6),
(76, '中壢區', '320', 0, 7),
(77, '平鎮區', '324', 0, 7),
(78, '龍潭區', '325', 0, 7),
(79, '楊梅區', '326', 0, 7),
(80, '新屋區', '327', 0, 7),
(81, '觀音區', '328', 0, 7),
(82, '桃園區', '330', 0, 7),
(83, '龜山區', '333', 0, 7),
(84, '八德區', '334', 0, 7),
(85, '大溪區', '335', 0, 7),
(86, '復興區', '336', 0, 7),
(87, '大園區', '337', 0, 7),
(88, '蘆竹區', '338', 0, 7),
(89, '竹南鎮', '350', 0, 8),
(90, '頭份市', '351', 0, 8),
(91, '三灣鄉', '352', 0, 8),
(92, '南庄鄉', '353', 0, 8),
(93, '獅潭鄉', '354', 0, 8),
(94, '後龍鎮', '356', 0, 8),
(95, '通霄鎮', '357', 0, 8),
(96, '苑裡鎮', '358', 0, 8),
(97, '苗栗市', '360', 0, 8),
(98, '造橋鄉', '361', 0, 8),
(99, '頭屋鄉', '362', 0, 8),
(100, '公館鄉', '363', 0, 8),
(101, '大湖鄉', '364', 0, 8),
(102, '泰安鄉', '365', 0, 8),
(103, '銅鑼鄉', '366', 0, 8),
(104, '三義鄉', '367', 0, 8),
(105, '西湖鄉', '368', 0, 8),
(106, '卓蘭鎮', '369', 0, 8),
(107, '中區', '400', 0, 9),
(108, '東區', '401', 0, 9),
(109, '南區', '402', 0, 9),
(110, '西區', '403', 0, 9),
(111, '北區', '404', 0, 9),
(112, '北屯區', '406', 0, 9),
(113, '西屯區', '407', 0, 9),
(114, '南屯區', '408', 0, 9),
(115, '太平區', '411', 0, 9),
(116, '大里區', '412', 0, 9),
(117, '霧峰區', '413', 0, 9),
(118, '烏日區', '414', 0, 9),
(119, '豐原區', '420', 0, 9),
(120, '后里區', '421', 0, 9),
(121, '石岡區', '422', 0, 9),
(122, '東勢區', '423', 0, 9),
(123, '和平區', '424', 0, 9),
(124, '新社區', '426', 0, 9),
(125, '潭子區', '427', 0, 9),
(126, '大雅區', '428', 0, 9),
(127, '神岡區', '429', 0, 9),
(128, '大肚區', '432', 0, 9),
(129, '沙鹿區', '433', 0, 9),
(130, '龍井區', '434', 0, 9),
(131, '梧棲區', '435', 0, 9),
(132, '清水區', '436', 0, 9),
(133, '大甲區', '437', 0, 9),
(134, '外埔區', '438', 0, 9),
(135, '大安區', '439', 0, 9),
(136, '彰化市', '500', 0, 10),
(137, '芬園鄉', '502', 0, 10),
(138, '花壇鄉', '503', 0, 10),
(139, '秀水鄉', '504', 0, 10),
(140, '鹿港鎮', '505', 0, 10),
(141, '福興鄉', '506', 0, 10),
(142, '線西鄉', '507', 0, 10),
(143, '和美鎮', '508', 0, 10),
(144, '伸港鄉', '509', 0, 10),
(145, '員林市', '510', 0, 10),
(146, '社頭鄉', '511', 0, 10),
(147, '永靖鄉', '512', 0, 10),
(148, '埔心鄉', '513', 0, 10),
(149, '溪湖鎮', '514', 0, 10),
(150, '大村鄉', '515', 0, 10),
(151, '埔鹽鄉', '516', 0, 10),
(152, '田中鎮', '520', 0, 10),
(153, '北斗鎮', '521', 0, 10),
(154, '田尾鄉', '522', 0, 10),
(155, '埤頭鄉', '523', 0, 10),
(156, '溪州鄉', '524', 0, 10),
(157, '竹塘鄉', '525', 0, 10),
(158, '二林鎮', '526', 0, 10),
(159, '大城鄉', '527', 0, 10),
(160, '芳苑鄉', '528', 0, 10),
(161, '二水鄉', '530', 0, 10),
(162, '南投市', '540', 0, 11),
(163, '中寮鄉', '541', 0, 11),
(164, '草屯鎮', '542', 0, 11),
(165, '國姓鄉', '544', 0, 11),
(166, '埔里鎮', '545', 0, 11),
(167, '仁愛鄉', '546', 0, 11),
(168, '名間鄉', '551', 0, 11),
(169, '集集鎮', '552', 0, 11),
(170, '水里鄉', '553', 0, 11),
(171, '魚池鄉', '555', 0, 11),
(172, '信義鄉', '556', 0, 11),
(173, '竹山鎮', '557', 0, 11),
(174, '鹿谷鄉', '558', 0, 11),
(175, '斗南鎮', '630', 0, 12),
(176, '大埤鄉', '631', 0, 12),
(177, '虎尾鎮', '632', 0, 12),
(178, '土庫鎮', '633', 0, 12),
(179, '褒忠鄉', '634', 0, 12),
(180, '東勢鄉', '635', 0, 12),
(181, '臺西鄉', '636', 0, 12),
(182, '崙背鄉', '637', 0, 12),
(183, '麥寮鄉', '638', 0, 12),
(184, '斗六市', '640', 0, 12),
(185, '林內鄉', '643', 0, 12),
(186, '古坑鄉', '646', 0, 12),
(187, '莿桐鄉', '647', 0, 12),
(188, '西螺鎮', '648', 0, 12),
(189, '二崙鄉', '649', 0, 12),
(190, '北港鎮', '651', 0, 12),
(191, '水林鄉', '652', 0, 12),
(192, '口湖鄉', '653', 0, 12),
(193, '四湖鄉', '654', 0, 12),
(194, '元長鄉', '655', 0, 12),
(195, '嘉義市(東區)', '600', 0, 13),
(196, '番路鄉', '602', 0, 14),
(197, '梅山鄉', '603', 0, 14),
(198, '竹崎鄉', '604', 0, 14),
(199, '阿里山鄉', '605', 0, 14),
(200, '中埔鄉', '606', 0, 14),
(201, '大埔鄉', '607', 0, 14),
(202, '水上鄉', '608', 0, 14),
(203, '鹿草鄉', '611', 0, 14),
(204, '太保市', '612', 0, 14),
(205, '朴子市', '613', 0, 14),
(206, '東石鄉', '614', 0, 14),
(207, '六腳鄉', '615', 0, 14),
(208, '新港鄉', '616', 0, 14),
(209, '民雄鄉', '621', 0, 14),
(210, '大林鎮', '622', 0, 14),
(211, '溪口鄉', '623', 0, 14),
(212, '義竹鄉', '624', 0, 14),
(213, '布袋鎮', '625', 0, 14),
(214, '中西區', '700', 0, 15),
(215, '東區', '701', 0, 15),
(216, '南區', '702', 0, 15),
(217, '北區', '704', 0, 15),
(218, '安平區', '708', 0, 15),
(219, '安南區', '709', 0, 15),
(220, '永康區', '710', 0, 15),
(221, '歸仁區', '711', 0, 15),
(222, '新化區', '712', 0, 15),
(223, '左鎮區', '713', 0, 15),
(224, '玉井區', '714', 0, 15),
(225, '楠西區', '715', 0, 15),
(226, '南化區', '716', 0, 15),
(227, '仁德區', '717', 0, 15),
(228, '關廟區', '718', 0, 15),
(229, '龍崎區', '719', 0, 15),
(230, '官田區', '720', 0, 15),
(231, '麻豆區', '721', 0, 15),
(232, '佳里區', '722', 0, 15),
(233, '西港區', '723', 0, 15),
(234, '七股區', '724', 0, 15),
(235, '將軍區', '725', 0, 15),
(236, '學甲區', '726', 0, 15),
(237, '北門區', '727', 0, 15),
(238, '新營區', '730', 0, 15),
(239, '後壁區', '731', 0, 15),
(240, '白河區', '732', 0, 15),
(241, '東山區', '733', 0, 15),
(242, '六甲區', '734', 0, 15),
(243, '下營區', '735', 0, 15),
(244, '柳營區', '736', 0, 15),
(245, '鹽水區', '737', 0, 15),
(246, '善化區', '741', 0, 15),
(247, '大內區', '742', 0, 15),
(248, '山上區', '743', 0, 15),
(249, '新市區', '744', 0, 15),
(250, '安定區', '745', 0, 15),
(251, '新興區', '800', 0, 16),
(252, '前金區', '801', 0, 16),
(253, '苓雅區', '802', 0, 16),
(254, '鹽埕區', '803', 0, 16),
(255, '鼓山區', '804', 0, 16),
(256, '旗津區', '805', 0, 16),
(257, '前鎮區', '806', 0, 16),
(258, '三民區', '807', 0, 16),
(259, '楠梓區', '811', 0, 16),
(260, '小港區', '812', 0, 16),
(261, '左營區', '813', 0, 16),
(262, '仁武區', '814', 0, 16),
(263, '大社區', '815', 0, 16),
(264, '岡山區', '820', 0, 16),
(265, '路竹區', '821', 0, 16),
(266, '阿蓮區', '822', 0, 16),
(267, '田寮區', '823', 0, 16),
(268, '燕巢區', '824', 0, 16),
(269, '橋頭區', '825', 0, 16),
(270, '梓官區', '826', 0, 16),
(271, '彌陀區', '827', 0, 16),
(272, '永安區', '828', 0, 16),
(273, '湖內區', '829', 0, 16),
(274, '鳳山區', '830', 0, 16),
(275, '大寮區', '831', 0, 16),
(276, '林園區', '832', 0, 16),
(277, '鳥松區', '833', 0, 16),
(278, '大樹區', '840', 0, 16),
(279, '旗山區', '842', 0, 16),
(280, '美濃區', '843', 0, 16),
(281, '六龜區', '844', 0, 16),
(282, '內門區', '845', 0, 16),
(283, '杉林區', '846', 0, 16),
(284, '甲仙區', '847', 0, 16),
(285, '桃源區', '848', 0, 16),
(286, '那瑪夏區', '849', 0, 16),
(287, '茂林區', '851', 0, 16),
(288, '茄萣區', '852', 0, 16),
(289, '東沙', '817', 0, 17),
(290, '南沙', '819', 0, 17),
(291, '馬公市', '880', 0, 18),
(292, '西嶼鄉', '881', 0, 18),
(293, '望安鄉', '882', 0, 18),
(294, '七美鄉', '883', 0, 18),
(295, '白沙鄉', '884', 0, 18),
(296, '湖西鄉', '885', 0, 18),
(297, '屏東市', '900', 0, 19),
(298, '三地門鄉', '901', 0, 19),
(299, '霧臺鄉', '902', 0, 19),
(300, '瑪家鄉', '903', 0, 19),
(301, '九如鄉', '904', 0, 19),
(302, '里港鄉', '905', 0, 19),
(303, '高樹鄉', '906', 0, 19),
(304, '鹽埔鄉', '907', 0, 19),
(305, '長治鄉', '908', 0, 19),
(306, '麟洛鄉', '909', 0, 19),
(307, '竹田鄉', '911', 0, 19),
(308, '內埔鄉', '912', 0, 19),
(309, '萬丹鄉', '913', 0, 19),
(310, '潮州鎮', '920', 0, 19),
(311, '泰武鄉', '921', 0, 19),
(312, '來義鄉', '922', 0, 19),
(313, '萬巒鄉', '923', 0, 19),
(314, '崁頂鄉', '924', 0, 19),
(315, '新埤鄉', '925', 0, 19),
(316, '南州鄉', '926', 0, 19),
(317, '林邊鄉', '927', 0, 19),
(318, '東港鄉', '928', 0, 19),
(319, '琉球鄉', '929', 0, 19),
(320, '佳冬鄉', '931', 0, 19),
(321, '新園鄉', '932', 0, 19),
(322, '枋寮鄉', '940', 0, 19),
(323, '枋山鄉', '941', 0, 19),
(324, '春日鄉', '942', 0, 19),
(325, '獅子鄉', '943', 0, 19),
(326, '車城鄉', '944', 0, 19),
(327, '牡丹鄉', '945', 0, 19),
(328, '恆春鎮', '946', 0, 19),
(329, '滿州鄉', '947', 0, 19),
(330, '臺東市', '950', 0, 20),
(331, '綠島鄉', '951', 0, 20),
(332, '蘭嶼鄉', '952', 0, 20),
(333, '延平鄉', '953', 0, 20),
(334, '卑南鄉', '954', 0, 20),
(335, '鹿野鄉', '955', 0, 20),
(336, '關山鎮', '956', 0, 20),
(337, '海端鄉', '957', 0, 20),
(338, '池上鄉', '958', 0, 20),
(339, '東河鄉', '959', 0, 20),
(340, '成功鎮', '961', 0, 20),
(341, '長濱鄉', '962', 0, 20),
(342, '太麻里鄉', '963', 0, 20),
(343, '金峰鄉', '964', 0, 20),
(344, '大武鄉', '965', 0, 20),
(345, '達仁鄉', '966', 0, 20),
(346, '花蓮市', '970', 0, 21),
(347, '新城鄉', '971', 0, 21),
(348, '秀林鄉', '972', 0, 21),
(349, '吉安鄉', '973', 0, 21),
(350, '壽豐鄉', '974', 0, 21),
(351, '鳳林鎮', '975', 0, 21),
(352, '光復鄉', '976', 0, 21),
(353, '豐濱鄉', '977', 0, 21),
(354, '瑞穗鄉', '978', 0, 21),
(355, '萬榮鄉', '979', 0, 21),
(356, '玉里鎮', '981', 0, 21),
(357, '卓溪鄉', '982', 0, 21),
(358, '富里鄉', '983', 0, 21),
(359, '金沙鎮', '890', 0, 22),
(360, '金湖鎮', '891', 0, 22),
(361, '金寧鄉', '892', 0, 22),
(362, '金城鎮', '893', 0, 22),
(363, '烈嶼鄉', '894', 0, 22),
(364, '烏坵鄉', '896', 0, 22),
(365, '南竿鄉', '209', 0, 23),
(366, '北竿鄉', '210', 0, 23),
(367, '莒光鄉', '211', 0, 23),
(368, '東引鄉', '212', 0, 23),
(371, '新竹市(北區)', '300', 0, 5),
(372, '新竹市(香山區)', '300', 0, 5),
(373, '嘉義市(西區)', '600', 0, 13);

-- --------------------------------------------------------

--
-- 資料表結構 `uorder`
--

CREATE TABLE `uorder` (
  `orderid` varchar(30) NOT NULL COMMENT '訂單編號',
  `emailid` int(10) NOT NULL COMMENT '會員編號',
  `addressid` int(10) NOT NULL COMMENT '收件人編號',
  `howpay` tinyint(4) NOT NULL DEFAULT 1 COMMENT '如何付款',
  `paystatus` int(5) DEFAULT NULL COMMENT '付款狀態',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '訂單處理狀態',
  `remark` varchar(200) DEFAULT NULL COMMENT '備註',
  `create_date` timestamp NOT NULL DEFAULT current_timestamp() COMMENT '訂單時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPACT;

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `addbook`
--
ALTER TABLE `addbook`
  ADD PRIMARY KEY (`addressid`);

--
-- 資料表索引 `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`caro_id`);

--
-- 資料表索引 `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartid`);

--
-- 資料表索引 `city`
--
ALTER TABLE `city`
  ADD PRIMARY KEY (`AutoNo`);

--
-- 資料表索引 `hot`
--
ALTER TABLE `hot`
  ADD PRIMARY KEY (`h_id`);

--
-- 資料表索引 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`emailid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 資料表索引 `multiselect`
--
ALTER TABLE `multiselect`
  ADD PRIMARY KEY (`msid`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`p_id`);

--
-- 資料表索引 `product_img`
--
ALTER TABLE `product_img`
  ADD PRIMARY KEY (`img_id`);

--
-- 資料表索引 `pyclass`
--
ALTER TABLE `pyclass`
  ADD PRIMARY KEY (`classid`);

--
-- 資料表索引 `town`
--
ALTER TABLE `town`
  ADD PRIMARY KEY (`townNo`);

--
-- 資料表索引 `uorder`
--
ALTER TABLE `uorder`
  ADD PRIMARY KEY (`orderid`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `addbook`
--
ALTER TABLE `addbook`
  MODIFY `addressid` int(10) NOT NULL AUTO_INCREMENT COMMENT '地址ID', AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `carousel`
--
ALTER TABLE `carousel`
  MODIFY `caro_id` int(3) NOT NULL AUTO_INCREMENT COMMENT '輪播編號', AUTO_INCREMENT=4;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `cart`
--
ALTER TABLE `cart`
  MODIFY `cartid` int(10) NOT NULL AUTO_INCREMENT COMMENT '購物車編號', AUTO_INCREMENT=15;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `city`
--
ALTER TABLE `city`
  MODIFY `AutoNo` int(10) NOT NULL AUTO_INCREMENT COMMENT '城市編號', AUTO_INCREMENT=24;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `hot`
--
ALTER TABLE `hot`
  MODIFY `h_id` int(3) NOT NULL AUTO_INCREMENT COMMENT '熱銷商品流水號', AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `member`
--
ALTER TABLE `member`
  MODIFY `emailid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'email流水號', AUTO_INCREMENT=5;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `multiselect`
--
ALTER TABLE `multiselect`
  MODIFY `msid` int(5) NOT NULL AUTO_INCREMENT COMMENT '多功能選擇ID', AUTO_INCREMENT=39;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `p_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '產品編號', AUTO_INCREMENT=17;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product_img`
--
ALTER TABLE `product_img`
  MODIFY `img_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '圖檔編號', AUTO_INCREMENT=49;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `pyclass`
--
ALTER TABLE `pyclass`
  MODIFY `classid` int(3) NOT NULL AUTO_INCREMENT COMMENT '產品類別', AUTO_INCREMENT=121;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `town`
--
ALTER TABLE `town`
  MODIFY `townNo` bigint(20) NOT NULL AUTO_INCREMENT COMMENT '鄕鎮市編號', AUTO_INCREMENT=374;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
