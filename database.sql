CREATE DATABASE pantry_db;
USE pantry_db;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(100) NOT NULL,
    image_path VARCHAR(500),
    unit VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    session_id VARCHAR(255) NOT NULL,
    status ENUM ('active', 'abandoned', 'converted') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
);
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cart_id) REFERENCES carts(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_product (cart_id, product_id)
);
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (cart_id) REFERENCES carts(id)
);
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    notes TEXT,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);
INSERT INTO products (name, description, category, image_path, unit) VALUES
('White Rice', 'Premium quality white rice', 'Grains', 'images/rice.jpg', '5 lb bag'),
('Pasta', 'High quality pasta', 'Grains', 'images/pasta.jpg', '16 oz box'),
('Steel Cut Oats', 'Healthy steel cut oats', 'Grains', 'images/oats.jpg', '2 lb bag'),
('Bread', 'Fresh baked bread', 'Grains', 'images/bread.jpg', '1 Loaf'),
('Olive Oil', 'Extra virgin olive oil', 'Fats & Oils', 'images/oliveoil.jpg', '16 oz bottle'),
('Peanut Butter', 'Creamy peanut butter', 'Fats & Oils', 'images/peanutbutter.jpg', '16 oz jar'),
('Butter', 'Fresh dairy butter', 'Fats & Oils', 'images/butter.jpg', '1 Carton'),
('Salad Dressing', 'Italian salad dressing', 'Fats & Oils', 'images/dressing.jpg', '16oz Bottle'),
('Black Beans', 'Canned black beans', 'Canned Goods', 'images/beans.jpg', '15 oz can'),
('Diced Tomatoes', 'Canned diced tomatoes', 'Canned Goods', 'images/tomatoes.jpg', '14 oz can'),
('Canned Peaches', 'Sweet canned peaches', 'Canned Goods', 'images/peaches.jpg', '14 oz can'),
('Canned Corn', 'Whole kernel corn', 'Canned Goods', 'images/corn.jpg', '14 oz can');

-- Admin account
-- Password: 'admin123' (hashed with password_hash())
INSERT INTO users (name, email, password, role) VALUES
('Administrator', 'admin@foodbank.com', '$2y$10$FgK5b/6qGk8v6Q5Z8q7QwO5V5q5Q5Z8q7QwO5V5q5Q5Z8q7QwO5V5q5', 'admin');
