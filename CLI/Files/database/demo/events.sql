DROP TABLE IF EXISTS "events";
CREATE TABLE events(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    Description VARCHAR,
    Moment DATETIME,
    invoice_id INTEGER NOT NULL ,
    product_id INTEGER NOT NULL ,
    FOREIGN KEY (invoice_id,product_id) REFERENCES orders(invoice_id,product_id));
INSERT INTO "events" VALUES(1,'Order to wholesaler','2011-12-27 12:00',1,1);
INSERT INTO "events" VALUES(2,'Shipment','2012-01-05 08:00',1,1);
INSERT INTO "events" VALUES(3,'Shipment','2012-01-05 07:30',1,2);
INSERT INTO "events" VALUES(4,'Shipment','2012-01-05 08:00',1,3);
INSERT INTO "events" VALUES(5,'Shipment','2012-01-05 09:00',2,2);
INSERT INTO "events" VALUES(6,'Shipment','2012-01-05 09:05',3,3);
INSERT INTO "events" VALUES(7,'Shipment','2012-01-05 09:10',3,2);
INSERT INTO "events" VALUES(8,'Shipment','2012-02-13 11:00',4,1);
INSERT INTO "events" VALUES(9,'Shipment','2012-02-13 11:00',4,2);
INSERT INTO "events" VALUES(10,'Order to wholesaler','2012-01-18 13:00',5,1);
INSERT INTO "events" VALUES(11,'Shipment','2012-02-21 14:00',5,1);
INSERT INTO "events" VALUES(12,'Shipment','2012-02-21 15:00',5,3);
INSERT INTO "events" VALUES(13,'Shipment','2012-03-04 23:00',6,3);
