CREATE DATABASE IF NOT EXISTS appDB;
CREATE USER IF NOT EXISTS 'user'@'%' IDENTIFIED BY 'password';
GRANT SELECT, UPDATE, INSERT, DELETE ON appDB.* TO 'user'@'%';
FLUSH PRIVILEGES;

USE appDB;

CREATE TABLE IF NOT EXISTS users (
  ID INT(11) NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  surname VARCHAR(40) NOT NULL,
  PRIMARY KEY (ID)
);

CREATE TABLE IF NOT EXISTS orders (
  order_id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  product VARCHAR(50) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (order_id),
  FOREIGN KEY (user_id) REFERENCES users(ID) ON DELETE CASCADE
);

INSERT INTO users (name, surname)
SELECT * FROM (SELECT 'Alex', 'Rover') AS tmp
WHERE NOT EXISTS (SELECT name FROM users WHERE name = 'Alex' AND surname = 'Rover') LIMIT 1;

INSERT INTO users (name, surname)
SELECT * FROM (SELECT 'Bob', 'Marley') AS tmp
WHERE NOT EXISTS (SELECT name FROM users WHERE name = 'Bob' AND surname = 'Marley') LIMIT 1;

INSERT INTO users (name, surname)
SELECT * FROM (SELECT 'Kate', 'Yandson') AS tmp
WHERE NOT EXISTS (SELECT name FROM users WHERE name = 'Kate' AND surname = 'Yandson') LIMIT 1;

INSERT INTO orders (user_id, product, amount)
SELECT * FROM (SELECT 1, 'Laptop', 1200.00) AS tmp
WHERE NOT EXISTS (SELECT product FROM orders WHERE product = 'Laptop') LIMIT 1;

INSERT INTO orders (user_id, product, amount)
SELECT * FROM (SELECT 2, 'Headphones', 150.00) AS tmp
WHERE NOT EXISTS (SELECT product FROM orders WHERE product = 'Headphones') LIMIT 1;

INSERT INTO orders (user_id, product, amount)
SELECT * FROM (SELECT 3, 'Mouse', 30.00) AS tmp
WHERE NOT EXISTS (SELECT product FROM orders WHERE product = 'Mouse') LIMIT 1;
