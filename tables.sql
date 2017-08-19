-- These sql statements are used to create the necessary tables

 CREATE TABLE IF NOT EXISTS 'bubbles' (
  'bubblekey' mediumint(9) NOT NULL AUTO_INCREMENT,
  'short' varchar(30) DEFAULT NULL,
  'longdesc' varchar(50) DEFAULT NULL,
  'groupid' varchar(20) DEFAULT NULL,
  PRIMARY KEY ('bubblekey'),
  KEY 'short' ('short')
);

CREATE TABLE IF NOT EXISTS 'bubblesused' (
  'usedkey' mediumint(9) NOT NULL AUTO_INCREMENT,
  'email' varchar(30) DEFAULT NULL,
  'bubble' varchar(30) DEFAULT NULL,
  'date' date DEFAULT NULL,
  'eat' varchar(60) DEFAULT NULL,
  'meal' varchar(20) DEFAULT NULL,
  PRIMARY KEY ('usedkey')
);

CREATE TABLE IF NOT EXISTS 'bubblesuser' (
  'userkey' mediumint(9) NOT NULL AUTO_INCREMENT,
  'email' varchar(30) DEFAULT NULL,
  'bubble' varchar(30) DEFAULT NULL,
  'numb' smallint(6) DEFAULT NULL,
  PRIMARY KEY ('userkey')
);

CREATE TABLE IF NOT EXISTS 'savedbubble' (
  'savedbubblekey' int(11) NOT NULL AUTO_INCREMENT,
  'bubble' varchar(30) DEFAULT NULL,
  'eat' varchar(60) DEFAULT NULL,
  'groupname' varchar(30) DEFAULT NULL,
  'email' varchar(30) DEFAULT NULL,
  PRIMARY KEY ('savedbubblekey')
);

CREATE TABLE IF NOT EXISTS 'exercize' (
  'exkey' mediumint(9) NOT NULL AUTO_INCREMENT,
  'email' varchar(30) DEFAULT NULL,
  'date' date DEFAULT NULL,
  'type' varchar(30) DEFAULT NULL,
  PRIMARY KEY ('exkey')
);