CREATE TABLE Feedback (
	ID int NOT NULL AUTO_INCREMENT,
	article_id INT(11),
	rating INT(11),
	comment VARCHAR(255),
	timestamp DATETIME,
	clients_site_id int(11),
	PRIMARY KEY (ID),
	FOREIGN KEY (article_id) REFERENCES Article(id) ON DELETE CASCADE ON UPDATE CASCADE,
	FOREIGN KEY (clients_site_id) REFERENCES clients_site(id) ON DELETE CASCADE ON UPDATE CASCADE
);