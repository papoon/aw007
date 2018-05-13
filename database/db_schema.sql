DROP TABLE IF EXISTS Inv_Index_Articles;
DROP TABLE IF EXISTS Inv_Index_Tweets;
DROP TABLE IF EXISTS Similarity_Articles;
DROP TABLE IF EXISTS Similarity_Tweets;
DROP TABLE IF EXISTS Tf_Idf_Articles;
DROP TABLE IF EXISTS Tf_Idf_Tweets;
DROP TABLE IF EXISTS MER_Terms_Articles;
DROP TABLE IF EXISTS MER_Terms_Tweets;
DROP TABLE IF EXISTS Article_Author;
DROP TABLE IF EXISTS articles_comment;
DROP TABLE IF EXISTS articles_rating;
DROP TABLE IF EXISTS Article;
DROP TABLE IF EXISTS Photos;
DROP TABLE IF EXISTS Tweets;
DROP TABLE IF EXISTS Author;
DROP TABLE IF EXISTS Disease;
DROP TABLE IF EXISTS clients_site;

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

ALTER TABLE `Disease`
ADD COLUMN `do_id` VARCHAR(50) NULL AFTER `dbpedia_revision_id`;

ALTER TABLE `Article`
ADD COLUMN `clicks` INT(11) DEFAULT 0 AFTER `article_revision_date`,
ADD COLUMN `relevance` INT(11) DEFAULT 0 AFTER `clicks`;

ALTER TABLE `Tweets`
ADD COLUMN `relevance` INT(11) DEFAULT 0 AFTER `nr_comments`;

CREATE TABLE MER_Terms_Articles (
	term VARCHAR(150),
	article_id INT(11),
	pos_start INT(11),
	pos_end INT(11),
	PRIMARY KEY (term, article_id, pos_start),
	FOREIGN KEY (article_id) REFERENCES Article(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE MER_Terms_Tweets (
	term VARCHAR(150),
	tweet_id INT(11),
	pos_start INT(11),
	pos_end INT(11),
	PRIMARY KEY (term, tweet_id, pos_start),
	FOREIGN KEY (tweet_id) REFERENCES Tweets(id) ON DELETE CASCADE ON UPDATE CASCADE
);

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

CREATE TABLE Inv_Index_Articles (
	did INT(11),
	article_id INT(11),
	article_rank INT(11),
	tf_idf_value NUMERIC(8, 4),
	resnik_value NUMERIC(8, 4),
	clicks INT(11),
	relevance INT(11),
	published_at DATETIME,
	PRIMARY KEY (did, article_id),
	FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (article_id) REFERENCES Article(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Inv_Index_Tweets (
	did INT(11),
	tweet_id INT(11),
	tweet_rank INT(11),
	tf_idf_value NUMERIC(8, 4),
	resnik_value NUMERIC(8, 4),
	nr_likes INT(11),
	relevance INT(11),
	published_at DATETIME,
	PRIMARY KEY (did, tweet_id),
	FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (tweet_id) REFERENCES Tweets(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `clients_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'usar o ip como identificador de utilizador - dá para simular utilizadores diferentes',
  `insert_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);


CREATE TABLE articles_comment (
	id int PRIMARY KEY AUTO_INCREMENT,
	article_id int,
	client_id int DEFAULT NULL,
	comment text(1000),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (article_id) REFERENCES Article (id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (client_id) REFERENCES clients_site (id) ON DELETE CASCADE ON UPDATE CASCADE

);


CREATE TABLE articles_rating (
	id int PRIMARY KEY AUTO_INCREMENT,
	article_id int,
	client_id int DEFAULT NULL,
	rating tinyint(3),
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (article_id) REFERENCES Article (id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (client_id) REFERENCES clients_site (id) ON DELETE CASCADE ON UPDATE CASCADE

);


ALTER TABLE MER_Terms_Articles ADD COLUMN disease_id int,
    ADD CONSTRAINT fk_terms_articles FOREIGN KEY (disease_id) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD COLUMN do_id varchar(55) DEFAULT NULL,
    ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE MER_Terms_Tweets ADD COLUMN disease_id int,
    ADD CONSTRAINT fk_terms_tweets FOREIGN KEY (disease_id) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD COLUMN do_id varchar(55) DEFAULT NULL,
     ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE MER_Terms_Articles ADD COLUMN likes int, ADD COLUMN dislikes int;

CREATE TABLE `diseases_rating` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `disease_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `rating` tinyint(3) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`disease_id`) REFERENCES `Disease` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`client_id`) REFERENCES `clients_site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `diseases_comment` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `disease_id` int(11) DEFAULT NULL,
  `client_id` int(11) DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`disease_id`) REFERENCES `Disease` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`client_id`) REFERENCES `clients_site` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);


CREATE TABLE Similarity_Diseases (
	id int auto_increment primary key ,
	did INT(11),
	disease_id INT(11),
	resnik_value NUMERIC(8, 4),
	FOREIGN KEY (did) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (disease_id) REFERENCES Disease(id) ON DELETE CASCADE ON UPDATE CASCADE
);


ALTER TABLE MER_Terms_Articles ALTER likes SET DEFAULT 0, ALTER dislikes SET DEFAULT 0;
