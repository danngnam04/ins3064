-- SQL file for Laptop Shop Management
-- Database: LaptopShop

-- Create database
CREATE DATABASE IF NOT EXISTS `LaptopShop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `LaptopShop`;

-- Create table `laptops`
CREATE TABLE IF NOT EXISTS `laptops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `processor` varchar(100) NOT NULL,
  `ram` varchar(50) NOT NULL,
  `storage` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (optional)
INSERT INTO `laptops` (`brand`, `model`, `processor`, `ram`, `storage`, `price`, `stock`) VALUES
('Dell', 'Inspiron 15', 'Intel i5-1135G7', '8GB', '512GB SSD', 750.00, 10),
('HP', 'Pavilion 14', 'Intel i7-1165G7', '16GB', '1TB SSD', 1200.00, 5),
('Apple', 'MacBook Air M1', 'Apple M1', '8GB', '256GB SSD', 1000.00, 7);
