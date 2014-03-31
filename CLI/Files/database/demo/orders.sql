DROP TABLE IF EXISTS "orders";
CREATE TABLE orders(
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    UnitPrice NUMBER,
    Quantity INTEGER,
    PRIMARY KEY(invoice_id,product_id),
    FOREIGN KEY (invoice_id) REFERENCES invoices(id),
    FOREIGN KEY (product_id) REFERENCES products(id));
INSERT INTO "orders" VALUES(1,1,NULL,1);
INSERT INTO "orders" VALUES(1,2,NULL,1);
INSERT INTO "orders" VALUES(1,3,NULL,2);
INSERT INTO "orders" VALUES(2,2,NULL,1);
INSERT INTO "orders" VALUES(3,3,NULL,1);
INSERT INTO "orders" VALUES(3,2,NULL,2);
INSERT INTO "orders" VALUES(4,1,NULL,3);
INSERT INTO "orders" VALUES(4,2,NULL,1);
INSERT INTO "orders" VALUES(5,1,NULL,5);
INSERT INTO "orders" VALUES(5,3,NULL,2);
INSERT INTO "orders" VALUES(6,3,NULL,1);
