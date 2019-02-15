DROP TABLE IF EXISTS B4S_orderitems;
DROP TABLE IF EXISTS B4S_order;
DROP TABLE IF EXISTS B4S_product;
DROP TABLE IF EXISTS B4S_customer;
DROP TABLE IF EXISTS B4S_newsletter;

CREATE TABLE B4S_newsletter (
    email VARCHAR(255) PRIMARY KEY
);

CREATE TABLE B4S_customer (
    email VARCHAR(255) PRIMARY KEY, 
    fname VARCHAR(100), 
    sname VARCHAR(100), 
    postcode VARCHAR(7),
    pass VARCHAR(41)
);

CREATE TABLE B4S_product (
    pid INT AUTO_INCREMENT PRIMARY KEY , 
    name VARCHAR(100),
    imagepath VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2)
);

CREATE TABLE B4S_order (
    oid INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    FOREIGN KEY (email) REFERENCES B4S_customer(email)
);

CREATE TABLE B4S_orderitems (
    oid INT,
    pid INT,
    qty INT,
    PRIMARY KEY (oid, pid),
    FOREIGN KEY (oid) REFERENCES B4S_order(oid),
    FOREIGN KEY (pid) REFERENCES B4S_product(pid)
);

INSERT INTO b4s_product VALUE
    (NULL, "Kids Bunk Bed(boys)","Images/kidbunkbedboy.jpg", "This cleverly designed detachable bunk bed can be split into two single beds", 499.99),
    (NULL, "Kids Bunk Bed(girls)","Images/kidbunkbedgirl.jpg","This wonderful space saving bed comes with the option of underbed drawers to keep your child's bedroom tidy",419.99),
    (NULL, "Divans Bed","Images/double beds.jpg","The 4 large drawers on castors give you an extra storage space under the bed.",245),
    (NULL, "Single Bed" ,"Images/Single bed.jpg","The 2 large drawers give you an extra storage space under the bed.",160),
(NULL,"Double bed","Images/double bed.jpg","This double bed consists f a soft matteress, perfect for a comfy sleep.", 320);
    
    
