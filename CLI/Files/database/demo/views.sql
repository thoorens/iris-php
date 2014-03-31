-- views
CREATE VIEW "vcustomers" AS select * from customers WHERE id < 3;
CREATE VIEW "vcustomers2" AS select * from customers WHERE id > 1;