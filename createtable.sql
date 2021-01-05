CREATE TABLE Table1
( Number1 INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  Name1 CHAR(50) NOT NULL,
  Phone1 CHAR(16) NOT NULL,
  Email1 CHAR(50) NOT NULL,
  Text1 VARCHAR(200) NOT NULL,
  Date1 CHAR(20) NOT NULL,
  UNIQUE INDEX (Email1, Text1)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
