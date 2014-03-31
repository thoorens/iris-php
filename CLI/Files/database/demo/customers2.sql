DROP TABLE IF EXISTS "customers2";
CREATE TABLE customers2(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Name TEXT  NOT NULL,
    Address TEXT,
    Email TEXT);
INSERT INTO "customers2" VALUES(1,'Jacques Thoorens','rue Villette','irisphp@thoorens.net');
INSERT INTO "customers2" VALUES(2,'John Smith','Bourbon street','john@smith.eu');
INSERT INTO "customers2" VALUES(3,'Antonio Sanchez','Gran Via',NULL);
