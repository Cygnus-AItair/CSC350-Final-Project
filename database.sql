CREATE DATABASE pantry_db;
USE pantry_db;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image_path VARCHAR(500),
    unit VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);
INSERT INTO products (name, description, category, price, image_path, unit) VALUES
('White Rice', 'Premium quality white rice', 'Grains', 0.70, 'images/rice.jpg', '5 lb bag'),
('Pasta', 'High quality pasta', 'Grains', 1.20, 'images/pasta.jpg', '16 oz box'),
('Steel Cut Oats', 'Healthy steel cut oats', 'Grains', 1.00, 'images/oats.jpg', '2 lb bag'),
('Bread', 'Fresh baked bread', 'Grains', 2.50, 'images/bread.jpg', '1 Loaf'),
('Olive Oil', 'Extra virgin olive oil', 'Fats & Oils', 5.50, 'images/oliveoil.jpg', '16 oz bottle'),
('Peanut Butter', 'Creamy peanut butter', 'Fats & Oils', 3.00, 'images/peanutbutter.jpg', '16 oz jar'),
('Butter', 'Fresh dairy butter', 'Fats & Oils', 1.50, 'images/butter.jpg', '1 Carton'),
('Salad Dressing', 'Italian salad dressing', 'Fats & Oils', 2.75, 'images/dressing.jpg', '16oz Bottle'),
('Black Beans', 'Canned black beans', 'Canned Goods', 1.25, 'images/beans.jpg', '15 oz can'),
('Diced Tomatoes', 'Canned diced tomatoes', 'Canned Goods', 1.10, 'images/tomatoes.jpg', '14 oz can'),
('Canned Peaches', 'Sweet canned peaches', 'Canned Goods', 0.25, 'images/peaches.jpg', '14 oz can'),
('Canned Corn', 'Whole kernel corn', 'Canned Goods', 1.30, 'images/corn.jpg', '14 oz can');

