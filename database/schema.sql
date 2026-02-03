-- Schema PostgreSQL
CREATE TYPE role_enum AS ENUM ('student', 'staff', 'admin');
CREATE TYPE service_enum AS ENUM ('lunch', 'dinner');
CREATE TYPE order_status_enum AS ENUM ('draft', 'reserved', 'paid', 'cancelled');
CREATE TYPE payment_status_enum AS ENUM ('pending', 'paid', 'failed');

CREATE TABLE users (
  id SERIAL PRIMARY KEY,
  role role_enum NOT NULL,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  student_id VARCHAR(50),
  staff_id VARCHAR(50),
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE categories (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) UNIQUE NOT NULL
);

CREATE TABLE dishes (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  description TEXT,
  price NUMERIC(8,2) NOT NULL
);

CREATE TABLE dish_categories (
  dish_id INT REFERENCES dishes(id) ON DELETE CASCADE,
  category_id INT REFERENCES categories(id) ON DELETE CASCADE,
  PRIMARY KEY (dish_id, category_id)
);

CREATE TABLE ingredients (
  id SERIAL PRIMARY KEY,
  name VARCHAR(120) UNIQUE NOT NULL,
  unit VARCHAR(20) NOT NULL
);

CREATE TABLE dish_ingredients (
  dish_id INT REFERENCES dishes(id) ON DELETE CASCADE,
  ingredient_id INT REFERENCES ingredients(id) ON DELETE CASCADE,
  qty NUMERIC(10,2) NOT NULL,
  PRIMARY KEY (dish_id, ingredient_id)
);

CREATE TABLE menus (
  id SERIAL PRIMARY KEY,
  menu_date DATE NOT NULL,
  service service_enum NOT NULL,
  UNIQUE (menu_date, service)
);

CREATE TABLE menu_items (
  menu_id INT REFERENCES menus(id) ON DELETE CASCADE,
  dish_id INT REFERENCES dishes(id) ON DELETE CASCADE,
  quantity_limit INT,
  sold_count INT DEFAULT 0,
  PRIMARY KEY (menu_id, dish_id)
);

CREATE TABLE orders (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES users(id),
  menu_id INT REFERENCES menus(id),
  status order_status_enum NOT NULL,
  total_amount NUMERIC(10,2) DEFAULT 0,
  reserved_for DATE,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE order_items (
  order_id INT REFERENCES orders(id) ON DELETE CASCADE,
  dish_id INT REFERENCES dishes(id),
  quantity INT NOT NULL,
  unit_price NUMERIC(8,2) NOT NULL,
  PRIMARY KEY (order_id, dish_id)
);

CREATE TABLE payments (
  id SERIAL PRIMARY KEY,
  order_id INT UNIQUE REFERENCES orders(id) ON DELETE CASCADE,
  status payment_status_enum NOT NULL,
  method VARCHAR(30) NOT NULL,
  amount NUMERIC(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE stock_movements (
  id SERIAL PRIMARY KEY,
  ingredient_id INT REFERENCES ingredients(id),
  delta_qty NUMERIC(10,2) NOT NULL,
  reason VARCHAR(120) NOT NULL,
  created_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE reviews (
  id SERIAL PRIMARY KEY,
  user_id INT REFERENCES users(id),
  dish_id INT REFERENCES dishes(id),
  menu_id INT REFERENCES menus(id),
  rating INT CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT NOW(),
  CHECK ((dish_id IS NOT NULL) OR (menu_id IS NOT NULL))
);

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_menu_items_menu ON menu_items(menu_id);
