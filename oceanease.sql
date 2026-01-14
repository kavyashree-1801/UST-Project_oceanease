-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 10:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `oceanease`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Sujay Bhat', 'sujay@gmail.com', 'regarding facial for men', 'i want info about the above subject', '2026-01-13 14:07:09'),
(2, 'Neha', 'neha@yahoo.com', 'About beauty services', 'i want information about the above subject', '2026-01-13 15:17:42'),
(3, 'Rajesh Puranik', 'rajesh@gmail.com', 'regarding party hall details', 'i want info about partyhall!', '2026-01-14 08:12:10');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 1, 'Sujay Bhat', 'sujay@gmail.com', 'Good website!!', '2026-01-13 14:09:39'),
(2, 8, 'Neha Rao', 'neha@yahoo.com', 'Good website with good service options:)!', '2026-01-13 15:18:09'),
(3, 9, 'Rajesh Puranik', 'rajesh@gmail.com', 'Good services!!', '2026-01-14 08:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `fitness_bookings`
--

CREATE TABLE `fitness_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `workout_type` varchar(100) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('confirmed','cancelled','completed') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitness_bookings`
--

INSERT INTO `fitness_bookings` (`id`, `user_id`, `workout_type`, `booking_date`, `booking_time`, `status`, `created_at`) VALUES
(1, 1, 'Gym Training', '2026-01-10', '06:00:00', 'completed', '2026-01-07 10:58:08'),
(2, 1, 'Yoga', '2026-01-12', '18:30:00', 'completed', '2026-01-07 11:08:55'),
(3, 1, 'Gym Training', '2026-01-07', '17:00:00', 'completed', '2026-01-07 14:43:17'),
(4, 1, 'Personal Training', '2026-01-14', '05:30:00', 'cancelled', '2026-01-12 18:07:05'),
(5, 1, 'Aerobics', '2026-01-13', '06:30:00', 'completed', '2026-01-13 13:25:31'),
(6, 8, 'Yoga', '2026-01-16', '05:00:00', 'confirmed', '2026-01-13 15:16:10'),
(7, 9, 'Yoga', '2026-01-15', '06:30:00', 'confirmed', '2026-01-14 08:10:15');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `item_name`, `category`, `description`, `price`, `image`, `created_at`) VALUES
(1, 'Margherita Pizza', 'Main Course', 'Classic pizza with mozzarella and basil', 299.00, 'https://www.helloweirdough.in/cdn/shop/files/Margheritapizza_1.jpg?v=1729269112', '2025-12-24 12:37:24'),
(2, 'Pepperoni Pizza', 'Main Course', 'Spicy pepperoni with cheese', 349.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQy-tSPPEH9cU6BDeb8QnQieBxc2jMYAU5vqA&s', '2025-12-24 12:37:24'),
(3, 'Veg Burger', 'Snacks', 'Grilled vegetable patty with lettuce and sauce', 159.00, 'https://shwetainthekitchen.com/wp-content/uploads/2024/06/Veggie-Chickpea-Burger.jpg', '2025-12-24 12:37:24'),
(4, 'Chicken Burger', 'Snacks', 'Crispy chicken burger with mayo', 199.00, 'https://i0.wp.com/flaevor.com/wp-content/uploads/2022/04/SambalFriedChickenBurger1.jpg?resize=1024%2C830&ssl=1', '2025-12-24 12:37:24'),
(5, 'French Fries', 'Snacks', 'Golden crispy fries', 99.00, 'https://thecozycook.com/wp-content/uploads/2020/02/Copycat-McDonalds-French-Fries-.jpg', '2025-12-24 12:37:24'),
(6, 'Pasta Alfredo', 'Main Course', 'Creamy white sauce pasta', 279.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSux91Ee3pC4ssL-iPVDz5JuIkt9SEkwV14qQ&s', '2025-12-24 12:37:24'),
(7, 'Pasta Arrabiata', 'Main Course', 'Spicy red sauce pasta', 269.00, 'https://www.andy-cooks.com/cdn/shop/articles/20250710035519-andy-20cooks-20-20quick-20arrabbiata-20pasta-20recipe.jpg?v=1752651736', '2025-12-24 12:37:24'),
(8, 'Grilled Chicken', 'Main Course', 'Herb grilled chicken breast', 399.00, 'https://www.budgetbytes.com/wp-content/uploads/2024/06/Grilled-Chicken-Overhead.jpg', '2025-12-24 12:37:24'),
(9, 'Paneer Butter Masala', 'Main Course', 'Paneer cooked in rich buttery gravy', 329.00, 'https://j6e2i8c9.delivery.rocketcdn.me/wp-content/uploads/2020/12/Paneer-Butter-Masala-10.jpg.webp', '2025-12-24 12:37:24'),
(10, 'Veg Biryani', 'Main Course', 'Aromatic rice with vegetables and spices', 249.00, 'https://i.ytimg.com/vi/Do7ZdUodDdw/hq720.jpg?sqp=-oaymwEhCK4FEIIDSFryq4qpAxMIARUAAAAAGAElAADIQj0AgKJD&rs=AOn4CLAEQctXy1aD1723HT7omylxjn4tMQ', '2025-12-24 12:37:24'),
(11, 'Chicken Biryani', 'Main Course', 'Traditional spicy chicken biryani', 349.00, 'https://www.cubesnjuliennes.com/wp-content/uploads/2020/07/Chicken-Biryani-Recipe.jpg', '2025-12-24 12:37:24'),
(12, 'Caesar Salad', 'Starters', 'Fresh lettuce with Caesar dressing', 179.00, 'https://www.allrecipes.com/thmb/mXZ0Tulwn3x9_YB_ZbkiTveDYFE=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/229063-Classic-Restaurant-Caesar-Salad-ddmfs-4x3-231-89bafa5e54dd4a8c933cf2a5f9f12a6f.jpg', '2025-12-24 12:37:24'),
(13, 'Greek Salad', 'Starters', 'Salad with olives, feta, and veggies', 189.00, 'https://www.onceuponachef.com/images/2023/06/greek-salad-1-1200x1477.jpg', '2025-12-24 12:37:24'),
(14, 'Tomato Soup', 'Starters', 'Hot creamy tomato soup', 129.00, 'https://joyfoodsunshine.com/wp-content/uploads/2021/02/best-homemade-tomato-soup-recipe-1x1-1-500x500.jpg', '2025-12-24 12:37:24'),
(15, 'Sweet Corn Soup', 'Starters', 'Creamy sweet corn soup', 139.00, 'https://www.bigbasket.com/media/uploads/recipe/w-l/1132_1.jpg', '2025-12-24 12:37:24'),
(16, 'Chocolate Cake', 'Desserts', 'Rich chocolate layered cake', 199.00, 'https://www.bakedambrosia.com/wp-content/uploads/2023/10/Moist-Chocolate-Cake-20.jpg', '2025-12-24 12:37:24'),
(17, 'Vanilla Ice Cream', 'Desserts', 'Classic vanilla ice cream scoop', 99.00, 'https://www.theroastedroot.net/wp-content/uploads/2023/06/dairy-free-vanilla-ice-cream-8-500x500.jpg', '2025-12-24 12:37:24'),
(18, 'Brownie with Ice Cream', 'Desserts', 'Warm brownie served with ice cream', 219.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTvG0CZHchlu4oszq0L-ixrp4n1Vec7vUt3lw&s', '2025-12-24 12:37:24'),
(19, 'Fresh Lime Soda', 'Beverages', 'Refreshing lime soda', 89.00, 'https://sattvakitchen.com/wp-content/uploads/2024/05/SWEET-LIME-SODA-shutterstock_2309599743-copy-Copy-copy.jpg', '2025-12-24 12:37:24'),
(20, 'Cold Coffee', 'Beverages', 'Chilled coffee with milk', 149.00, 'https://www.whiskaffair.com/wp-content/uploads/2021/03/Cold-Coffee-2-1.jpg', '2025-12-24 12:37:24'),
(21, 'Roti', 'Main Course', 'Soft, warm whole-wheat flatbread, perfect for pairing with any curry or dip.', 50.00, 'https://tse4.mm.bing.net/th/id/OIP.j_6Nnf-LYzP5BwZDSvDY8wHaFX?rs=1&pid=ImgDetMain&o=7&rm=3', '2026-01-13 15:24:49'),
(22, 'Naan', 'Main Course', '\"Soft, fluffy Indian flatbread, freshly baked and perfect for scooping up rich, flavorful curries.\"', 50.00, 'https://www.vegrecipesofindia.com/wp-content/uploads/2022/12/garlic-naan-1.jpg', '2026-01-14 08:15:35');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `item_names` text DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `username`, `item_names`, `total_amount`, `status`, `order_date`) VALUES
(1, 1, 'Sujay Bhat', 'French Friesx1', 99.00, 'completed', '2025-12-26 10:27:11'),
(2, 1, 'Sujay Bhat', 'Veg Biryanix1', 290.00, 'completed', '2025-12-26 13:42:59'),
(3, 1, 'Sujay Bhat', 'Vanilla Ice Cream x1', 99.00, 'completed', '2026-01-07 10:13:00'),
(4, 1, 'Sujay Bhat', 'Grilled Chicken x1', 399.00, 'completed', '2026-01-07 10:23:35'),
(5, 1, 'Sujay Bhat', 'Sweet Corn Soup x1', 139.00, 'completed', '2026-01-07 10:26:02'),
(6, 1, 'Sujay Bhat', 'Paneer Butter Masala x1, Greek Salad x1', 518.00, 'completed', '2026-01-07 10:30:00'),
(7, 1, 'Sujay Bhat', 'Pasta Arrabiata x1', 269.00, 'completed', '2026-01-07 10:31:29'),
(8, 1, 'Sujay Bhat', 'Pasta Arrabiata x1', 269.00, 'completed', '2026-01-07 10:34:25'),
(9, 1, 'Sujay Bhat', 'Chocolate Cake x1', 199.00, 'completed', '2026-01-07 10:38:02'),
(10, 1, 'Sujay Bhat', 'Chicken Burger x1, Sweet Corn Soup x1', 338.00, 'completed', '2026-01-10 17:17:10'),
(11, 1, 'Sujay Bhat', 'Sweet Corn Soup x1, Fresh Lime Soda x1', 228.00, 'completed', '2026-01-12 17:47:39'),
(12, 7, 'Ramesh Nayak', 'Margherita Pizza x1, Chicken Biryani x1, Chocolate Cake x1', 847.00, 'completed', '2026-01-13 14:17:23'),
(13, 8, 'Neha Rao', 'Pasta Alfredo x1, Chocolate Cake x1, Cold Coffee x1', 627.00, 'completed', '2026-01-13 14:20:01'),
(14, 9, 'Rajesh Puranik', 'Margherita Pizza x1, Sweet Corn Soup x1', 438.00, 'pending', '2026-01-14 08:08:12');

-- --------------------------------------------------------

--
-- Table structure for table `party_hall_bookings`
--

CREATE TABLE `party_hall_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `hall_name` varchar(100) DEFAULT NULL,
  `event_type` varchar(100) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `guests` int(11) DEFAULT 10,
  `status` enum('confirmed','completed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `party_hall_bookings`
--

INSERT INTO `party_hall_bookings` (`id`, `user_id`, `hall_name`, `event_type`, `booking_date`, `booking_time`, `guests`, `status`, `created_at`) VALUES
(1, 1, 'Royal Grand Hall', 'Birthday Party', '2026-01-16', '10:00:00', 25, 'confirmed', '2026-01-07 10:58:49'),
(2, 1, 'Ocean View Banquet', 'Anniversary', '2026-01-07', '12:00:00', 23, 'completed', '2026-01-07 15:26:35'),
(3, 1, 'Sunset Party Lounge', 'Wedding', '2026-01-28', '12:00:00', 23, 'confirmed', '2026-01-12 18:07:36'),
(4, 1, 'Coral Celebration Hall', 'Birthday Party', '2026-01-13', '05:30:00', 25, 'completed', '2026-01-13 13:26:13'),
(5, 1, 'Ocean View Banquet', 'Anniversary', '2026-01-14', '11:00:00', 50, 'cancelled', '2026-01-13 14:01:42'),
(6, 8, 'Coral Celebration Hall', 'Birthday Party', '2026-01-17', '18:30:00', 25, 'confirmed', '2026-01-13 15:16:33'),
(7, 9, 'Coral Celebration Hall', 'Anniversary', '2026-01-15', '09:00:00', 50, 'confirmed', '2026-01-14 08:10:50');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `user_id`, `token`, `expires_at`) VALUES
(1, 1, 'f3879a687d912538af9d34cc9033b042', '2026-01-12 13:32:06'),
(2, 1, 'ca9b3beaed792005f59209b8426290fb', '2026-01-12 13:32:25');

-- --------------------------------------------------------

--
-- Table structure for table `resort_movie_bookings`
--

CREATE TABLE `resort_movie_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resort_name` varchar(100) NOT NULL,
  `movie_name` varchar(100) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `guests` int(11) DEFAULT 1,
  `status` enum('confirmed','cancelled','completed') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resort_movie_bookings`
--

INSERT INTO `resort_movie_bookings` (`id`, `user_id`, `resort_name`, `movie_name`, `booking_date`, `booking_time`, `guests`, `status`, `created_at`) VALUES
(1, 1, 'Blue Wave Resort', 'Lamhe', '2026-01-24', '04:00:00', 10, 'cancelled', '2026-01-05 14:33:54'),
(2, 1, 'Ocean Pearl Resort', 'DDLJ', '2026-01-07', '10:00:00', 23, 'completed', '2026-01-07 15:02:22'),
(3, 1, 'Coral Bay Resort', 'Sholay', '2026-01-13', '02:00:00', 4, 'confirmed', '2026-01-12 17:48:10'),
(4, 8, 'Ocean Pearl Resort', 'KGF', '2026-01-20', '10:00:00', 5, 'confirmed', '2026-01-13 15:15:17'),
(5, 9, 'Blue Wave Resort', 'Anupama', '2026-01-15', '12:00:00', 10, 'confirmed', '2026-01-14 08:09:13');

-- --------------------------------------------------------

--
-- Table structure for table `salon_bookings`
--

CREATE TABLE `salon_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` enum('completed','confirmed','cancelled') DEFAULT 'confirmed',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salon_bookings`
--

INSERT INTO `salon_bookings` (`id`, `user_id`, `service_name`, `booking_date`, `booking_time`, `status`, `created_at`) VALUES
(1, 1, 'Shaving', '2025-12-31', '10:01:00', 'completed', '2025-12-26 18:12:07'),
(2, 1, 'Facial', '2026-01-09', '17:00:00', 'completed', '2026-01-08 06:41:44'),
(3, 1, 'Beard Trim', '2026-01-14', '05:30:00', 'cancelled', '2026-01-12 18:06:41'),
(4, 1, 'Facial', '2026-01-13', '18:51:00', 'completed', '2026-01-13 13:21:09'),
(5, 1, 'Facial', '2026-01-13', '17:51:00', 'completed', '2026-01-13 13:21:33'),
(6, 8, 'Waxing', '2026-01-16', '10:45:00', 'confirmed', '2026-01-13 15:15:50'),
(7, 9, 'Shaving', '2026-01-15', '05:30:00', 'confirmed', '2026-01-14 08:09:51');

-- --------------------------------------------------------

--
-- Table structure for table `stationery_items`
--

CREATE TABLE `stationery_items` (
  `id` int(11) NOT NULL,
  `item` varchar(100) NOT NULL,
  `quantity_in_stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stationery_items`
--

INSERT INTO `stationery_items` (`id`, `item`, `quantity_in_stock`, `image`, `created_at`, `price`) VALUES
(1, 'Notebook', 100, 'https://images.pexels.com/photos/159865/notepad-pencil-green-black-159865.jpeg?cs=srgb&dl=pexels-pixabay-159865.jpg&fm=jpg', '2025-12-24 12:41:14', 25.00),
(2, 'Ball Pen', 500, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQOGt0lFaeZWTcc7HyTzl15FtWqni_2HYD0bA&s', '2025-12-24 12:41:14', 2.50),
(3, 'Pencil', 200, 'https://www.kokuyocamlin.com/camlin/camel-access/image/catalog/assets/camlin/pencils-and-accessories/wooden-pencils/box-of-10-pencils/box-of-10-pencils-with-eraser-and-long-point-sharpener/6.JPG', '2025-12-24 12:41:14', 3.00),
(4, 'Eraser', 500, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQfI-N4b6PTXOoUSwaCCXtvD9NBUzcokQWPVg&s', '2025-12-24 12:41:14', 5.00),
(5, 'Sharpener', 250, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRatFHzXWSpdOMfPPqFSLornu3CD9nujq9NXg&s', '2025-12-24 12:41:14', 5.00),
(6, 'Sticky Notes', 100, 'https://storage.needpix.com/rsynced_images/sticky-notes-938602_1280.jpg', '2025-12-24 12:41:14', 20.00),
(7, 'Highlighter', 200, 'https://media.istockphoto.com/id/157512131/photo/set-of-four-highlighters-on-white-background.webp?b=1&s=612x612&w=0&k=20&c=CSQUtd82pbYQRC0VCMcy4Ie5sTCGOdjmDHH_SVJSk4g=', '2025-12-24 12:41:14', 25.00),
(8, 'Marker Pen', 100, 'https://www.nicepng.com/png/detail/369-3693101_camlin-permanent-marker-cello-pen-marker-hd-png.png', '2025-12-24 12:41:14', 30.00),
(9, 'Paper File', 100, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS5eY31TBkPfJ2361yzKItLrwIRKj3Aqws0YQ&s', '2025-12-24 12:41:14', 35.00),
(10, 'Stapler', 300, 'https://m.media-amazon.com/images/I/81kvz-zNyfL.jpg', '2025-12-24 12:41:14', 80.00);

-- --------------------------------------------------------

--
-- Table structure for table `stationery_orders`
--

CREATE TABLE `stationery_orders` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `ordered_by` varchar(100) DEFAULT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('confirmed','completed','cancelled') DEFAULT 'confirmed',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `order_time` time NOT NULL DEFAULT '12:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stationery_orders`
--

INSERT INTO `stationery_orders` (`id`, `item_name`, `quantity`, `ordered_by`, `order_date`, `status`, `price`, `order_time`) VALUES
(1, 'Highlighter', 1, 'Sujay Bhat', '2026-01-07 11:45:14', 'completed', 25.00, '12:00:00'),
(2, 'Eraser', 2, 'Sujay Bhat', '2026-01-10 23:19:26', 'completed', 5.00, '12:00:00'),
(3, 'Marker Pen', 1, 'Sujay Bhat', '2026-01-10 23:19:26', 'completed', 30.00, '12:00:00'),
(4, 'Marker Pen', 1, 'Sujay Bhat', '2026-01-12 23:11:49', 'completed', 30.00, '12:00:00'),
(5, 'Notebook', 1, 'Neha Rao', '2026-01-13 19:50:21', 'completed', 25.00, '12:00:00'),
(6, 'Pencil', 1, 'Neha Rao', '2026-01-13 19:50:21', 'completed', 3.00, '12:00:00'),
(7, 'Highlighter', 1, 'Neha Rao', '2026-01-13 19:50:21', 'completed', 25.00, '12:00:00'),
(8, 'Marker Pen', 1, 'Neha Rao', '2026-01-13 00:00:00', 'completed', 30.00, '20:36:25'),
(9, 'Paper File', 1, 'Neha Rao', '2026-01-13 00:00:00', 'completed', 35.00, '20:44:20'),
(10, 'Eraser', 1, 'Neha Rao', '2026-01-13 00:00:00', 'completed', 5.00, '20:44:42'),
(11, 'Sharpener', 1, 'Neha Rao', '2026-01-13 00:00:00', 'completed', 5.00, '20:44:42'),
(12, 'Highlighter', 1, 'Rajesh Puranik', '2026-01-14 00:00:00', 'confirmed', 25.00, '13:38:31'),
(13, 'Marker Pen', 1, 'Rajesh Puranik', '2026-01-14 00:00:00', 'confirmed', 30.00, '13:38:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('voyager','admin','manager','headcook','supervisor') NOT NULL DEFAULT 'voyager',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `reset_id` varchar(10) DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`, `status`, `reset_id`, `reset_expires`) VALUES
(1, 'Sujay Bhat', 'sujay@gmail.com', '$2y$10$FCBd0njTd9KDXbnRrpxBtOnIZniIEKtG3d0Nvk0loLunw9hVg6kbW', 'voyager', '2025-12-24 12:32:18', 'active', 'a591e22507', '2026-01-12 20:23:09'),
(2, 'admin', 'admin@oceanease.com', '$2y$10$JujIOCvcLDh26uppOQcC9.DqPYb/Y2CyGHi9/luuTJaFV04VoYClW', 'admin', '2025-12-24 15:41:51', 'active', NULL, NULL),
(3, 'Manager', 'manager@oceanease.com', '$2y$10$oQm87g4/yMSFZIyQl5yZSOyRx/.YAO2PIq/B18o54TuZLjRLRSHWS', 'manager', '2025-12-24 15:48:41', 'active', NULL, NULL),
(5, 'supervisor', 'supervisor@oceanease.com', '$2y$10$VPgl1jtnWkxqm4P/0BLlRuogR8TxEOQxRHqFJeHX8shtIx3sYc6ne', 'supervisor', '2025-12-24 15:48:41', 'active', NULL, NULL),
(6, 'headcook', 'headcook@oceanease.com', '$2y$10$fQ7rGpGc0gtm5bLyfDKv6.H6tGmX6FF6jNImXyNaBg0X1wkBXeV0q', 'headcook', '2026-01-08 17:36:21', 'active', NULL, NULL),
(7, 'Ramesh Nayak', 'ramesh@yahoo.com', '$2y$10$wLT1YyqkgofOLdrNwEBv0O35EDopQnnsWv.NhEF6ZupZxDnmwja1e', 'voyager', '2026-01-13 14:16:33', 'active', NULL, NULL),
(8, 'Neha Rao', 'neha@yahoo.com', '$2y$10$JFDpji9tS.vLBXF62MyS3.b/7erjWyaI3jFq4CqjGeNGFRp3BjKU6', 'voyager', '2026-01-13 14:19:20', 'active', NULL, NULL),
(9, 'Rajesh Puranik', 'rajesh@gmail.com', '$2y$10$Mdk1JpNpei6zpti2eeBfjuLbWysT2nPXeLn3WPujuhxeHDdCOwWke', 'voyager', '2026-01-14 08:07:03', 'active', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fitness_bookings`
--
ALTER TABLE `fitness_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `party_hall_bookings`
--
ALTER TABLE `party_hall_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resort_movie_bookings`
--
ALTER TABLE `resort_movie_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `salon_bookings`
--
ALTER TABLE `salon_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `stationery_items`
--
ALTER TABLE `stationery_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stationery_orders`
--
ALTER TABLE `stationery_orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `fitness_bookings`
--
ALTER TABLE `fitness_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `party_hall_bookings`
--
ALTER TABLE `party_hall_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `resort_movie_bookings`
--
ALTER TABLE `resort_movie_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `salon_bookings`
--
ALTER TABLE `salon_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `stationery_items`
--
ALTER TABLE `stationery_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stationery_orders`
--
ALTER TABLE `stationery_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fitness_bookings`
--
ALTER TABLE `fitness_bookings`
  ADD CONSTRAINT `fitness_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `party_hall_bookings`
--
ALTER TABLE `party_hall_bookings`
  ADD CONSTRAINT `party_hall_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `resort_movie_bookings`
--
ALTER TABLE `resort_movie_bookings`
  ADD CONSTRAINT `resort_movie_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salon_bookings`
--
ALTER TABLE `salon_bookings`
  ADD CONSTRAINT `salon_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
