-- Migration File: tour_du_lich_v2.sql

-- Adding columns to existing tables
ALTER TABLE tours ADD COLUMNS (image VARCHAR(255), destination VARCHAR(150), duration INT, max_slots INT, discount_percent DECIMAL(5,2), featured BOOLEAN, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

ALTER TABLE users ADD COLUMNS (phone VARCHAR(20), address TEXT, avatar VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

ALTER TABLE bookings ADD COLUMNS (quantity INT, total_price DECIMAL(10,2), payment_status VARCHAR(50), payment_method VARCHAR(50), confirmed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, notes TEXT);


-- Creating new tables with proper indexes and foreign keys
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    amount DECIMAL(10,2),
    payment_method VARCHAR(50),
    status VARCHAR(50),
    transaction_id VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
);

CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    tour_id INT,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (tour_id) REFERENCES tours(id)
);

CREATE TABLE promotions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50),
    discount DECIMAL(5,2),
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    active BOOLEAN
);

CREATE TABLE itineraries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT,
    day INT,
    title VARCHAR(255),
    description TEXT,
    activities TEXT,
    FOREIGN KEY (tour_id) REFERENCES tours(id)
);

CREATE TABLE services (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT,
    name VARCHAR(255),
    description TEXT,
    FOREIGN KEY (tour_id) REFERENCES tours(id)
);

CREATE TABLE tour_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tour_id INT,
    image_url VARCHAR(255),
    `order` INT,
    FOREIGN KEY (tour_id) REFERENCES tours(id)
);