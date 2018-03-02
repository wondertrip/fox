CREATE TABLE `stockdailyinfo_kdj` (
  `stockcode` varchar(6) NOT NULL DEFAULT '',
  `tradedate` int(8) NOT NULL DEFAULT '0',
  `closePrice` float DEFAULT NULL,
  `lowestPrice` float DEFAULT NULL,
  `highestPrice` float DEFAULT NULL,
  `k` float DEFAULT NULL,
  `d` float DEFAULT NULL,
  `j` float DEFAULT NULL,
  `rsv` float DEFAULT NULL,
  PRIMARY KEY (`stockcode`,`tradedate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
