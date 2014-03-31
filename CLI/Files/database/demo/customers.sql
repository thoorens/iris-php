DROP TABLE IF EXISTS "customers";
CREATE TABLE customers(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Name TEXT  NOT NULL,
    Address TEXT,
    Email TEXT);
INSERT INTO "customers" VALUES(1,'Jacques Thoorens','rue Villette','irisphp@thoorens.net');
INSERT INTO "customers" VALUES(2,'John Smith','Bourbon street','john@smith.eu');
INSERT INTO "customers" VALUES(3,'Antonio Sanchez','Gran Via',NULL);
