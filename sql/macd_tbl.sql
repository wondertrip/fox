CREATE TABLE `stockdailyinfo_macd` (
  `stockcode` varchar(6) NOT NULL DEFAULT '',
  `tradedate` int(8) NOT NULL DEFAULT '0',
  `closePrice` float DEFAULT NULL,
  `ema12` float DEFAULT NULL,
  `ema26` float DEFAULT NULL,
  `diff` float DEFAULT NULL,
  `dea` float DEFAULT NULL,
  `macd` float DEFAULT NULL,
  PRIMARY KEY (`stockcode`,`tradedate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
