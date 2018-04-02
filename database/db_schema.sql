DROP TABLE IF EXISTS Article_Author;
DROP TABLE IF EXISTS Article;
DROP TABLE IF EXISTS Photos;
DROP TABLE IF EXISTS Tweets;
DROP TABLE IF EXISTS Author;
DROP TABLE IF EXISTS Disease;

CREATE TABLE Disease (
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	dbpedia_id INT(20),
	abstract text(40000),
	created_at DATETIME,
	updated_at DATETIME
);

CREATE TABLE Article (
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	did INT(11),
	journal_id VARCHAR(50),
	title text(5000),
	abstract text(40000),
	published_at DATETIME,
	article_date DATETIME,
	inserted_at DATETIME,
	updated_at DATETIME,
	FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Author (
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR (255),
	institution VARCHAR(255),
	contact VARCHAR(255),
  inserted_at DATETIME,
	updated_at DATETIME
);

CREATE TABLE Article_Author (
	art_id INT(11),
	aut_id INT(11),
	PRIMARY KEY (art_id, aut_id),
	FOREIGN KEY(art_id) REFERENCES Article(id),
  FOREIGN KEY(aut_id) REFERENCES Author(id)
);

CREATE TABLE Photos (
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	did INT(11),
	url VARCHAR(255),
  flicrk_id VARCHAR(255),
  author_name VARCHAR(255),
  username VARCHAR(255),
  nr_likes INT(11),
  nr_comments INT(11),
  shares INT(11),
  country VARCHAR(50),
  published_at DATETIME,
	inserted_at DATETIME,
	updated_at DATETIME,
  FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Tweets (
	id INT(11) PRIMARY KEY AUTO_INCREMENT,
	did INT(11),
	url VARCHAR(255),
	type VARCHAR(255),
  tweet_id VARCHAR(255),
  author_name VARCHAR(255),
  username VARCHAR(255),
  nr_likes INT(11),
  nr_comments INT(11),
  shares INT(11),
  country VARCHAR(50),
  published_at DATETIME,
	inserted_at DATETIME,
	updated_at DATETIME,
  FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE
);

ALTER TABLE `Disease`
ADD COLUMN `dbpedia_revision_id` INT NULL AFTER `dbpedia_id`,
ADD COLUMN `thumbnail` VARCHAR(255) NULL COMMENT 'url do thumbnail' AFTER `abstract`,
ADD COLUMN `uri` VARCHAR(255) NULL COMMENT 'url disease' AFTER `thumbnail`;

ALTER TABLE `Article`
ADD COLUMN `article_revision_date` DATETIME NULL AFTER `article_date`;

ALTER TABLE `Article`
ADD COLUMN `authors` VARCHAR(500) NULL AFTER `article_revision_date`;

ALTER TABLE `Article`
ADD COLUMN `article_id` INT(11) NULL AFTER `did`;

ALTER TABLE `Tweets`
ADD COLUMN `html` TEXT NULL AFTER `country`;

DROP TABLE IF EXISTS Tf_Idf_Articles;
DROP TABLE IF EXISTS Tf_Idf_Tweets;

CREATE TABLE Tf_Idf_Articles (
	term VARCHAR(150),
	article_id INT(11),
	tf_idf_value NUMERIC(8, 4),
	PRIMARY KEY (term, article_id),
	FOREIGN KEY (article_id) REFERENCES Article(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Tf_Idf_Tweets (
	term VARCHAR(150),
	tweet_id INT(11),
	tf_idf_value NUMERIC(8, 4),
	PRIMARY KEY (term, tweet_id),
	FOREIGN KEY (tweet_id) REFERENCES Tweets(id) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS Similarity_Articles;
DROP TABLE IF EXISTS Similarity_Tweets;

CREATE TABLE Similarity_Articles (
	did INT(11),
	article_id INT(11),
	resnik_value NUMERIC(8, 4),
	PRIMARY KEY (did, article_id),
	FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (article_id) REFERENCES Article(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Similarity_Tweets (
	did INT(11),
	tweet_id INT(11),
	resnik_value NUMERIC(8, 4),
	PRIMARY KEY (did, tweet_id),
	FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (tweet_id) REFERENCES Tweets(id) ON DELETE CASCADE ON UPDATE CASCADE
);
