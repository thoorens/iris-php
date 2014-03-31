DROP TABLE IF EXISTS "products";
CREATE TABLE products(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL , 
    Description TEXT  NOT NULL,
    Price NUMBER);
INSERT INTO "products" VALUES(1,'orange',0.5);
INSERT INTO "products" VALUES(2,'banana',0.6);
INSERT INTO "products" VALUES(3,'apple',0.3);
