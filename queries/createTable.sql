CREATE TABLE Product (
	id	INTEGER NOT NULL,
	Product_Name	TEXT NOT NULL UNIQUE CHECK (length(Product_Name) >=3 and length(Product_Name) <= 25),
	Cost	INTEGER NOT NULL,
	PRIMARY KEY(id)
);