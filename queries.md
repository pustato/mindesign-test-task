## Схема таблиц:

```mysql
CREATE TABLE orders(
  id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  order_number VARCHAR(100) NOT NULL
);

CREATE TABLE products(
  id INT(10) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  title VARCHAR(100) NOT NULL
);

CREATE TABLE orders_products (
  order_id INT(10) NOT NULL, 
  product_id INT(10) NOT NULL
);
```

#### Напишите следующие SQL-запросы:

##### 1. Вывести список заказов вместе с количеством товаров в данных заказах

```mysql
SELECT orders.id, orders.order_number, COUNT(orders_products.product_id) AS products_count
FROM orders
  INNER JOIN orders_products ON orders.id = orders_products.order_id
GROUP BY orders.id;
```

##### 2. Вывести все заказы, в которых больше 10 товаров

```mysql
SELECT orders.id, orders.order_number, COUNT(orders_products.product_id) AS products_count
FROM orders
  INNER JOIN orders_products ON orders.id = orders_products.order_id
GROUP BY orders.id
HAVING products_count > 10;
```

##### 3. вывести два любых заказа, у которых максимальное количество общих товаров

```mysql
SELECT 
  pivot_main.order_id AS first_order_id,
  order_main.order_number AS first_order_number,
  pivot_second.order_id AS second_order_id,
  order_second.order_number AS first_order_number,
  count(pivot_main.product_id) AS product_matches_count
FROM orders_products AS pivot_main
  INNER JOIN orders_products AS pivot_second ON pivot_second.product_id = pivot_main.product_id AND pivot_main.order_id < pivot_second.order_id
  INNER JOIN orders AS order_main ON pivot_main.order_id = order_main.id
  INNER JOIN orders AS order_second ON pivot_second.order_id = order_second.id
GROUP BY pivot_main.order_id, pivot_second.order_id
ORDER BY product_matches_count DESC ;
```
