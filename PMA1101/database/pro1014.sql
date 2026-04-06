-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th4 06, 2026 lúc 03:56 PM
-- Phiên bản máy phục vụ: 8.4.3
-- Phiên bản PHP: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `pro1014`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `summary` text,
  `content` longtext,
  `thumbnail` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `author_id` int DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `view_count` int DEFAULT '0',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `customer_id` int DEFAULT NULL,
  `bus_company_id` int DEFAULT NULL,
  `version_id` int DEFAULT NULL,
  `booking_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `departure_date` date DEFAULT NULL,
  `original_price` decimal(15,2) DEFAULT NULL,
  `final_price` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `discount_note` text,
  `status` varchar(50) DEFAULT 'pending',
  `notes` text,
  `internal_notes` text,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `departure_id` int DEFAULT NULL,
  `adults` int NOT NULL DEFAULT '1',
  `children` int NOT NULL DEFAULT '0',
  `infants` int NOT NULL DEFAULT '0',
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_phone` varchar(50) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_address` text,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `tour_id`, `customer_id`, `bus_company_id`, `version_id`, `booking_date`, `departure_date`, `original_price`, `final_price`, `total_price`, `discount_note`, `status`, `notes`, `internal_notes`, `created_by`, `created_at`, `updated_at`, `departure_id`, `adults`, `children`, `infants`, `contact_name`, `contact_phone`, `contact_email`, `contact_address`, `expires_at`) VALUES
(128, 31, 23, NULL, 11, '2026-04-04 00:00:00', NULL, NULL, 22900000.00, 22900000.00, NULL, 'cho_xac_nhan', '', NULL, 1, '2026-04-03 19:22:51', '2026-04-03 19:22:51', 86, 1, 0, 0, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_customers`
--

CREATE TABLE `booking_customers` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `full_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `id_card` varchar(50) DEFAULT NULL,
  `special_request` text,
  `room_type` varchar(100) DEFAULT NULL,
  `is_foc` tinyint DEFAULT '0',
  `passenger_type` enum('adult','child','infant') DEFAULT 'adult',
  `payment_status` enum('unpaid','partial','paid') DEFAULT 'unpaid',
  `payment_amount` decimal(15,2) DEFAULT '0.00',
  `payment_date` datetime DEFAULT NULL,
  `checkin_status` enum('not_arrived','checked_in','absent') DEFAULT 'not_arrived',
  `checkin_time` datetime DEFAULT NULL,
  `checkin_location` varchar(255) DEFAULT NULL,
  `checkin_notes` text,
  `checked_by` int DEFAULT NULL COMMENT 'User ID của HDV check-in',
  `is_checked_in` tinyint(1) DEFAULT '0',
  `checked_in_at` datetime DEFAULT NULL,
  `room_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_price_adjustments`
--

CREATE TABLE `booking_price_adjustments` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `adjust_type` enum('discount_cash','discount_percent','foc','surcharge','gift','other') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(500) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_suppliers_assignment`
--

CREATE TABLE `booking_suppliers_assignment` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `price` decimal(15,2) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bus_companies`
--

CREATE TABLE `bus_companies` (
  `id` int NOT NULL,
  `company_code` varchar(50) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `business_license` varchar(100) DEFAULT NULL,
  `vehicle_type` text,
  `vehicle_brand` varchar(100) DEFAULT NULL,
  `total_vehicles` int DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active',
  `rating` decimal(3,2) DEFAULT '5.00',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `bus_companies`
--

INSERT INTO `bus_companies` (`id`, `company_code`, `company_name`, `contact_person`, `phone`, `email`, `address`, `business_license`, `vehicle_type`, `vehicle_brand`, `total_vehicles`, `status`, `rating`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'NX0001', 'Nhà xe Phương Trang', 'Nguyễn Văn A', '0901234567', 'phuongtrang@example.com', NULL, NULL, NULL, NULL, 50, 'active', 5.00, NULL, '2025-12-08 16:21:09', '2025-12-08 16:21:09'),
(2, 'NX0002', 'Nhà xe Mai Linh', 'Trần Thị B', '0912345678', 'mailinh@example.com', NULL, NULL, NULL, NULL, 30, 'active', 5.00, NULL, '2025-12-08 16:21:09', '2025-12-08 16:21:09'),
(3, 'NX0003', 'Nhà xe Kumho', 'Lê Văn C', '0923456789', 'kumho@example.com', NULL, NULL, NULL, NULL, 40, 'active', 5.00, NULL, '2025-12-08 16:21:09', '2025-12-08 16:21:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `financial_reports`
--

CREATE TABLE `financial_reports` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `total_revenue` decimal(15,2) DEFAULT NULL,
  `total_expense` decimal(15,2) DEFAULT NULL,
  `profit` decimal(15,2) DEFAULT NULL,
  `report_date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `guides`
--

CREATE TABLE `guides` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `experience_years` int DEFAULT NULL,
  `rating` float DEFAULT '0',
  `health_status` varchar(100) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `guides`
--

INSERT INTO `guides` (`id`, `user_id`, `languages`, `experience_years`, `rating`, `health_status`, `notes`) VALUES
(5, 19, 'Tiếng Việt', 0, 5, 'Tốt', 'Khởi tạo tự động từ hệ thống'),
(6, 20, 'Tiếng Việt', 0, 5, 'Tốt', 'Khởi tạo tự động từ hệ thống'),
(7, 21, 'Tiếng Việt', 0, 5, 'Tốt', 'Khởi tạo tự động từ hệ thống'),
(8, 22, 'Tiếng Việt', 0, 5, 'Tốt', 'Khởi tạo tự động từ hệ thống');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `itineraries`
--

CREATE TABLE `itineraries` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `day_label` varchar(100) DEFAULT NULL,
  `day_number` int DEFAULT NULL,
  `time_start` time DEFAULT NULL,
  `time_end` time DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `activities` text,
  `image_url` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `itineraries`
--

INSERT INTO `itineraries` (`id`, `tour_id`, `day_label`, `day_number`, `time_start`, `time_end`, `title`, `description`, `activities`, `image_url`) VALUES
(157, 26, 'Ngày 1', 1, NULL, NULL, 'TP. HCM – SB Nội Bài (Hà Nội) – Phú Thọ – Sapa', 'Quý khách tập trung tại sân bay Tân Sơn Nhất (ga trong nước), hướng dẫn viên làm thủ tục cho Quý khách đáp chuyến bay đi Hà Nội. Đến sân bay Nội Bài, xe đón đoàn khởi hành đi Sapa (Lào Cai). Trên đường dừng chân tham quan:\n\nGiữa mái đình rêu phong tại Đình cổ Hùng Lô hơn 300 năm tuổi, không chỉ là nơi bạn tìm lại ký ức văn hóa, mà còn là cơ hội để bạn hòa mình vào không gian di sản qua từng lời hát Xoan du dương, tạo nên kỷ niệm khó quên.\nĐi bộ qua Làng cổ Hùng Lô - nơi những ngôi nhà gỗ 200 năm tuổi cùng nghề truyền thống như sản xuất mỳ gạo, bánh Chưng, bánh Giầy sẽ đưa bạn trở về với ký ức dân gian.\nThưởng thức bánh sắn, gà đồi và nhiều món ngon đậm đà hương vị đất Tổ tại nhà hàng địa phương\nĐến Sapa, Quý khách dùng cơm tối và nhận phòng nghỉ ngơi. Buổi tối, Quý khách tự do dạo phố ngắm nhà thờ Đá Sapa, tự do thưởng thức đặc sản vùng cao tại Chợ đêm Sapa như thịt lợn cắp nách nướng, trứng nướng, và rượu táo mèo.\n\nNghỉ đêm tại Sapa.', 'Quý khách tập trung tại sân bay Tân Sơn Nhất (ga trong nước), hướng dẫn viên làm thủ tục cho Quý khách đáp chuyến bay đi Hà Nội. Đến sân bay Nội Bài, xe đón đoàn khởi hành đi Sapa (Lào Cai). Trên đường dừng chân tham quan:\n\nGiữa mái đình rêu phong tại Đình cổ Hùng Lô hơn 300 năm tuổi, không chỉ là nơi bạn tìm lại ký ức văn hóa, mà còn là cơ hội để bạn hòa mình vào không gian di sản qua từng lời hát Xoan du dương, tạo nên kỷ niệm khó quên.\nĐi bộ qua Làng cổ Hùng Lô - nơi những ngôi nhà gỗ 200 năm tuổi cùng nghề truyền thống như sản xuất mỳ gạo, bánh Chưng, bánh Giầy sẽ đưa bạn trở về với ký ức dân gian.\nThưởng thức bánh sắn, gà đồi và nhiều món ngon đậm đà hương vị đất Tổ tại nhà hàng địa phương\nĐến Sapa, Quý khách dùng cơm tối và nhận phòng nghỉ ngơi. Buổi tối, Quý khách tự do dạo phố ngắm nhà thờ Đá Sapa, tự do thưởng thức đặc sản vùng cao tại Chợ đêm Sapa như thịt lợn cắp nách nướng, trứng nướng, và rượu táo mèo.\n\nNghỉ đêm tại Sapa.', ''),
(158, 26, 'Ngày 2', 2, NULL, NULL, 'Sapa – Fansipan Legend', 'Sau khi dùng bữa sáng, xe đưa Quý khách ra Ga Sapa trải nghiệm đến khu du lịch Fansipan Legend bằng Tàu hỏa leo núi Mường Hoa hiện đại nhất Việt Nam với tổng chiều dài gần 2000m, di chuyển qua thung lũng Mường Hoa để chiêm ngưỡng bức tranh thiên nhiên tuyệt đẹp:\n\nTrải nghiệm không gian văn hóa bản địa tại Bản Mây - “Ngôi làng giữa mây” độc đáo tại SunWorld Fansipan. Tham gia vào các chương trình biểu diễn văn nghệ đặc sắc hay tìm hiểu về con người, phong tục, những nét đẹp trong sinh hoạt, ẩm thực và nghệ thuật của đồng bào dân tộc H’Mông, Dao, Tày…\nDạo vườn hồng cổ và thử cưỡi ngựa giữa khung cảnh núi rừng Fansipan (tự túc).\nChinh phục đỉnh Fansipan 3.143m bằng cáp treo hiện đại (chi phí tự túc), chiêm bái chùa Trình hoặc Bích Vân Thiền Tự cầu bình an.\nBản Cát Cát - đẹp như một bức tranh giữa vùng phố cổ Sapa, nơi đây thu hút du khách bởi cầu treo, thác nước, guồng nước và những mảng màu hoa mê hoặc du khách khi lạc bước đến đây. Thăm những nếp nhà của người Mông, Dao, Giáy trong bản, du khách sẽ không khỏi ngỡ ngàng trước vẻ đẹp mộng mị của một trong những ngôi làng cổ đẹp nhất Sapa. Tại đây Quý khách xem chương trình biểu diễn văn nghệ của người H\'Mông.\nNghỉ đêm tại Sapa.\n\nLưu ý:\n\n- Trong trường hợp, Fansipan không hoạt động chương trình sẽ được thay thế bằng điểm tham quan Cầu Kính Rồng Mây (không bao gồm vé tham quan).\n\n- Chương trình tặng vé trải nghiệm tàu hỏa leo núi Mường Hoa sẽ không được hoàn lại chi phí và không thay thế dịch vụ khác trong trường hợp Quý khách không tham gia hoặc phải hủy do điều kiện khách quan về thời tiết, cung đường, thông báo từ đơn vị cung ứng.', 'Sau khi dùng bữa sáng, xe đưa Quý khách ra Ga Sapa trải nghiệm đến khu du lịch Fansipan Legend bằng Tàu hỏa leo núi Mường Hoa hiện đại nhất Việt Nam với tổng chiều dài gần 2000m, di chuyển qua thung lũng Mường Hoa để chiêm ngưỡng bức tranh thiên nhiên tuyệt đẹp:\n\nTrải nghiệm không gian văn hóa bản địa tại Bản Mây - “Ngôi làng giữa mây” độc đáo tại SunWorld Fansipan. Tham gia vào các chương trình biểu diễn văn nghệ đặc sắc hay tìm hiểu về con người, phong tục, những nét đẹp trong sinh hoạt, ẩm thực và nghệ thuật của đồng bào dân tộc H’Mông, Dao, Tày…\nDạo vườn hồng cổ và thử cưỡi ngựa giữa khung cảnh núi rừng Fansipan (tự túc).\nChinh phục đỉnh Fansipan 3.143m bằng cáp treo hiện đại (chi phí tự túc), chiêm bái chùa Trình hoặc Bích Vân Thiền Tự cầu bình an.\nBản Cát Cát - đẹp như một bức tranh giữa vùng phố cổ Sapa, nơi đây thu hút du khách bởi cầu treo, thác nước, guồng nước và những mảng màu hoa mê hoặc du khách khi lạc bước đến đây. Thăm những nếp nhà của người Mông, Dao, Giáy trong bản, du khách sẽ không khỏi ngỡ ngàng trước vẻ đẹp mộng mị của một trong những ngôi làng cổ đẹp nhất Sapa. Tại đây Quý khách xem chương trình biểu diễn văn nghệ của người H\'Mông.\nNghỉ đêm tại Sapa.\n\nLưu ý:\n\n- Trong trường hợp, Fansipan không hoạt động chương trình sẽ được thay thế bằng điểm tham quan Cầu Kính Rồng Mây (không bao gồm vé tham quan).\n\n- Chương trình tặng vé trải nghiệm tàu hỏa leo núi Mường Hoa sẽ không được hoàn lại chi phí và không thay thế dịch vụ khác trong trường hợp Quý khách không tham gia hoặc phải hủy do điều kiện khách quan về thời tiết, cung đường, thông báo từ đơn vị cung ứng.', ''),
(159, 26, 'Ngày 3', 3, NULL, NULL, 'Sapa – Lào Cai – Hà Nội', 'Quý khách dùng bữa sáng và trả phòng khách sạn. Xe đưa Quý khách đi đến điểm café check-in Moana - Giường trên không, nhà bong bóng, và khách sạn ngàn sao sẽ đưa bạn vào một thế giới mơ mộng giữa những dãy núi mờ ảo. Và, đừng quên lưu lại những bức ảnh \"nghìn like\" giữa không gian đầy mây và núi.\n\nĐến giờ hẹn, xe khởi hành đưa Quý khách về Hà Nội, trên đường dừng tham quan mua sắm đặc sản và dùng cơm trưa tại nhà hàng địa phương.\n\nĐến Hà Nội, Quý khách nhận phòng, tự do dạo chơi tại Phố Tạ Hiện, nơi cuộc sống về đêm sôi động và nhộn nhịp. Hoặc tìm một góc cà phê để thưởng thức không khí phố cổ, ngắm nhìn hình ảnh thủ đô yên bình từ một góc nhìn mới.\n\nNghỉ đêm tại Hà Nội.\n\nMột số chương trình gợi ý: (các chương trình có ngày diễn ra cố định trong tuần, Quý khách tự túc phương tiện và chi phí).\n\nHành trình 1: “Giải Mã Hoàng Thành Thăng Long” (Thứ 6, Thứ 7)\nTrải nghiệm không gian Hoàng Thành về đêm với nhiều hoạt động hấp dẫn:Lễ dâng hương, chụp ảnh cùng các cung nữ và lính canh trong trang phục cổ xưa; Chiêm ngưỡng trình diễn nghệ thuật trên sàn kính khảo cổ Đoan Môn; Tham gia trò chơi giải mã với hiệu ứng laze ấn tượng và nhận những phần quà ý nghĩa.\n\nHành trình 2: “Đêm Văn Miếu - Quốc Tử Giám” (Thứ 4, Thứ 7)\nTrải nghiệm công nghệ trình chiếu hiện đại, tái hiện lịch sử và văn hóa một cách sống động; tạo cho du khách cảm giác đắm chìm trong không gian kỳ ảo và lung linh của Văn Miếu về đêm.\n\nHành trình 3: “Đêm Thiêng Liêng” tại Nhà Tù Hỏa Lò (Thứ 6, Thứ 7, Chủ Nhật)\nHành trình trở về quá khứ, sống lại những năm tháng hào hùng của dân tộc, và cảm nhận được tinh thần bất khuất của những chiến sĩ cách mạng qua những câu chuyện đầy xúc động.\n\nNgoài ra, nếu đi vào những ngày khác, Quý khách có thể lựa chọn:\n\nTour đêm Đền Ngọc Sơn - khám phá vẻ đẹp huyền bí của ngôi đền linh thiêng giữa lòng hồ Hoàn Kiếm.\nTour xe buýt 2 tầng - tận hưởng không khí về đêm, chiêm ngưỡng phố phường Hà Nội rực rỡ ánh đèn.\nThưởng thức đặc sản Hà Nội như: bún ốc nguội, chả rươi, kem Tràng Tiền…', 'Quý khách dùng bữa sáng và trả phòng khách sạn. Xe đưa Quý khách đi đến điểm café check-in Moana - Giường trên không, nhà bong bóng, và khách sạn ngàn sao sẽ đưa bạn vào một thế giới mơ mộng giữa những dãy núi mờ ảo. Và, đừng quên lưu lại những bức ảnh \"nghìn like\" giữa không gian đầy mây và núi.\n\nĐến giờ hẹn, xe khởi hành đưa Quý khách về Hà Nội, trên đường dừng tham quan mua sắm đặc sản và dùng cơm trưa tại nhà hàng địa phương.\n\nĐến Hà Nội, Quý khách nhận phòng, tự do dạo chơi tại Phố Tạ Hiện, nơi cuộc sống về đêm sôi động và nhộn nhịp. Hoặc tìm một góc cà phê để thưởng thức không khí phố cổ, ngắm nhìn hình ảnh thủ đô yên bình từ một góc nhìn mới.\n\nNghỉ đêm tại Hà Nội.\n\nMột số chương trình gợi ý: (các chương trình có ngày diễn ra cố định trong tuần, Quý khách tự túc phương tiện và chi phí).\n\nHành trình 1: “Giải Mã Hoàng Thành Thăng Long” (Thứ 6, Thứ 7)\nTrải nghiệm không gian Hoàng Thành về đêm với nhiều hoạt động hấp dẫn:Lễ dâng hương, chụp ảnh cùng các cung nữ và lính canh trong trang phục cổ xưa; Chiêm ngưỡng trình diễn nghệ thuật trên sàn kính khảo cổ Đoan Môn; Tham gia trò chơi giải mã với hiệu ứng laze ấn tượng và nhận những phần quà ý nghĩa.\n\nHành trình 2: “Đêm Văn Miếu - Quốc Tử Giám” (Thứ 4, Thứ 7)\nTrải nghiệm công nghệ trình chiếu hiện đại, tái hiện lịch sử và văn hóa một cách sống động; tạo cho du khách cảm giác đắm chìm trong không gian kỳ ảo và lung linh của Văn Miếu về đêm.\n\nHành trình 3: “Đêm Thiêng Liêng” tại Nhà Tù Hỏa Lò (Thứ 6, Thứ 7, Chủ Nhật)\nHành trình trở về quá khứ, sống lại những năm tháng hào hùng của dân tộc, và cảm nhận được tinh thần bất khuất của những chiến sĩ cách mạng qua những câu chuyện đầy xúc động.\n\nNgoài ra, nếu đi vào những ngày khác, Quý khách có thể lựa chọn:\n\nTour đêm Đền Ngọc Sơn - khám phá vẻ đẹp huyền bí của ngôi đền linh thiêng giữa lòng hồ Hoàn Kiếm.\nTour xe buýt 2 tầng - tận hưởng không khí về đêm, chiêm ngưỡng phố phường Hà Nội rực rỡ ánh đèn.\nThưởng thức đặc sản Hà Nội như: bún ốc nguội, chả rươi, kem Tràng Tiền…', ''),
(160, 26, 'Ngày 4', 4, NULL, NULL, 'Hà Nội – Hạ Long – Yên Tử', 'Quý khách dùng bữa sáng và trả phòng khách sạn. Xe khởi hành đưa Quý khách đi tham quan Hồ Hoàn Kiếm ngắm bên ngoài Tháp Rùa, Đền Ngọc Sơn, Cầu Thê Húc.\n\nQuý khách tiếp tục khởi hành đi Hạ Long theo đường cao tốc Hải Phòng – Hạ Long, trên đường ngắm cảnh Bạch Đằng Giang.\n\nTiếp tục hành trình, xe đưa Quý Khách đi Hạ Long, trên đường tham quan Danh thắng Yên Tử:\n\nTrải nghiệm văn hóa, kiến trúc và lịch sử Việt Nam tại Yên Tử. Ghé thăm Cổng khai tâm, Gương thiền, Suối giải oan, và hồ Đoạn Nguyệt – những địa điểm check-in độc đáo đầy ý nghĩa.\nTham gia các hoạt động dân gian (chi phí tự túc): in tranh Đông Hồ, khắc Mộc bản, làm nón lá, vẽ chữ trên đá, nặn tò he tại Làng Nương Tử.\nQuý khách đi cáp treo du ngoạn thắng cảnh thiên nhiên Đông Yên Tử (chi phí cáp treo tự túc), chiêm bái Chùa Một Mái và Chùa Hoa Yên, nơi có di tích lịch sử của Phật hoàng Trần Nhân Tông. Vào mùa mai vàng (tháng 2-3), Yên Tử khoác lên mình chiếc áo cà sa vàng rực rỡ, thu hút hàng nghìn du khách đến thưởng ngoạn vẻ đẹp thiên nhiên hùng vĩ.\nĐến Hạ Long, xe đưa Quý khách đi Check-in Ngọn Hải Đăng bên vịnh Hạ Long và thưởng thức lẩu hải sản 9 tầng đặc sắc.\n\nQuý khách nhận phòng nghỉ ngơi, tự do khám phá Hạ Long về đêm.\n\nNghỉ đêm tại Hạ Long.\n\nHoặc lựa chọn một số dịch vụ khám phá Hạ Long về đêm (tự túc phương tiện và chi phí tham quan):\n\n-Trải nghiệm dịch vụ Cáp Treo Nữ Hoàng tại Sun World Hạ Long Complex trên Núi Ba Đèo, chiêm ngưỡng cảnh đẹp về đêm của thành phố Hạ Long dưới ánh đèn lung linh (thời gian hoạt động cáp treo dự kiến từ 10h đến 18h các ngày thứ 7 và CN).', 'Quý khách dùng bữa sáng và trả phòng khách sạn. Xe khởi hành đưa Quý khách đi tham quan Hồ Hoàn Kiếm ngắm bên ngoài Tháp Rùa, Đền Ngọc Sơn, Cầu Thê Húc.\n\nQuý khách tiếp tục khởi hành đi Hạ Long theo đường cao tốc Hải Phòng – Hạ Long, trên đường ngắm cảnh Bạch Đằng Giang.\n\nTiếp tục hành trình, xe đưa Quý Khách đi Hạ Long, trên đường tham quan Danh thắng Yên Tử:\n\nTrải nghiệm văn hóa, kiến trúc và lịch sử Việt Nam tại Yên Tử. Ghé thăm Cổng khai tâm, Gương thiền, Suối giải oan, và hồ Đoạn Nguyệt – những địa điểm check-in độc đáo đầy ý nghĩa.\nTham gia các hoạt động dân gian (chi phí tự túc): in tranh Đông Hồ, khắc Mộc bản, làm nón lá, vẽ chữ trên đá, nặn tò he tại Làng Nương Tử.\nQuý khách đi cáp treo du ngoạn thắng cảnh thiên nhiên Đông Yên Tử (chi phí cáp treo tự túc), chiêm bái Chùa Một Mái và Chùa Hoa Yên, nơi có di tích lịch sử của Phật hoàng Trần Nhân Tông. Vào mùa mai vàng (tháng 2-3), Yên Tử khoác lên mình chiếc áo cà sa vàng rực rỡ, thu hút hàng nghìn du khách đến thưởng ngoạn vẻ đẹp thiên nhiên hùng vĩ.\nĐến Hạ Long, xe đưa Quý khách đi Check-in Ngọn Hải Đăng bên vịnh Hạ Long và thưởng thức lẩu hải sản 9 tầng đặc sắc.\n\nQuý khách nhận phòng nghỉ ngơi, tự do khám phá Hạ Long về đêm.\n\nNghỉ đêm tại Hạ Long.\n\nHoặc lựa chọn một số dịch vụ khám phá Hạ Long về đêm (tự túc phương tiện và chi phí tham quan):\n\n-Trải nghiệm dịch vụ Cáp Treo Nữ Hoàng tại Sun World Hạ Long Complex trên Núi Ba Đèo, chiêm ngưỡng cảnh đẹp về đêm của thành phố Hạ Long dưới ánh đèn lung linh (thời gian hoạt động cáp treo dự kiến từ 10h đến 18h các ngày thứ 7 và CN).', ''),
(161, 26, 'Ngày 5', 5, NULL, NULL, 'Vịnh Hạ Long – SB Nội Bài (Hà Nội)', 'Quý khách ăn sáng và trả phòng khách sạn. Xe đưa quý khách ra bến tàu, Quý khách xuống thuyền đi du ngoạn Vịnh Hạ Long - Thắng cảnh thiên nhiên tuyệt đẹp và vô cùng sống động, được UNESCO công nhận là di sản thiên nhiên Thế giới năm 1994.\n\nKhám phá Động Thiên Cung – một trong những động đẹp nhất tại Hạ Long, nơi thạch nhũ lung linh hòa quyện với ánh sáng huyền ảo. Du ngoạn Vịnh Hạ Long, chiêm ngưỡng những hòn đảo lớn nhỏ nổi bật như Hòn Gà Chọi và Hòn Lư Hương, ..\nXe khởi hành đưa Quý khách ra sân bay Nội Bài làm thủ tục đón chuyến bay về Tp.HCM. Chia tay Quý khách và kết thúc chương trình du lịch tại sân bay Tân Sơn Nhất.', 'Quý khách ăn sáng và trả phòng khách sạn. Xe đưa quý khách ra bến tàu, Quý khách xuống thuyền đi du ngoạn Vịnh Hạ Long - Thắng cảnh thiên nhiên tuyệt đẹp và vô cùng sống động, được UNESCO công nhận là di sản thiên nhiên Thế giới năm 1994.\n\nKhám phá Động Thiên Cung – một trong những động đẹp nhất tại Hạ Long, nơi thạch nhũ lung linh hòa quyện với ánh sáng huyền ảo. Du ngoạn Vịnh Hạ Long, chiêm ngưỡng những hòn đảo lớn nhỏ nổi bật như Hòn Gà Chọi và Hòn Lư Hương, ..\nXe khởi hành đưa Quý khách ra sân bay Nội Bài làm thủ tục đón chuyến bay về Tp.HCM. Chia tay Quý khách và kết thúc chương trình du lịch tại sân bay Tân Sơn Nhất.', ''),
(162, 27, 'Ngày 1', 1, NULL, NULL, 'HÀ NỘI – NAGOYA - KYOTO - GIFU', 'ĐÊM TRƯỚC NGÀY 01\n\nQuý khách tập trung tại công ty Vietravel (03 Hai Bà Trưng, Hoàn Kiếm). Xe và Hướng dẫn viên đón Quý khách đến sân bay Nội Bài để làm thủ tục đáp chuyến bay đi Nagoya - Nhật Bản.\n\nQuý khách nghỉ đêm trên máy bay.\n\nNGÀY 01: NAGOYA – KYOTO - GIFU\n\nĐoàn đáp sân bay Nagoya, làm thủ tục nhập cảnh và ăn sáng tự túc tại sân bay.\n\nChùa vàng - Golden Pavillion Temple – toàn bộ ngôi chùa được dát bằng vàng lá tuyệt đẹp\nĂn trưa tại nhà hàng\n\nBuổi chiều, đoàn ghé thăm:\n\n“Chùa Thanh Thuỷ - Kiyomizu Dera” với lối kiến trúc độc đáo toàn bằng gỗ, nằm trên vùng cao ngọn đồi Higashiyama\nQuý khách dùng bữa tối, di chuyển về khu vực Gifu và nhận phòng khách sạn.\n\nNghỉ tại khách sạn ở khu vực Seki- Gifu', 'ĐÊM TRƯỚC NGÀY 01\n\nQuý khách tập trung tại công ty Vietravel (03 Hai Bà Trưng, Hoàn Kiếm). Xe và Hướng dẫn viên đón Quý khách đến sân bay Nội Bài để làm thủ tục đáp chuyến bay đi Nagoya - Nhật Bản.\n\nQuý khách nghỉ đêm trên máy bay.\n\nNGÀY 01: NAGOYA – KYOTO - GIFU\n\nĐoàn đáp sân bay Nagoya, làm thủ tục nhập cảnh và ăn sáng tự túc tại sân bay.\n\nChùa vàng - Golden Pavillion Temple – toàn bộ ngôi chùa được dát bằng vàng lá tuyệt đẹp\nĂn trưa tại nhà hàng\n\nBuổi chiều, đoàn ghé thăm:\n\n“Chùa Thanh Thuỷ - Kiyomizu Dera” với lối kiến trúc độc đáo toàn bằng gỗ, nằm trên vùng cao ngọn đồi Higashiyama\nQuý khách dùng bữa tối, di chuyển về khu vực Gifu và nhận phòng khách sạn.\n\nNghỉ tại khách sạn ở khu vực Seki- Gifu', ''),
(163, 27, 'Ngày 2', 2, NULL, NULL, 'GIFU- SHIRAKAWAGO- KAMAKOCHI- MATSUMOTO', 'Ăn sáng tại khách sạn. Đoàn tiến hành tham quan, khám phá:\n\nTham quan Shirakawa-go, ngôi làng đẹp như bước ra từ cổ tích, nằm ở chân núi Haku-san, tỉnh Gifu. Điểm đặc biệt của ngôi làng này là những ngôi nhà gỗ mang kiến trúc gasshō-zukuri có phần mái nhà dốc lợp bằng cỏ tranh, nhìn giống như các bàn tay cầu nguyện, ngôi làng được UNESCO công nhận là di sản thế giới từ năm 1995 và là nơi hoạ sĩ Fujiko Fujio thai nghén và cho ra đời bộ truyện tranh kinh điển Doraemon.\nĂn trưa tại nhà hàng, thưởng thức món thịt bò Hida nổi tiếng\n\nSau bữa trưa, đoàn di chuyển tham quan Kamikochi- nằm ở độ cao khoảng 1.500m so với mực nước biển, thung lũng Kamikochi thuộc tỉnh Nagano được ví như \"Thụy Sĩ của Nhật Bản\" nhờ cảnh quan thiên nhiên hùng vĩ và khí hậu trong lành. Đây là một trong những điểm đến đẹp nhất của dãy Alps Nhật Bản, thu hút du khách với những ngọn núi cao chót vót, rừng cây xanh bạt ngàn và dòng sông Azusa trong vắt.\nKamikochi nổi bật với:\n✅ Cầu Kappa – Biểu tượng của Kamikochi, nơi lý tưởng để chiêm ngưỡng dãy núi Hotaka.\n\n✅ Hồ Taisho – Mặt hồ phản chiếu hình ảnh núi lửa Yake-dake, tạo nên khung cảnh thơ mộng.\n\n✅ Rừng nguyên sinh & hệ sinh thái đa dạng – Nơi sinh sống của nhiều loài động thực vật quý hiếm.\n\nSau khi tham quan, trên đường về khách sạn, quý khách dừng chân mua sắm tại Aeon Mall Matsumoto ( nếu thời gian cho phép)\n\nĂn tối tại nhà hàng\n\nĐoàn nhận phòng khách sạn ở Matsumoto ( Khách sạn Alpico Matsumoto hoặc tương đương)', 'Ăn sáng tại khách sạn. Đoàn tiến hành tham quan, khám phá:\n\nTham quan Shirakawa-go, ngôi làng đẹp như bước ra từ cổ tích, nằm ở chân núi Haku-san, tỉnh Gifu. Điểm đặc biệt của ngôi làng này là những ngôi nhà gỗ mang kiến trúc gasshō-zukuri có phần mái nhà dốc lợp bằng cỏ tranh, nhìn giống như các bàn tay cầu nguyện, ngôi làng được UNESCO công nhận là di sản thế giới từ năm 1995 và là nơi hoạ sĩ Fujiko Fujio thai nghén và cho ra đời bộ truyện tranh kinh điển Doraemon.\nĂn trưa tại nhà hàng, thưởng thức món thịt bò Hida nổi tiếng\n\nSau bữa trưa, đoàn di chuyển tham quan Kamikochi- nằm ở độ cao khoảng 1.500m so với mực nước biển, thung lũng Kamikochi thuộc tỉnh Nagano được ví như \"Thụy Sĩ của Nhật Bản\" nhờ cảnh quan thiên nhiên hùng vĩ và khí hậu trong lành. Đây là một trong những điểm đến đẹp nhất của dãy Alps Nhật Bản, thu hút du khách với những ngọn núi cao chót vót, rừng cây xanh bạt ngàn và dòng sông Azusa trong vắt.\nKamikochi nổi bật với:\n✅ Cầu Kappa – Biểu tượng của Kamikochi, nơi lý tưởng để chiêm ngưỡng dãy núi Hotaka.\n\n✅ Hồ Taisho – Mặt hồ phản chiếu hình ảnh núi lửa Yake-dake, tạo nên khung cảnh thơ mộng.\n\n✅ Rừng nguyên sinh & hệ sinh thái đa dạng – Nơi sinh sống của nhiều loài động thực vật quý hiếm.\n\nSau khi tham quan, trên đường về khách sạn, quý khách dừng chân mua sắm tại Aeon Mall Matsumoto ( nếu thời gian cho phép)\n\nĂn tối tại nhà hàng\n\nĐoàn nhận phòng khách sạn ở Matsumoto ( Khách sạn Alpico Matsumoto hoặc tương đương)', ''),
(164, 27, 'Ngày 3', 3, NULL, NULL, 'MATSUMOTO – KAWAGUCHIKO', 'Quý khách ăn sáng tại khách sạn.\n\nĐoàn chụp ảnh bên ngoài Lâu đài Matsumoto- được mệnh danh là lâu đài đẹp nhất thời trung cổ.\nTham quan nhà máy rượu- trải nghiệm xem cách sản xuất rượu của người dân Nhật Bản.\nĂn trưa tại nhà hàng\n\nBuổi chiều đoàn di chuyển về Kawaguchiko- thăm quan hồ Kawaguchiko - là một trong Ngũ Hồ Phú Sĩ và là hồ nổi tiếng nhất để chiêm ngưỡng vẻ đẹp của Núi Phú Sĩ. Đây là điểm đến hấp dẫn quanh năm với phong cảnh tuyệt đẹp, không gian yên bình và nhiều hoạt động du lịch thú vị. Từ đây, quý khách có thể chụp ảnh Núi Phú Sĩ.\n\nĂn tối tại nhà hàng/ hoặc khách sạn.\n\nĐoàn nhận phòng khách sạn, thư giãn và thoả sức Sauna tại bồn nước suối khoáng nóng thiên nhiên trong quần thể khu du lịch.\n\nNghỉ đêm tại khách sạn tại khu vực Kawaguchiko ( Khách sạn Jiragon No Fuji No Yokata hoặc tương đương)', 'Quý khách ăn sáng tại khách sạn.\n\nĐoàn chụp ảnh bên ngoài Lâu đài Matsumoto- được mệnh danh là lâu đài đẹp nhất thời trung cổ.\nTham quan nhà máy rượu- trải nghiệm xem cách sản xuất rượu của người dân Nhật Bản.\nĂn trưa tại nhà hàng\n\nBuổi chiều đoàn di chuyển về Kawaguchiko- thăm quan hồ Kawaguchiko - là một trong Ngũ Hồ Phú Sĩ và là hồ nổi tiếng nhất để chiêm ngưỡng vẻ đẹp của Núi Phú Sĩ. Đây là điểm đến hấp dẫn quanh năm với phong cảnh tuyệt đẹp, không gian yên bình và nhiều hoạt động du lịch thú vị. Từ đây, quý khách có thể chụp ảnh Núi Phú Sĩ.\n\nĂn tối tại nhà hàng/ hoặc khách sạn.\n\nĐoàn nhận phòng khách sạn, thư giãn và thoả sức Sauna tại bồn nước suối khoáng nóng thiên nhiên trong quần thể khu du lịch.\n\nNghỉ đêm tại khách sạn tại khu vực Kawaguchiko ( Khách sạn Jiragon No Fuji No Yokata hoặc tương đương)', ''),
(165, 27, 'Ngày 4', 4, NULL, NULL, 'NÚI PHÚ SĨ - TOKYO', 'Quý khách ăn sáng tại khách sạn.\n\nTrong giai đoạn hoa anh đào, Quý khách sẽ tham quan:\n\nNúi Phú Sĩ: biểu tượng của đất nước mặt trời mọc, được công nhận là di sản văn hóa thế giới (tại thời điểm thực tế, đoàn sẽ tham quan trạm 5 (tùy theo tình hình thời tiết thực tế mà Chính phủ Nhật Bản cho phép lên trạm tham quan)…\nĐoàn di chuyển về Tokyo\n\nĂn trưa tại nhà hàng, sau đó tiếp tục tham quan:\n\nĐền Asakusa Kannon: Ngôi đền thờ cổ nhất tại Tokyo, nơi diễn ra các lễ hội lớn của quốc gia cùng với truyền thuyết ra đời ngôi đền bí ẩn cũng như ghé thăm khu phố Nakamise-dori cảm nhận một Tokyo cổ xưa tại một trong những con phố mua sắm lâu đời nhất của Nhật Bản. Từ đây đoàn có thể chụp ảnh Tháp Tokyo Sky Tree.\nNgắm hoa anh đào tại Sumida River (Hoặc Ueno Park): Bạn có thể chụp những bức ảnh tuyệt đẹp về hoa anh đào ở đây với hơn 600 cây anh đào Nhật Bản và hình ảnh của Tokyo Skytree cao chót vót ở hậu cảnh\nCông viên Ueno - Công viên Ueno nổi tiếng với các bảo tàng, đặc biệt là bảo tàng nghệ thuật như Bảo tàng Quốc gia Tokyo, có đền và vườn thú trong đó có gấu trúc thu hút sự chú ý của khách đến thăm. Đặc biệt, nơi đây còn nổi tiếng với 1,000 cây Anh Đào, cũng là nơi người Nhật, nhất là các bạn trẻ, thường hẹn hò ngắm cảnh, chụp hình hay thưởng thức rượu sake bên gốc anh đào.\nĐoàn tham quan, mua sắm tại khu mua sắm lớn nhất Tokyo Giza hoặc Akihabara (nếu còn thời gian)\nĂn tối tại nhà hàng.\n\nNghỉ đêm tại khách sạn tại Tokyo.\n\nCác giai đoạn khác trong năm, đoàn tham quan:\n\nLàng Cổ Oshino Hakkai - ngôi làng yên bình nép dưới chân núi Phú Sĩ, cho đến ngày nay vẫn giữ nguyên nét kiến trúc truyền thống Nhật và phảng phất nét hoài cổ của xứ Phù Tang trong ký ức.\nĐoàn shopping tại khu mua sắm miễn thuế Gotemba Outlet ( nếu thời gian cho phép)\n\nĂn trưa tại nhà hàng\n\nSau đó, đoàn di chuyển đến Tokyo, tham quan:\n\nĐền Asakusa Kannon - Ngôi đền thờ cổ nhất tại Tokyo, nơi diễn ra các lễ hội lớn của quốc gia cùng với truyền thuyết ra đời ngôi đền bí ẩn. Từ đây đoàn có thể chụp ảnh Tháp Tokyo Sky Tree.\nĐoàn tham quan, mua sắm tại khu mua sắm lớn nhất Tokyo Giza hoặc Akihabara\n\nĂn tối tại nhà hàng.\n\nNghỉ đêm tại khách sạn tại Tokyo.\n\nGiai đoạn trượt tuyết, đoàn tham quan:\n\nNúi Phú Sĩ: biểu tượng của đất nước mặt trời mọc, được công nhận là di sản văn hóa thế giới (tại thời điểm thực tế, đoàn sẽ tham quan trạm 5 (tùy theo tình hình thời tiết thực tế mà Chính phủ Nhật Bản cho phép lên trạm tham quan)…\nĐoàn đi trải nghiệm trượt tuyết ở khu nghỉ dưỡng trượt tuyết Fujiten Snow Resort (giai đoạn tháng 1 – tháng 2) nằm dọc phía bắc núi Phú Sĩ. Quý khách tham quan, vui chơi và tận hưởng các hoạt động thú vị và ngắm ngọn núi huyền thoại của xứ sở Phù Tang. (Chưa bao gồm chi phí thuê dụng cụ trượt tuyết)\nĂn trưa tại nhà hàng, sau đó di chuyển đến Tokyo tiếp tục tham quan:\n\nĐền Asakusa Kannon: Ngôi đền thờ cổ nhất tại Tokyo, nơi diễn ra các lễ hội lớn của quốc gia cùng với truyền thuyết ra đời ngôi đền bí ẩn cũng như ghé thăm khu phố Nakamise-dori cảm nhận một Tokyo cổ xưa tại một trong những con phố mua sắm lâu đời nhất của Nhật Bản. Từ đây đoàn có thể chụp ảnh Tháp Tokyo Sky Tree.\nĐoàn tham quan, mua sắm tại khu mua sắm lớn nhất Tokyo Giza hoặc Akihabara (nếu còn thời gian)\nĂn tối tại nhà hàng.\n\nNghỉ đêm tại khách sạn tại Tokyo – Khu vực gần sân bay Haneda ( Shinjuku Washington hoặc tương đương)', 'Quý khách ăn sáng tại khách sạn.\n\nTrong giai đoạn hoa anh đào, Quý khách sẽ tham quan:\n\nNúi Phú Sĩ: biểu tượng của đất nước mặt trời mọc, được công nhận là di sản văn hóa thế giới (tại thời điểm thực tế, đoàn sẽ tham quan trạm 5 (tùy theo tình hình thời tiết thực tế mà Chính phủ Nhật Bản cho phép lên trạm tham quan)…\nĐoàn di chuyển về Tokyo\n\nĂn trưa tại nhà hàng, sau đó tiếp tục tham quan:\n\nĐền Asakusa Kannon: Ngôi đền thờ cổ nhất tại Tokyo, nơi diễn ra các lễ hội lớn của quốc gia cùng với truyền thuyết ra đời ngôi đền bí ẩn cũng như ghé thăm khu phố Nakamise-dori cảm nhận một Tokyo cổ xưa tại một trong những con phố mua sắm lâu đời nhất của Nhật Bản. Từ đây đoàn có thể chụp ảnh Tháp Tokyo Sky Tree.\nNgắm hoa anh đào tại Sumida River (Hoặc Ueno Park): Bạn có thể chụp những bức ảnh tuyệt đẹp về hoa anh đào ở đây với hơn 600 cây anh đào Nhật Bản và hình ảnh của Tokyo Skytree cao chót vót ở hậu cảnh\nCông viên Ueno - Công viên Ueno nổi tiếng với các bảo tàng, đặc biệt là bảo tàng nghệ thuật như Bảo tàng Quốc gia Tokyo, có đền và vườn thú trong đó có gấu trúc thu hút sự chú ý của khách đến thăm. Đặc biệt, nơi đây còn nổi tiếng với 1,000 cây Anh Đào, cũng là nơi người Nhật, nhất là các bạn trẻ, thường hẹn hò ngắm cảnh, chụp hình hay thưởng thức rượu sake bên gốc anh đào.\nĐoàn tham quan, mua sắm tại khu mua sắm lớn nhất Tokyo Giza hoặc Akihabara (nếu còn thời gian)\nĂn tối tại nhà hàng.\n\nNghỉ đêm tại khách sạn tại Tokyo.\n\nCác giai đoạn khác trong năm, đoàn tham quan:\n\nLàng Cổ Oshino Hakkai - ngôi làng yên bình nép dưới chân núi Phú Sĩ, cho đến ngày nay vẫn giữ nguyên nét kiến trúc truyền thống Nhật và phảng phất nét hoài cổ của xứ Phù Tang trong ký ức.\nĐoàn shopping tại khu mua sắm miễn thuế Gotemba Outlet ( nếu thời gian cho phép)\n\nĂn trưa tại nhà hàng\n\nSau đó, đoàn di chuyển đến Tokyo, tham quan:\n\nĐền Asakusa Kannon - Ngôi đền thờ cổ nhất tại Tokyo, nơi diễn ra các lễ hội lớn của quốc gia cùng với truyền thuyết ra đời ngôi đền bí ẩn. Từ đây đoàn có thể chụp ảnh Tháp Tokyo Sky Tree.\nĐoàn tham quan, mua sắm tại khu mua sắm lớn nhất Tokyo Giza hoặc Akihabara\n\nĂn tối tại nhà hàng.\n\nNghỉ đêm tại khách sạn tại Tokyo.\n\nGiai đoạn trượt tuyết, đoàn tham quan:\n\nNúi Phú Sĩ: biểu tượng của đất nước mặt trời mọc, được công nhận là di sản văn hóa thế giới (tại thời điểm thực tế, đoàn sẽ tham quan trạm 5 (tùy theo tình hình thời tiết thực tế mà Chính phủ Nhật Bản cho phép lên trạm tham quan)…\nĐoàn đi trải nghiệm trượt tuyết ở khu nghỉ dưỡng trượt tuyết Fujiten Snow Resort (giai đoạn tháng 1 – tháng 2) nằm dọc phía bắc núi Phú Sĩ. Quý khách tham quan, vui chơi và tận hưởng các hoạt động thú vị và ngắm ngọn núi huyền thoại của xứ sở Phù Tang. (Chưa bao gồm chi phí thuê dụng cụ trượt tuyết)\nĂn trưa tại nhà hàng, sau đó di chuyển đến Tokyo tiếp tục tham quan:\n\nĐền Asakusa Kannon: Ngôi đền thờ cổ nhất tại Tokyo, nơi diễn ra các lễ hội lớn của quốc gia cùng với truyền thuyết ra đời ngôi đền bí ẩn cũng như ghé thăm khu phố Nakamise-dori cảm nhận một Tokyo cổ xưa tại một trong những con phố mua sắm lâu đời nhất của Nhật Bản. Từ đây đoàn có thể chụp ảnh Tháp Tokyo Sky Tree.\nĐoàn tham quan, mua sắm tại khu mua sắm lớn nhất Tokyo Giza hoặc Akihabara (nếu còn thời gian)\nĂn tối tại nhà hàng.\n\nNghỉ đêm tại khách sạn tại Tokyo – Khu vực gần sân bay Haneda ( Shinjuku Washington hoặc tương đương)', ''),
(166, 27, 'Ngày 5', 5, NULL, NULL, 'TOKYO – ODAIBA - HÀ NỘI', 'Quý khách ăn sáng tại khách sạn. Quý khách tham quan:\n\n- Hoàng cung Nhật Bản được xây dựng trên nền cũ của Lâu dài Edo - một vùng công viên rộng lớn được bao bọc bởi những hồ nước và những bức tường đá ngay giữa trung tâm Tokyo. Đây cũng là nơi ở của Hoàng gia Nhật bản hiện tại (chụp hình bên ngoài)..\n\n- Vịnh Odaiba: quý khách chụp ảnh với bản sao Tượng nữa thần tự do và shopping tại đây.\n\nĂn trưa tại nhà hàng.\n\nSau đó, Quý khách ra sân bay làm thủ tục đáp chuyến bay về Hà Nội. Về tới sân bay Nội Bài, xe của Vietravel đón Quý khách về thành phố. Chia tay và kết thúc chương trình tham quan.\n\n*****\n\nLưu ý:\n\n- Thời gian & hành trình có thể thay đổi thứ tự điểm đến tùy vào điều kiện thực tế và thời lượng sử dụng xe du lịch theo quy định tại Nhật Bản – Tính từ ngày 1/5/2024 là tối đa 10 tiếng/ngày.\n\n- Mùa hoa nở, mùa lá vàng lá đỏ rất dễ bị ảnh hưởng bởi điều kiện thời tiết bên ngoài. Đây là trường hợp bất khả kháng mong Quý khách hiểu và thông cảm.\n\n- Khách Sạn có thể ở xa trung tâm thành phố khoảng 1h -1,5h đi xe vào các mùa Cao Điểm của Nhật như Hoa Anh Đào, Tuần lễ Vàng ( 30/4; 01/5), lễ, Tết….\n\nTHÔNG BÁO QUAN TRỌNG Về việc hoàn thành nghĩa vụ thuế trước khi xuất cảnh\n\nNhằm đảm bảo quá trình xuất cảnh diễn ra thuận lợi, Quý khách là chủ doanh nghiệp, chủ hộ kinh doanh, hoặc cá nhân có nghĩa vụ nộp thuế Thu nhập cá nhân (TNCN) cần chủ động hoàn thành đầy đủ các nghĩa vụ thuế trước khi xuất cảnh.\nTheo quy định hiện hành, cơ quan Hải quan có thể kiểm tra tình trạng nộp thuế trên hệ thống điện tử. Do đó, để tránh gián đoạn trong quá trình xuất cảnh, Quý khách vui lòng tự kiểm tra thông tin thuế cá nhân bằng cách tải và sử dụng ứng dụng Etax Mobile.\nThời gian qua, đã có nhiều trường hợp không thể xuất cảnh do chưa hoàn thành nghĩa vụ thuế với Nhà nước. Vì vậy, Vietravel khuyến nghị Quý khách kiểm tra kỹ và hoàn tất các khoản thuế cần thiết trước chuyến đi để đảm bảo kế hoạch di chuyển không bị ảnh hưởng.\nVietravel không chịu trách nhiệm trong trường hợp Quý khách bị từ chối xuất cảnh do chưa hoàn thành nghĩa vụ thuế.', 'Quý khách ăn sáng tại khách sạn. Quý khách tham quan:\n\n- Hoàng cung Nhật Bản được xây dựng trên nền cũ của Lâu dài Edo - một vùng công viên rộng lớn được bao bọc bởi những hồ nước và những bức tường đá ngay giữa trung tâm Tokyo. Đây cũng là nơi ở của Hoàng gia Nhật bản hiện tại (chụp hình bên ngoài)..\n\n- Vịnh Odaiba: quý khách chụp ảnh với bản sao Tượng nữa thần tự do và shopping tại đây.\n\nĂn trưa tại nhà hàng.\n\nSau đó, Quý khách ra sân bay làm thủ tục đáp chuyến bay về Hà Nội. Về tới sân bay Nội Bài, xe của Vietravel đón Quý khách về thành phố. Chia tay và kết thúc chương trình tham quan.\n\n*****\n\nLưu ý:\n\n- Thời gian & hành trình có thể thay đổi thứ tự điểm đến tùy vào điều kiện thực tế và thời lượng sử dụng xe du lịch theo quy định tại Nhật Bản – Tính từ ngày 1/5/2024 là tối đa 10 tiếng/ngày.\n\n- Mùa hoa nở, mùa lá vàng lá đỏ rất dễ bị ảnh hưởng bởi điều kiện thời tiết bên ngoài. Đây là trường hợp bất khả kháng mong Quý khách hiểu và thông cảm.\n\n- Khách Sạn có thể ở xa trung tâm thành phố khoảng 1h -1,5h đi xe vào các mùa Cao Điểm của Nhật như Hoa Anh Đào, Tuần lễ Vàng ( 30/4; 01/5), lễ, Tết….\n\nTHÔNG BÁO QUAN TRỌNG Về việc hoàn thành nghĩa vụ thuế trước khi xuất cảnh\n\nNhằm đảm bảo quá trình xuất cảnh diễn ra thuận lợi, Quý khách là chủ doanh nghiệp, chủ hộ kinh doanh, hoặc cá nhân có nghĩa vụ nộp thuế Thu nhập cá nhân (TNCN) cần chủ động hoàn thành đầy đủ các nghĩa vụ thuế trước khi xuất cảnh.\nTheo quy định hiện hành, cơ quan Hải quan có thể kiểm tra tình trạng nộp thuế trên hệ thống điện tử. Do đó, để tránh gián đoạn trong quá trình xuất cảnh, Quý khách vui lòng tự kiểm tra thông tin thuế cá nhân bằng cách tải và sử dụng ứng dụng Etax Mobile.\nThời gian qua, đã có nhiều trường hợp không thể xuất cảnh do chưa hoàn thành nghĩa vụ thuế với Nhà nước. Vì vậy, Vietravel khuyến nghị Quý khách kiểm tra kỹ và hoàn tất các khoản thuế cần thiết trước chuyến đi để đảm bảo kế hoạch di chuyển không bị ảnh hưởng.\nVietravel không chịu trách nhiệm trong trường hợp Quý khách bị từ chối xuất cảnh do chưa hoàn thành nghĩa vụ thuế.', ''),
(172, 28, 'Ngày 1', 1, NULL, NULL, 'Nha Trang - Đà Nẵng', 'Quý khách tập trung tại Ga Nha Trang - 17 Thái Nguyên. Hướng dẫn viên hỗ trợ quý khách làm thủ tục lên tàu, chuyến SE22 (chuyến 18:40) khởi hành đến TP. Đà Nẵng.', 'Quý khách tập trung tại Ga Nha Trang - 17 Thái Nguyên. Hướng dẫn viên hỗ trợ quý khách làm thủ tục lên tàu, chuyến SE22 (chuyến 18:40) khởi hành đến TP. Đà Nẵng.', ''),
(173, 28, 'Ngày 2', 2, NULL, NULL, 'Đà Nẵng - Huế - Đại Nội', 'Xe và hướng dẫn viên đón quý khách tại Ga Đà Nẵng, đưa quý khách đi ăn sáng sau đó tham quan:\n\nBán đảo Sơn Trà và Chùa Linh Ứng: nơi đây có tượng Phật Quan Thế Âm cao nhất Việt Nam.\nĐầm Lập An: ngắm cảnh mây bồng bềnh trên những chóp núi bao bọc quanh đầm,..\nNgọc trai VietPearl: là một trong những điểm đến thân thuộc của người dân xứ Huế chuyên tìm kiếm những nguồn ngọc trai chất lượng và đưa đến khách hàng những sản phẩm tinh tế nhất, cho Quý khách trải nghiệm đẳng cấp\nĐại Nội: hoàng cung xưa của 13 vị vua triều Nguyễn, tham quan Ngọ Môn, Điện Thái Hòa, Tử Cấm Thành, Thế Miếu, Hiển Lâm Các, Cửu Đình, Bảo tàng Cổ Vật Cung Đình Huế, Điện Kiến Trung…\nChùa Thiên Mụ: ngôi chùa được xem là biểu tượng xứ Huế và là nơi lưu giữ nhiều cổ vật quý giá không chỉ về mặt lịch sử mà còn cả về nghệ thuật.\nDạo Phố Đêm: Trải nghiệm một Huế về đêm đầy sắc màu tại Phố đi bộ ven sông Hương, hài hòa với vẻ lung linh cầu Trường Tiền. Tự do thưởng thức các món đường phố xứ Huế như bánh mì lọc, chè Huế, ngắm nhìn thuyền rồng ngược xuôi bên bến Tòa Khâm văng vẳng âm vang điệu hò Huế. Khám phá khu phố Tây sôi động về đêm với nhiều quán xá đông vui, những hàng quà lưu niệm.\nĐoàn quay về khách sạn, nhận phòng tự do nghỉ ngơi.', 'Xe và hướng dẫn viên đón quý khách tại Ga Đà Nẵng, đưa quý khách đi ăn sáng sau đó tham quan:\n\nBán đảo Sơn Trà và Chùa Linh Ứng: nơi đây có tượng Phật Quan Thế Âm cao nhất Việt Nam.\nĐầm Lập An: ngắm cảnh mây bồng bềnh trên những chóp núi bao bọc quanh đầm,..\nNgọc trai VietPearl: là một trong những điểm đến thân thuộc của người dân xứ Huế chuyên tìm kiếm những nguồn ngọc trai chất lượng và đưa đến khách hàng những sản phẩm tinh tế nhất, cho Quý khách trải nghiệm đẳng cấp\nĐại Nội: hoàng cung xưa của 13 vị vua triều Nguyễn, tham quan Ngọ Môn, Điện Thái Hòa, Tử Cấm Thành, Thế Miếu, Hiển Lâm Các, Cửu Đình, Bảo tàng Cổ Vật Cung Đình Huế, Điện Kiến Trung…\nChùa Thiên Mụ: ngôi chùa được xem là biểu tượng xứ Huế và là nơi lưu giữ nhiều cổ vật quý giá không chỉ về mặt lịch sử mà còn cả về nghệ thuật.\nDạo Phố Đêm: Trải nghiệm một Huế về đêm đầy sắc màu tại Phố đi bộ ven sông Hương, hài hòa với vẻ lung linh cầu Trường Tiền. Tự do thưởng thức các món đường phố xứ Huế như bánh mì lọc, chè Huế, ngắm nhìn thuyền rồng ngược xuôi bên bến Tòa Khâm văng vẳng âm vang điệu hò Huế. Khám phá khu phố Tây sôi động về đêm với nhiều quán xá đông vui, những hàng quà lưu niệm.\nĐoàn quay về khách sạn, nhận phòng tự do nghỉ ngơi.', ''),
(174, 28, 'Ngày 3', 3, NULL, NULL, 'Huế - La Vang - Động Phong Nha', 'Dùng bữa sáng khách sạn. Quý khách khởi hành sớm đi Quảng Bình, trên đường đi dừng tham quan:\n\nThánh Địa La Vang: một trong bốn tiểu vương cung thánh đường La Vang tại Việt Nam\n(Ghi chú: Giai đoạn từ 08/08 – 16/08 hàng năm, Thánh Địa La Vang diễn ra \"Đại Hội Hành Hương Đức Mẹ La Vang\" hàng năm, nên các tour khởi hành trong giai đoạn này sẽ không vào tham quan La Vang)\n\nĐộng Phong Nha: nằm trong quần thể Di sản thiên nhiên thế giới Phong Nha - Kẻ Bàng, được xem như chốn thần tiên bởi hệ thống núi đá vôi và sông ngầm dài nhất thế giới\nĐoàn khởi hành về đến Huế khoảng 20:00, nhận phòng tự do nghỉ ngơi.', 'Dùng bữa sáng khách sạn. Quý khách khởi hành sớm đi Quảng Bình, trên đường đi dừng tham quan:\n\nThánh Địa La Vang: một trong bốn tiểu vương cung thánh đường La Vang tại Việt Nam\n(Ghi chú: Giai đoạn từ 08/08 – 16/08 hàng năm, Thánh Địa La Vang diễn ra \"Đại Hội Hành Hương Đức Mẹ La Vang\" hàng năm, nên các tour khởi hành trong giai đoạn này sẽ không vào tham quan La Vang)\n\nĐộng Phong Nha: nằm trong quần thể Di sản thiên nhiên thế giới Phong Nha - Kẻ Bàng, được xem như chốn thần tiên bởi hệ thống núi đá vôi và sông ngầm dài nhất thế giới\nĐoàn khởi hành về đến Huế khoảng 20:00, nhận phòng tự do nghỉ ngơi.', ''),
(175, 28, 'Ngày 4', 4, NULL, NULL, 'Huế - Đà Nẵng - KDL Bà Nà - Phố cổ Hội An', 'Dùng bữa sáng tại khách sạn. Xe đưa Quý khách khởi hành trở về Đà Nẵng, trên đường đi dừng tham quan:\n\nTinh dầu tràm Thái Hà: Huế được coi là xứ sở của loại dầu tràm nổi danh khắp nước với công dụng tuyệt vời, là phương thuốc lành tính, an toàn với mọi lứa tuổi, giúp tiêu tan cái mệt mỏi, các cơn đau kinh niên, cho giấc ngủ được sâu hơn,…\nKhu du lịch Bà Nà (chi phí cáp treo & ăn trưa tự túc): tận hưởng không khí se lạnh của Đà Lạt tại miền Trung, đoàn tự do tham quan Chùa Linh Ứng, Hầm Rượu Debay, vườn hoa Le Jardin D’Amour, Khu Tâm linh mới của Bà Nà viếng Đền Lĩnh Chúa Linh Từ, khu vui chơi Fantasy Park, tự do chụp hình tại Cầu Vàng điểm tham quan siêu hot tại Bà Nà…\nĂn trưa tại Bà Nà tự túc. Sau đó đoàn tiếp tục tham quan vui chơi đến giờ xuống cáp.\n\nBuổi chiều quý khách tham quan:\n\nPhố Cổ Hội An: Chùa Cầu, Nhà Cổ Phùng Hưng, Hội Quán Phước Kiến, Cơ sở Thủ Công Mỹ Nghệ,… Tự do thả đèn hoa đăng cầu sức khỏe bình an & phúc lộc năm mới trên dòng sông Hoài…. (chi phí tự túc).\nĐoàn quay về Đà Nẵng, nhận phòng khách sạn, tự do nghỉ ngơi.', 'Dùng bữa sáng tại khách sạn. Xe đưa Quý khách khởi hành trở về Đà Nẵng, trên đường đi dừng tham quan:\n\nTinh dầu tràm Thái Hà: Huế được coi là xứ sở của loại dầu tràm nổi danh khắp nước với công dụng tuyệt vời, là phương thuốc lành tính, an toàn với mọi lứa tuổi, giúp tiêu tan cái mệt mỏi, các cơn đau kinh niên, cho giấc ngủ được sâu hơn,…\nKhu du lịch Bà Nà (chi phí cáp treo & ăn trưa tự túc): tận hưởng không khí se lạnh của Đà Lạt tại miền Trung, đoàn tự do tham quan Chùa Linh Ứng, Hầm Rượu Debay, vườn hoa Le Jardin D’Amour, Khu Tâm linh mới của Bà Nà viếng Đền Lĩnh Chúa Linh Từ, khu vui chơi Fantasy Park, tự do chụp hình tại Cầu Vàng điểm tham quan siêu hot tại Bà Nà…\nĂn trưa tại Bà Nà tự túc. Sau đó đoàn tiếp tục tham quan vui chơi đến giờ xuống cáp.\n\nBuổi chiều quý khách tham quan:\n\nPhố Cổ Hội An: Chùa Cầu, Nhà Cổ Phùng Hưng, Hội Quán Phước Kiến, Cơ sở Thủ Công Mỹ Nghệ,… Tự do thả đèn hoa đăng cầu sức khỏe bình an & phúc lộc năm mới trên dòng sông Hoài…. (chi phí tự túc).\nĐoàn quay về Đà Nẵng, nhận phòng khách sạn, tự do nghỉ ngơi.', ''),
(176, 28, 'Ngày 5', 5, NULL, NULL, 'Đà Nẵng - Nha Trang', 'Dùng bữa sáng và trả phòng khách sạn, xe và Hướng dẫn viên đưa quý khách tham quan Chợ Cồn - Đà Nẵng.\n\nChợ Cồn: thưởng thức thoải mái các món ngọt ở dãy đồ ngọt như sinh tố, chè, trái cây dầm… hoặc thỏa thuê ăn đồ mặn bên dãy đồ mặn với các món như bánh bèo, bánh xèo, bánh căn, ốc hút, mì quảng… Khu ẩm thực ngoài trời (Chi phí tự túc)\nXe tiễn Quý khách ra ga Đà Nẵng đón chuyến tàu SE1 (chuyến 12:47) về lại TP. Nha Trang. Chia tay Quý khách và kết thúc chương trình tham quan tại điểm đón ban đầu.', 'Dùng bữa sáng và trả phòng khách sạn, xe và Hướng dẫn viên đưa quý khách tham quan Chợ Cồn - Đà Nẵng.\n\nChợ Cồn: thưởng thức thoải mái các món ngọt ở dãy đồ ngọt như sinh tố, chè, trái cây dầm… hoặc thỏa thuê ăn đồ mặn bên dãy đồ mặn với các món như bánh bèo, bánh xèo, bánh căn, ốc hút, mì quảng… Khu ẩm thực ngoài trời (Chi phí tự túc)\nXe tiễn Quý khách ra ga Đà Nẵng đón chuyến tàu SE1 (chuyến 12:47) về lại TP. Nha Trang. Chia tay Quý khách và kết thúc chương trình tham quan tại điểm đón ban đầu.', ''),
(177, 29, 'Ngày 1', 1, NULL, NULL, 'TP. Hồ Chí Minh –  Hà Nội', 'Quý khách tập trung tại sân bay Tân Sơn Nhất (Ga nội địa), hướng dẫn viên hỗ trợ khách làm thủ tục đáp chuyến bay đi Hà Nội. Đến sân bay Nội Bài, xe và HDV Vietravel đón Quý khách đi Hà Nội. Xe đưa Quý khách đi tham quan:\n\nHoàng thành Thăng Long: quần thể công trìnhkiến trúc đồ sộ được các triều vua xây dựng trong nhiều giai đoạn lịch sử và trở thành di tích quan trọng bậc nhất trong hệ thốngcác di tích Việt Nam.\nVăn Miếu: nơi thờ Khổng Tử và các bậc hiền triếtcủa Nho Giáo, Quốc Tử Giám - trường đại học đầu tiên của Việt Nam, tìm về cội nguồn lịch sử của các vị Nho học.\nQuý khách nhận phòng khách sạn nghỉ ngơi hoặc tự do dạo 36 phố phường Hà Nội, trải nghiệm không gian sôi nổi, náo nhiệt tại Phố Tạ Hiện hay tìm một gốc với ly cà phê quan sát phố cổ hẳn cũng rất thú vị.\n\nNghỉ đêm tại Hà Nội.', 'Quý khách tập trung tại sân bay Tân Sơn Nhất (Ga nội địa), hướng dẫn viên hỗ trợ khách làm thủ tục đáp chuyến bay đi Hà Nội. Đến sân bay Nội Bài, xe và HDV Vietravel đón Quý khách đi Hà Nội. Xe đưa Quý khách đi tham quan:\n\nHoàng thành Thăng Long: quần thể công trìnhkiến trúc đồ sộ được các triều vua xây dựng trong nhiều giai đoạn lịch sử và trở thành di tích quan trọng bậc nhất trong hệ thốngcác di tích Việt Nam.\nVăn Miếu: nơi thờ Khổng Tử và các bậc hiền triếtcủa Nho Giáo, Quốc Tử Giám - trường đại học đầu tiên của Việt Nam, tìm về cội nguồn lịch sử của các vị Nho học.\nQuý khách nhận phòng khách sạn nghỉ ngơi hoặc tự do dạo 36 phố phường Hà Nội, trải nghiệm không gian sôi nổi, náo nhiệt tại Phố Tạ Hiện hay tìm một gốc với ly cà phê quan sát phố cổ hẳn cũng rất thú vị.\n\nNghỉ đêm tại Hà Nội.', ''),
(178, 29, 'Ngày 2', 2, NULL, NULL, 'Hà Nội – Ninh Bình', 'Quý khách dùng bữa sáng và làm thủ tục trả phòng khách sạn sớm. Xe đưa Quý khách đến Ninh Bình - vùng đất được mệnh danh là “Nơi mơ đến, chốn mong về”, nổi tiếng với những danh thắng thiên nhiên hùng vĩ và các di tích văn hóa đặc sắc. Đến nơi, Quý khách tham quan:\n\nTuyệt Tịnh Cốc: nằm giữa mảnh đất cố đô Hoa Lư (Ninh Bình),động Am Tiên ẩn mình giữa lưng chừng núi được mệnh danh là “thiên đường nơi hạ giới” và được giới trẻ gọi là Tuyệt Tịnh Cốc Việt Nam.\nChùa Bái Đính: một quần thể chùa với nhiều kỷ lục Việt Nam như pho tượng Phật Di Lặc bằng đồng nặng 80 tấn, hành lang với 500 tượng vị La Hán, chụp hình bên ngoài tòa Bảo Tháp cao 99m. ..\nKhu Du Lịch Tràng An: Quý khách lên thuyền truyền thống đi tham quan thắng cảnh hệ thống núi đá vôi hùng vĩ và các thung lũng ngập nước, thông với nhau bởi các dòng suối tạo nên các hang động ngập nước quanh năm. Điểm xuyến trong không gian hoang sơ, tĩnh lặng là hình ảnh rêu phong, cổ kínhcủa các mái đình, đền, phủ nằm nép mình dưới chân các dãy núi cao.\nBuổi tối, Quý khách tự do khám phá Phố cổ Hoa Lư – không gian check-in cổ kính, trầm mặc; về đêm càng lung linh với sắc màu đèn lồng và Bảo Tháp trên hồ Kỳ Lân. Quý khách có thể trải nghiệm các trò chơi dân gian và thưởng thức nhiều loại hình văn hóa nghệ thuật như múa rối nước, nhảy Tắc Xình, hát xẩm... cùng các buổi trình diễn acoustic vào tối cuối tuần.\n\nNghỉ đêm tại Ninh Bình.', 'Quý khách dùng bữa sáng và làm thủ tục trả phòng khách sạn sớm. Xe đưa Quý khách đến Ninh Bình - vùng đất được mệnh danh là “Nơi mơ đến, chốn mong về”, nổi tiếng với những danh thắng thiên nhiên hùng vĩ và các di tích văn hóa đặc sắc. Đến nơi, Quý khách tham quan:\n\nTuyệt Tịnh Cốc: nằm giữa mảnh đất cố đô Hoa Lư (Ninh Bình),động Am Tiên ẩn mình giữa lưng chừng núi được mệnh danh là “thiên đường nơi hạ giới” và được giới trẻ gọi là Tuyệt Tịnh Cốc Việt Nam.\nChùa Bái Đính: một quần thể chùa với nhiều kỷ lục Việt Nam như pho tượng Phật Di Lặc bằng đồng nặng 80 tấn, hành lang với 500 tượng vị La Hán, chụp hình bên ngoài tòa Bảo Tháp cao 99m. ..\nKhu Du Lịch Tràng An: Quý khách lên thuyền truyền thống đi tham quan thắng cảnh hệ thống núi đá vôi hùng vĩ và các thung lũng ngập nước, thông với nhau bởi các dòng suối tạo nên các hang động ngập nước quanh năm. Điểm xuyến trong không gian hoang sơ, tĩnh lặng là hình ảnh rêu phong, cổ kínhcủa các mái đình, đền, phủ nằm nép mình dưới chân các dãy núi cao.\nBuổi tối, Quý khách tự do khám phá Phố cổ Hoa Lư – không gian check-in cổ kính, trầm mặc; về đêm càng lung linh với sắc màu đèn lồng và Bảo Tháp trên hồ Kỳ Lân. Quý khách có thể trải nghiệm các trò chơi dân gian và thưởng thức nhiều loại hình văn hóa nghệ thuật như múa rối nước, nhảy Tắc Xình, hát xẩm... cùng các buổi trình diễn acoustic vào tối cuối tuần.\n\nNghỉ đêm tại Ninh Bình.', ''),
(179, 29, 'Ngày 3', 3, NULL, NULL, 'Ninh Bình – Hạ Long', 'Quý khách dùng bữa sáng và làm thủ tục trả phòng khách sạn. Xe đưa Quý khách đến cảng tàu Quốc tế Hạ Long, làm thủ tục lên thuyền và bắt đầu hành trình du ngoạn Vịnh Hạ Long, di sản thiên nhiên thế giới được UNESCO công nhận năm 1994.\n\nĐộng Thiên Cung là một trong những động đẹp nhất ở Hạ Long. Vẻ đẹp nguy nga và lộng lẫy bởi những lớp thạch nhũ và những luồng ánh sáng lung linh.\nTừ trên tàu ngắm nhìn các hòn đảo lớn nhỏ trong Vịnh Hạ Long: Hòn Gà Chọi, Hòn Lư Hương\nTham quan mua sắm đặc sản tại Trung Tâm OCOP Central Hạ Long với nhiều mặt hàng hải sản tươi, khô, chả mực, … đạt chất lượng theo tiêu chuẩn OCOP.\nBuổi tối, Quý khách tự do khám phá \"phố cổ\" Bãi Cháy sôi động, nơi hội tụ những hoạt động giải trí hấp dẫn, từ ẩm thực đường phố đặc sắc đến những quán bar sôi động Valley Beach Club. Hoặc, Quý khách có thể hòa mình vào không gian các quán cà phê độc đáo như Thông Zeo, 1900, Luna.\n\nHoặc lựa chọn một số dịch vụ khám phá Hạ Long về đêm (tự túc phương tiện và chi phí tham quan):\n\nTrải nghiệmdịch vụ Cáp Treo Nữ Hoàng tại Sun World Hạ Long Complex trên Núi Ba Đèo, chiêm ngưỡng cảnh đẹp về đêm của thành phố Hạ Long dưới ánh đèn lung linh (thời gian hoạt động cáp treo dự kiến từ 10h đến 18h các ngày thứ 7 và CN).\nNghỉ đêm tại Hạ Long.', 'Quý khách dùng bữa sáng và làm thủ tục trả phòng khách sạn. Xe đưa Quý khách đến cảng tàu Quốc tế Hạ Long, làm thủ tục lên thuyền và bắt đầu hành trình du ngoạn Vịnh Hạ Long, di sản thiên nhiên thế giới được UNESCO công nhận năm 1994.\n\nĐộng Thiên Cung là một trong những động đẹp nhất ở Hạ Long. Vẻ đẹp nguy nga và lộng lẫy bởi những lớp thạch nhũ và những luồng ánh sáng lung linh.\nTừ trên tàu ngắm nhìn các hòn đảo lớn nhỏ trong Vịnh Hạ Long: Hòn Gà Chọi, Hòn Lư Hương\nTham quan mua sắm đặc sản tại Trung Tâm OCOP Central Hạ Long với nhiều mặt hàng hải sản tươi, khô, chả mực, … đạt chất lượng theo tiêu chuẩn OCOP.\nBuổi tối, Quý khách tự do khám phá \"phố cổ\" Bãi Cháy sôi động, nơi hội tụ những hoạt động giải trí hấp dẫn, từ ẩm thực đường phố đặc sắc đến những quán bar sôi động Valley Beach Club. Hoặc, Quý khách có thể hòa mình vào không gian các quán cà phê độc đáo như Thông Zeo, 1900, Luna.\n\nHoặc lựa chọn một số dịch vụ khám phá Hạ Long về đêm (tự túc phương tiện và chi phí tham quan):\n\nTrải nghiệmdịch vụ Cáp Treo Nữ Hoàng tại Sun World Hạ Long Complex trên Núi Ba Đèo, chiêm ngưỡng cảnh đẹp về đêm của thành phố Hạ Long dưới ánh đèn lung linh (thời gian hoạt động cáp treo dự kiến từ 10h đến 18h các ngày thứ 7 và CN).\nNghỉ đêm tại Hạ Long.', ''),
(180, 29, 'Ngày 4', 4, NULL, NULL, 'Hạ Long – Sb Nội Bài – Tp.Hồ Chí Minh', 'Quý khách ăn sáng và tư do đến giờ hẹn trả phòng khách sạn. Xe đưa Quý khách đi tham quan:\n\nCheck-in Ngọn Hải Đăng bên vịnh Hạ Long\nTham quan Bảo Tàng Quảng Ninh và chụp hình bên ngoài Cung Cá Heo - Cung Quy Hoạch, Hội Chợ, Triển Lãm Và Văn Hóa Quảng Ninh...\nXe khởi hành đưa Quý khách ra sân bay Nội Bài làm thủ tục đón chuyến bay về Tp.HCM. Chia tay Quý khách và kết thúc chương trình du lịch tại sân bay Tân Sơn Nhất.', 'Quý khách ăn sáng và tư do đến giờ hẹn trả phòng khách sạn. Xe đưa Quý khách đi tham quan:\n\nCheck-in Ngọn Hải Đăng bên vịnh Hạ Long\nTham quan Bảo Tàng Quảng Ninh và chụp hình bên ngoài Cung Cá Heo - Cung Quy Hoạch, Hội Chợ, Triển Lãm Và Văn Hóa Quảng Ninh...\nXe khởi hành đưa Quý khách ra sân bay Nội Bài làm thủ tục đón chuyến bay về Tp.HCM. Chia tay Quý khách và kết thúc chương trình du lịch tại sân bay Tân Sơn Nhất.', ''),
(181, 30, 'Ngày 1', 1, NULL, NULL, 'Thiên đường nghỉ dưỡng Six Senses Côn Đảo', 'Quý khách tập trung tại sân bay Tân Sơn Nhất, làm thủ tục đáp chuyến bay của Vietnam Airlines đến Côn Đảo – hòn ngọc giữa đại dương, nổi tiếng với vẻ đẹp hoang sơ.\n\nĐến sân bay Côn Đảo, xe riêng đón Quý khách theo cung đường ven biển tuyệt đẹp về Six Senses Côn Đảo – khu nghỉ dưỡng quốc tế giữa thiên nhiên nguyên bản.\n\nQuý khách thưởng thức bữa tối Dining Under The Stars riêng tư với thực đơn cá nhân hóa kết hợp rượu vang cùng câu chuyện phía sau từng món ăn.\n\nNghỉ đêm Six Senses Côn Đảo', 'Quý khách tập trung tại sân bay Tân Sơn Nhất, làm thủ tục đáp chuyến bay của Vietnam Airlines đến Côn Đảo – hòn ngọc giữa đại dương, nổi tiếng với vẻ đẹp hoang sơ.\n\nĐến sân bay Côn Đảo, xe riêng đón Quý khách theo cung đường ven biển tuyệt đẹp về Six Senses Côn Đảo – khu nghỉ dưỡng quốc tế giữa thiên nhiên nguyên bản.\n\nQuý khách thưởng thức bữa tối Dining Under The Stars riêng tư với thực đơn cá nhân hóa kết hợp rượu vang cùng câu chuyện phía sau từng món ăn.\n\nNghỉ đêm Six Senses Côn Đảo', ''),
(182, 30, 'Ngày 2', 2, NULL, NULL, 'Một ngày sống giữa đại dương', 'h thái san hô rực rỡ\nTự do bơi lội & thư giãn giữa đại dương trong sắc xanh nguyên bản\nQuý khách trở về resort nghỉ ngơi hoặc lựa chọn tận hưởng Six Senses Spa - đưa cơ thể trở về trạng thái cân bằng sâu, đồng điệu giữa thân – tâm – trí (chi phí tự túc).\n\nBuổi tối, Quý khách thưởng thức tiệc nướng BQQ bên bãi biển riêng trong không gian lãng mạn đặc trưng của Côn Đảo.\n\nSau bữa tối, Quý khách có thể ghé Elephant Bar thưởng thức cocktail/mocktail – theo tinh thần giao thoa Đông – Tây (chi phí tự túc).\n\nNghỉ đêm Six Senses Côn Đảo', 'h thái san hô rực rỡ\nTự do bơi lội & thư giãn giữa đại dương trong sắc xanh nguyên bản\nQuý khách trở về resort nghỉ ngơi hoặc lựa chọn tận hưởng Six Senses Spa - đưa cơ thể trở về trạng thái cân bằng sâu, đồng điệu giữa thân – tâm – trí (chi phí tự túc).\n\nBuổi tối, Quý khách thưởng thức tiệc nướng BQQ bên bãi biển riêng trong không gian lãng mạn đặc trưng của Côn Đảo.\n\nSau bữa tối, Quý khách có thể ghé Elephant Bar thưởng thức cocktail/mocktail – theo tinh thần giao thoa Đông – Tây (chi phí tự túc).\n\nNghỉ đêm Six Senses Côn Đảo', '');
INSERT INTO `itineraries` (`id`, `tour_id`, `day_label`, `day_number`, `time_start`, `time_end`, `title`, `description`, `activities`, `image_url`) VALUES
(183, 30, 'Ngày 3', 3, NULL, NULL, '“Chạm” Vào Sự Tĩnh Tại – Six Senses Côn Đảo', 'Buổi sáng, Quý khách đón ngày mới với nhịp sống chậm – sâu – riêng tư tại Six Senses.\n\nLớp yoga / phục hồi năng lượng.\nLiệu pháp thư giãn với thảo dược địa phương / massage đặc trưng.(chi phí tự túc).\nFeed the Fish Golf – trải nghiệm đánh golf ra biển với EcoBioBalls®, loại bóng golf sinh học có thể phân rã tự nhiên và trở thành thức ăn cho cá. Một khoảnh khắc “green-luxury” rất riêng của Six Senses.(chi phí tự túc).\nThả Rùa Con Về Biển từ bãi biển riêng của khu nghỉ dưỡng. (Mùa rùa biển rơi vào tháng 4 - tháng 10).\nXe đưa Quý khách ra sân bay, làm thủ tục đáp chuyến bay Vietnam Airlines về lại TP. Hồ Chí Minh. Hướng dẫn viên gửi lời chào tạm biệt và hẹn gặp lại Quý khách trong những hành trình kế tiếp.', 'Buổi sáng, Quý khách đón ngày mới với nhịp sống chậm – sâu – riêng tư tại Six Senses.\n\nLớp yoga / phục hồi năng lượng.\nLiệu pháp thư giãn với thảo dược địa phương / massage đặc trưng.(chi phí tự túc).\nFeed the Fish Golf – trải nghiệm đánh golf ra biển với EcoBioBalls®, loại bóng golf sinh học có thể phân rã tự nhiên và trở thành thức ăn cho cá. Một khoảnh khắc “green-luxury” rất riêng của Six Senses.(chi phí tự túc).\nThả Rùa Con Về Biển từ bãi biển riêng của khu nghỉ dưỡng. (Mùa rùa biển rơi vào tháng 4 - tháng 10).\nXe đưa Quý khách ra sân bay, làm thủ tục đáp chuyến bay Vietnam Airlines về lại TP. Hồ Chí Minh. Hướng dẫn viên gửi lời chào tạm biệt và hẹn gặp lại Quý khách trong những hành trình kế tiếp.', ''),
(188, 31, 'Ngày 1', 1, NULL, NULL, 'Vancouver – Okanagan  - Kelowna – Revelstoke', 'Quý khách chủ động phương tiện di chuyển đến điểm hẹn tập trung.\n\n08:00 - Quý khách lên xe, khám phá những cung đường tuyệt đẹp xuyên Tây Canada. Dọc đường đi, đoàn sẽ băng qua thung lũng Fraser xanh mướt, chiêm ngưỡng sự chuyển mình ngoạn mục của cảnh sắc khi tiến vào vùng bán sa mạc Okanagan – một trong những khu vực khô nóng nhất Canada, nổi tiếng với vườn nho trĩu quả và những hồ nước tuyệt đẹp giữa lòng núi đồi.\n\n12:30 - Dừng chân tại Kelowna - thành phố nghỉ dưỡng ven hồ Okanagan quyến rũ.\n\n13:00 - Ăn trưa\n\n14:00 - Đoàn dạo chơi tại trung tâm Kelowna – với những con phố ven hồ yên bình, nhiều quán café và nhà hàng.\n\n14:00 - 16:00: Tiếp đó, đoàn đến Craigellachie, nơi đánh dấu mốc lịch sử “Cây đinh cuối cùng” được đóng xuống, hoàn tất tuyến đường sắt xuyên Canada – một biểu tượng đoàn kết và phát triển của đất nước lá phong.\n\n18:00 - Đoàn đến thị trấn Revelstoke nằm nép mình giữa dãy núi Selkirk, nổi tiếng với vẻ đẹp hoang sơ và thanh bình, ăn tối tại nhà hàng địa phương với thực đơn bản địa.\n\nNhận phòng và nghỉ ngơi tại khách sạn Regent Hotel Downtown Revelstoke hoặc tương đương.\n\nKhoảng cách tham khảo:\n\nVancouver – Okanagan - Kelowna – Revelstoke ~601 km', 'Quý khách chủ động phương tiện di chuyển đến điểm hẹn tập trung.\n\n08:00 - Quý khách lên xe, khám phá những cung đường tuyệt đẹp xuyên Tây Canada. Dọc đường đi, đoàn sẽ băng qua thung lũng Fraser xanh mướt, chiêm ngưỡng sự chuyển mình ngoạn mục của cảnh sắc khi tiến vào vùng bán sa mạc Okanagan – một trong những khu vực khô nóng nhất Canada, nổi tiếng với vườn nho trĩu quả và những hồ nước tuyệt đẹp giữa lòng núi đồi.\n\n12:30 - Dừng chân tại Kelowna - thành phố nghỉ dưỡng ven hồ Okanagan quyến rũ.\n\n13:00 - Ăn trưa\n\n14:00 - Đoàn dạo chơi tại trung tâm Kelowna – với những con phố ven hồ yên bình, nhiều quán café và nhà hàng.\n\n14:00 - 16:00: Tiếp đó, đoàn đến Craigellachie, nơi đánh dấu mốc lịch sử “Cây đinh cuối cùng” được đóng xuống, hoàn tất tuyến đường sắt xuyên Canada – một biểu tượng đoàn kết và phát triển của đất nước lá phong.\n\n18:00 - Đoàn đến thị trấn Revelstoke nằm nép mình giữa dãy núi Selkirk, nổi tiếng với vẻ đẹp hoang sơ và thanh bình, ăn tối tại nhà hàng địa phương với thực đơn bản địa.\n\nNhận phòng và nghỉ ngơi tại khách sạn Regent Hotel Downtown Revelstoke hoặc tương đương.\n\nKhoảng cách tham khảo:\n\nVancouver – Okanagan - Kelowna – Revelstoke ~601 km', ''),
(189, 31, 'Ngày 2', 2, NULL, NULL, 'Revelstoke – Hồ Ngọc Lục Bảo – Hồ Louise –  Calgary', '07:00 - Ăn sáng và trả phòng khách sạn.\n\n08:00 - Quý khách rời Revelstoke để bước vào một trong những chặng đường ngoạn mục nhất của hành trình – xuyên qua Rogers Pass, nơi nổi tiếng với cảnh sắc hùng vĩ của dãy Selkirk và là tuyến giao thông huyết mạch lịch sử của miền Tây Canada.\n\n10:30 - 11:30 - Đoàn dừng chân tại Hồ Ngọc Lục Bảo – được mệnh danh là “nàng thơ” của Vườn Quốc Gia Yoho với màu nước xanh ngọc bích đặc trưng. Mặt hồ trong xanh như tấm gương phản chiếu khung cảnh núi non hùng vĩ và bầu trời trong vắt. Tại đây, đoàn sở hữu góc ảnh tựa tranh vẽ: cây cầu gỗ và ngôi nhà gỗ (lodge) vàng nhạt nổi bật trên mặt hồ xanh biếc – tọa độ \'must-have\' không thể bỏ lỡ.\n\n12:00 - 13:30 - Di chuyển đến tham quan Hồ Louise - viên ngọc xanh của dãy Rockies, một tuyệt tác được đặt tên theo danh nàng công chúa Louise Caroline Alberta, con gái thứ tư của nữ hoàng Victoria. Với mặt nước xanh ngọc phản chiếu núi non tuyết phủ và khách sạn Fairmont cổ điển bên hồ. Nơi đây tự hào luôn giữ vững vị thế trong danh sách những hồ nước đẹp nhất hành tinh. Quý khách có thời gian tự do dạo quanh hồ, chụp hình hoặc thuê xuồng độc mộc để cảm nhận sự bình yên (chi phí tự túc).\n\n13:30 - 14:30 - Ăn trưa tại Lake Louise Village\n\n15:00 - Tham quan khách sạn Fairmont Banff Springs – biểu tượng huyền thoại của vùng Rocky Mountains. Với kiến trúc lộng lẫy, nơi đây được thế giới ưu ái mệnh danh là “Lâu đài giữa dãy núi Rocky”, một trong những khách sạn danh tiếng bậc nhất Canada.\n\n17:00 - Di chuyển về Calgary\n\n18:30 - Ăn tối tại nhà hàng Việt.\n\nNhận Phòng và nghỉ ngơi tại khách sạn Blackfoot Hotel Calgary hoặc tương đương.\n\nKhoảng cách tham khảo:\n\nRevelstoke – Rogers Pass –Emerald Lake - Lake Louise - Calgary ~ 426 km', '07:00 - Ăn sáng và trả phòng khách sạn.\n\n08:00 - Quý khách rời Revelstoke để bước vào một trong những chặng đường ngoạn mục nhất của hành trình – xuyên qua Rogers Pass, nơi nổi tiếng với cảnh sắc hùng vĩ của dãy Selkirk và là tuyến giao thông huyết mạch lịch sử của miền Tây Canada.\n\n10:30 - 11:30 - Đoàn dừng chân tại Hồ Ngọc Lục Bảo – được mệnh danh là “nàng thơ” của Vườn Quốc Gia Yoho với màu nước xanh ngọc bích đặc trưng. Mặt hồ trong xanh như tấm gương phản chiếu khung cảnh núi non hùng vĩ và bầu trời trong vắt. Tại đây, đoàn sở hữu góc ảnh tựa tranh vẽ: cây cầu gỗ và ngôi nhà gỗ (lodge) vàng nhạt nổi bật trên mặt hồ xanh biếc – tọa độ \'must-have\' không thể bỏ lỡ.\n\n12:00 - 13:30 - Di chuyển đến tham quan Hồ Louise - viên ngọc xanh của dãy Rockies, một tuyệt tác được đặt tên theo danh nàng công chúa Louise Caroline Alberta, con gái thứ tư của nữ hoàng Victoria. Với mặt nước xanh ngọc phản chiếu núi non tuyết phủ và khách sạn Fairmont cổ điển bên hồ. Nơi đây tự hào luôn giữ vững vị thế trong danh sách những hồ nước đẹp nhất hành tinh. Quý khách có thời gian tự do dạo quanh hồ, chụp hình hoặc thuê xuồng độc mộc để cảm nhận sự bình yên (chi phí tự túc).\n\n13:30 - 14:30 - Ăn trưa tại Lake Louise Village\n\n15:00 - Tham quan khách sạn Fairmont Banff Springs – biểu tượng huyền thoại của vùng Rocky Mountains. Với kiến trúc lộng lẫy, nơi đây được thế giới ưu ái mệnh danh là “Lâu đài giữa dãy núi Rocky”, một trong những khách sạn danh tiếng bậc nhất Canada.\n\n17:00 - Di chuyển về Calgary\n\n18:30 - Ăn tối tại nhà hàng Việt.\n\nNhận Phòng và nghỉ ngơi tại khách sạn Blackfoot Hotel Calgary hoặc tương đương.\n\nKhoảng cách tham khảo:\n\nRevelstoke – Rogers Pass –Emerald Lake - Lake Louise - Calgary ~ 426 km', ''),
(190, 31, 'Ngày 3', 3, NULL, NULL, 'Calgary  - Banff  - Hành Trình Xuyên Icefields Parkway – Chạm Vào Cánh Đồng Băng Columbia - Valemount', '07:00 - Ăn sáng và trả phòng khách sạn .\n\n08:00 - Quý khách lên xe tham quan thành phố Calgary - thành phố lớn nhất tỉnh Alberta, là viên ngọc quý của Canada tọa lạc ngay dưới chân dãy Rocky hùng vĩ.\n\n10:00 - Đoàn lên đường chinh phục Icefields Parkway – một trong 5 tuyến đường đẹp nhất thế giới, ngắm nhìn cảnh quan núi tuyết, rừng thông, sông băng và thác nước hùng vĩ.\n\nĐoàn dừng ăn trưa trước khi đến trải nghiệm đáng nhớ nhất trong ngày, khám phá Columbia Icefield – một trong những khối băng lớn nhất ở phía nam Bắc Cực. Tại đây, Quý khách có thể tham gia:\n\nIce Explorer Tour: di chuyển bằng xe chuyên dụng, băng qua sông băng Athabasca để chạm tay vào băng ngàn năm tuổi – trải nghiệm độc nhất vô nhị trong đời.\n17:00 - Rời cánh đồng băng Columbia, đoàn ghé tham quan thị trấn Jasper xinh đẹp. Trên đường về Valemount, xe dừng tại điểm ngắm cảnh Mt. Robson – nơi ngắm đỉnh ngọn núi Mt Robson cao nhất dãy Rockies (gần 4.000m), thường được bao phủ bởi mây trắng kỳ ảo.\n\nĐoàn nhận phòng khách sạn tại Valemount, nghỉ ngơi giữa không gian rừng núi yên bình.\n\nLưu ý:\n\nCác tour xe chuyên dụng Ice Explorer lên sông băng Athabasca thường hoạt động từ tháng 5 đến khoảng giữa tháng 10. Chương trình khám phá tùy thuộc vào tình hình điều kiện thời tiết.\n\nKhoảng cách tham khảo:\n\nCalgary - Banff - Valemount ~ 540 km', '07:00 - Ăn sáng và trả phòng khách sạn .\n\n08:00 - Quý khách lên xe tham quan thành phố Calgary - thành phố lớn nhất tỉnh Alberta, là viên ngọc quý của Canada tọa lạc ngay dưới chân dãy Rocky hùng vĩ.\n\n10:00 - Đoàn lên đường chinh phục Icefields Parkway – một trong 5 tuyến đường đẹp nhất thế giới, ngắm nhìn cảnh quan núi tuyết, rừng thông, sông băng và thác nước hùng vĩ.\n\nĐoàn dừng ăn trưa trước khi đến trải nghiệm đáng nhớ nhất trong ngày, khám phá Columbia Icefield – một trong những khối băng lớn nhất ở phía nam Bắc Cực. Tại đây, Quý khách có thể tham gia:\n\nIce Explorer Tour: di chuyển bằng xe chuyên dụng, băng qua sông băng Athabasca để chạm tay vào băng ngàn năm tuổi – trải nghiệm độc nhất vô nhị trong đời.\n17:00 - Rời cánh đồng băng Columbia, đoàn ghé tham quan thị trấn Jasper xinh đẹp. Trên đường về Valemount, xe dừng tại điểm ngắm cảnh Mt. Robson – nơi ngắm đỉnh ngọn núi Mt Robson cao nhất dãy Rockies (gần 4.000m), thường được bao phủ bởi mây trắng kỳ ảo.\n\nĐoàn nhận phòng khách sạn tại Valemount, nghỉ ngơi giữa không gian rừng núi yên bình.\n\nLưu ý:\n\nCác tour xe chuyên dụng Ice Explorer lên sông băng Athabasca thường hoạt động từ tháng 5 đến khoảng giữa tháng 10. Chương trình khám phá tùy thuộc vào tình hình điều kiện thời tiết.\n\nKhoảng cách tham khảo:\n\nCalgary - Banff - Valemount ~ 540 km', ''),
(191, 31, 'Ngày 4', 4, NULL, NULL, 'Valemount – Spaphats Falls - Kamloops – Vancouver', '07:00 - Ăn sáng và trả phòng khách sạn.\n\n08:00 - Quý khách rời Valemount trở về Vancouver qua những cung đường yên bình, mang đậm hơi thở thiên nhiên hoang dã.Trên đường, đoàn dừng chân tại thác nước Spaphats cao gần 75m, ẩn mình giữa rừng cây xanh mát, hít thở không khí trong lành.\n\n12:30 - 13:30 - Đoàn dùng bữa trưa tại thành phố Kamloops, trung tâm giao thương miền Tây Canada. 15:00 - 19:00 - Đoàn về đến Vancouver. Kết thúc chuyến tham quan. Chia tay đoàn tại đây và hẹn gặp lại.\n\nKhoảng cách tham khảo:\n\nValemount - Spahats Falls - Kamloops – Vancouver ~ 650 km', '07:00 - Ăn sáng và trả phòng khách sạn.\n\n08:00 - Quý khách rời Valemount trở về Vancouver qua những cung đường yên bình, mang đậm hơi thở thiên nhiên hoang dã.Trên đường, đoàn dừng chân tại thác nước Spaphats cao gần 75m, ẩn mình giữa rừng cây xanh mát, hít thở không khí trong lành.\n\n12:30 - 13:30 - Đoàn dùng bữa trưa tại thành phố Kamloops, trung tâm giao thương miền Tây Canada. 15:00 - 19:00 - Đoàn về đến Vancouver. Kết thúc chuyến tham quan. Chia tay đoàn tại đây và hẹn gặp lại.\n\nKhoảng cách tham khảo:\n\nValemount - Spahats Falls - Kamloops – Vancouver ~ 650 km', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `message` text NOT NULL,
  `link` varchar(512) DEFAULT NULL,
  `is_read` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `link`, `is_read`, `created_at`) VALUES
(1, 1, 'Booking mới #B001 - Đoàn 20 khách Sapa 15/12', '/admin/bookings/1', 0, '2025-11-20 10:35:00'),
(2, 1, 'Khách vừa thanh toán đủ booking #B003', '/admin/bookings/3', 0, '2025-11-26 10:05:00'),
(5, 1, 'Tour Hạ Long 19/12 đã kết thúc – HDV đánh giá 5 sao', '/admin/tour-logs/3', 0, '2025-12-19 21:30:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `rating` float DEFAULT '0',
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `type`, `contact_person`, `phone`, `email`, `address`, `rating`, `description`) VALUES
(1, 'Khách sạn Sunflower Hà Nội', 'Khách sạn', 'Chị Lan', '02438558888', 'lan@sunflower.com', '12 P. Lý Thái Tổ, Hà Nội', 4.6, NULL),
(2, 'Nhà hàng Hương Sen Sapa', 'Nhà hàng', 'Anh Hùng', '0214388999', 'hung@huongsen.com', 'Sapa, Lào Cai', 4.4, NULL),
(3, 'Tàu Paradise Hạ Long', 'Tàu', 'Chị Mai', '02033845888', 'mai@paradise.com', 'Tuần Châu, Quảng Ninh', 4.8, NULL),
(4, 'Xe Limousine 29 chỗ VIP', 'Xe khách', 'Anh Tuấn', '0912345678', 'tuan@limousine.vn', 'Hà Nội', 4.7, NULL),
(5, 'Khách sạn Silk Path Huế', 'Khách sạn', 'Chị Hương', '02343889999', 'huong@silkpath.com', 'Huế', 4.9, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier_contracts`
--

CREATE TABLE `supplier_contracts` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `contract_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price_info` text,
  `status` varchar(50) DEFAULT NULL,
  `notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `supplier_contracts`
--

INSERT INTO `supplier_contracts` (`id`, `supplier_id`, `contract_name`, `start_date`, `end_date`, `price_info`, `status`, `notes`) VALUES
(1, 1, 'Hợp đồng năm 2025', '2025-01-01', '2025-12-31', 'Phòng Standard: 1.100.000đ/phòng/đêm', 'active', 'Giảm 5% nếu thanh toán trước'),
(2, 3, 'Hợp đồng du thuyền 2025', '2025-01-01', '2025-12-31', 'Cabin Deluxe 4.500.000đ/khách/2N1Đ', 'active', 'Bao gồm tất cả bữa ăn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `supplier_feedbacks`
--

CREATE TABLE `supplier_feedbacks` (
  `id` int NOT NULL,
  `supplier_id` int NOT NULL,
  `guide_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tours`
--

CREATE TABLE `tours` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int NOT NULL,
  `supplier_id` int DEFAULT NULL COMMENT 'Supplier mặc định của tour',
  `description` text,
  `base_price` decimal(15,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'active',
  `featured` tinyint(1) DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tours`
--

INSERT INTO `tours` (`id`, `name`, `category_id`, `supplier_id`, `description`, `base_price`, `status`, `featured`, `created_at`, `updated_at`, `deleted_at`) VALUES
(26, 'Sapa - Bản Cát Cát - Fansipan - Hà Nội - Yên Tử - Hạ Long | Thưởng thức Lẩu Hải Sản 9 tầng Hạ Long', 7, NULL, '', 9390000.00, 'active', 0, '2026-04-03 15:23:42', '2026-04-03 09:01:26', NULL),
(27, 'Nagoya - Kyoto - Shirakawago - Matsumoto - Núi Phú Sĩ - Tokyo (Thưởng Thức Thịt Bò Hida)', 8, NULL, '- Trải nghiệm hành trình bay cùng Vietnam Airlines\r\n\r\n- Tham quan cung đường mới: Nagoya - Kyoto - Làng cổ Shirakawago - Kamikochi - Núi Phú Sĩ -Tokyo\r\n\r\n- Thưởng thức thịt bò Hida trứ danh\r\n\r\n- Ghé thăm Kamikochi- Thụy Sỹ trong lòng Nhật Bản\r\n\r\n- Thăm quan ngôi làng cố nổi tiếng Shirakawago: ngôi làng cổ tích trong truyện tranh của Nhật Bản.\r\n\r\n- Trải nghiệm tắm Onsen phong cách tắm truyền thống của người Nhật Bản.\r\n\r\n- Tham quan vịnh Odaiba: hòn đảo nhân tạo khá lớn của Nhật Bản nằm trong k', 31990000.00, 'active', 0, '2026-04-03 16:41:09', '2026-04-03 16:41:09', NULL),
(28, 'Đà Nẵng - Huế - La Vang - Động Phong Nha - Bà Nà - Cầu Vàng - Sơn Trà - Hội An - Đà Nẵng', 7, NULL, '', 6990000.00, 'active', 0, '2026-04-03 16:48:42', '2026-04-03 09:48:57', NULL),
(29, 'Hà Nội – Hoàng Thành Thăng Long – Chùa Bái Đính – Tràng An – Tuyệt Tịnh Cốc – Vịnh Hạ Long', 7, NULL, '', 9390000.00, 'active', 0, '2026-04-03 17:17:33', '2026-04-03 10:20:09', NULL),
(30, 'Côn Đảo: Thiên đường nghỉ dưỡng Six Senses Resort', 7, NULL, 'Không chỉ là một chuyến đi nghỉ dưỡng, hành trình đến Côn Đảo mở ra những khoảnh khắc thật sự thuộc về mình giữa thiên nhiên nguyên bản. Tại Six Senses Côn Đảo, mỗi trải nghiệm được thiết kế để hòa mình vào thiên nhiên chạm tới cá nhân hóa khách hàng – Từ hành trình cano riêng khám phá vịnh Côn Sơn, kết hợp những bữa tối được cá nhân hóa bên bờ biển, mọi trải nghiệm dần thăng hoa giữa thiên nhiên nguyên bản của Six Senses. Khi hành trình khép lại, điều mang về không chỉ là vẻ đẹp của đại dương xanh biếc, mà còn là cảm giác an nhiên và những ký ức tinh tế của một kỳ nghỉ sang trọng.', 85990000.00, 'active', 0, '2026-04-03 18:10:00', '2026-04-03 18:10:00', NULL),
(31, 'Canada: Vancouver - Banff - Rockies - Lake Louise - Emerald lake - Columbia Icefield - Vancouver (Dịch vụ tại điểm đến)', 8, NULL, 'Vancouver – thành phố lớn thứ 3 của Canada, được mệnh danh là “Thành phố của thiên nhiên”\r\nVườn Quốc Gia Banff – vườn quốc gia đầu tiên của Canada, được thành lập năm 1885 và được UNESCO công nhận là di sản thế giới năm 1984 nổi tiếng với Hồ Louise.\r\nKhám phá Hồ Ngọc Lục Bảo (Emerald Lake) – được mệnh danh là “nàng thơ” của Vườn Quốc Gia Yoho với màu nước xanh ngọc bích đặc trưng.\r\nKhám phá cánh đồng băng Columbia – biểu tượng kỳ vĩ của đất trời Canada.\r\nLưu trú tại những thị trấn mang đậm phong cách châu Âu, hòa mình vào thiên nhiên hùng vĩ.', 22900000.00, 'active', 0, '2026-04-03 18:19:06', '2026-04-03 11:19:20', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_assignments`
--

CREATE TABLE `tour_assignments` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `guide_id` int NOT NULL,
  `bus_company_id` int DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_categories`
--

CREATE TABLE `tour_categories` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `icon` varchar(512) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_categories`
--

INSERT INTO `tour_categories` (`id`, `name`, `slug`, `description`, `icon`, `created_at`, `updated_at`) VALUES
(7, 'Trong nước', 'trong-nuoc', 'Các tour trong nước', '', '2025-12-09 14:35:43', '2026-03-29 09:50:52'),
(8, 'Ngoài nước', 'nuoc-ngoai', 'Những tour nước ngoài', 'ph ph-globe', '2025-12-09 14:40:09', '2026-04-03 08:00:45'),
(9, 'Theo yêu cầu', 'theo-yeu-cau', 'Yêu cầu của khách hàng', '', '2025-12-09 15:12:53', '2025-12-09 15:12:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_departures`
--

CREATE TABLE `tour_departures` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `version_id` int DEFAULT NULL,
  `departure_date` date NOT NULL,
  `max_seats` int DEFAULT '40',
  `booked_seats` int DEFAULT '0',
  `price_adult` decimal(12,2) DEFAULT NULL,
  `price_child` decimal(12,2) DEFAULT NULL,
  `price_infant` decimal(12,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'open',
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_departures`
--

INSERT INTO `tour_departures` (`id`, `tour_id`, `version_id`, `departure_date`, `max_seats`, `booked_seats`, `price_adult`, `price_child`, `price_infant`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(86, 31, NULL, '2026-04-04', 40, 0, 22900000.00, 16030000.00, NULL, 'open', NULL, '2026-04-03 11:54:50', '2026-04-03 18:54:50'),
(87, 31, NULL, '2026-04-05', 40, 0, 22900000.00, 16030000.00, NULL, 'open', NULL, '2026-04-03 12:14:03', '2026-04-03 19:14:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_feedbacks`
--

CREATE TABLE `tour_feedbacks` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `user_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_gallery_images`
--

CREATE TABLE `tour_gallery_images` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `image_url` varchar(512) NOT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  `main_img` tinyint DEFAULT '0',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_gallery_images`
--

INSERT INTO `tour_gallery_images` (`id`, `tour_id`, `image_url`, `caption`, `sort_order`, `main_img`, `created_at`) VALUES
(53, 26, 'tours/tour_main_69cf790ed81fd.webp', '', 1, 1, '2026-04-03 15:23:42'),
(54, 26, 'tours/tour_69cf790ed8435.webp', '', 2, 0, '2026-04-03 15:23:42'),
(55, 26, 'tours/tour_69cf790ed851a.webp', '', 3, 0, '2026-04-03 15:23:42'),
(56, 26, 'tours/tour_69cf790ed90ff.webp', '', 4, 0, '2026-04-03 15:23:42'),
(57, 26, 'tours/tour_69cf790ed931a.webp', '', 5, 0, '2026-04-03 15:23:42'),
(58, 27, 'tours/tour_main_69cf8b35832c0.jpg', '', 1, 1, '2026-04-03 16:41:09'),
(59, 27, 'tours/tour_69cf8b3583a86.jpg', '', 2, 0, '2026-04-03 16:41:09'),
(60, 27, 'tours/tour_69cf8b35840ca.jpg', '', 3, 0, '2026-04-03 16:41:09'),
(61, 27, 'tours/tour_69cf8b3584773.jpg', '', 4, 0, '2026-04-03 16:41:09'),
(62, 27, 'tours/tour_69cf8b3584e95.jpg', '', 5, 0, '2026-04-03 16:41:09'),
(63, 28, 'tours/tour_main_69cf8cfae1424.jpg', '', 1, 1, '2026-04-03 16:48:42'),
(64, 28, 'tours/tour_69cf8cfae1c03.jpg', '', 2, 0, '2026-04-03 16:48:42'),
(65, 28, 'tours/tour_69cf8cfae22fe.jpg', '', 3, 0, '2026-04-03 16:48:42'),
(66, 28, 'tours/tour_69cf8cfae29a9.webp', '', 4, 0, '2026-04-03 16:48:42'),
(67, 28, 'tours/tour_69cf8cfae30b4.jpg', '', 5, 0, '2026-04-03 16:48:42'),
(68, 29, 'tours/tour_main_69cf93bd8153a.webp', '', 1, 1, '2026-04-03 17:17:33'),
(69, 29, 'tours/tour_69cf93bd819c6.webp', '', 2, 0, '2026-04-03 17:17:33'),
(70, 29, 'tours/tour_69cf93bd81ebd.webp', '', 3, 0, '2026-04-03 17:17:33'),
(71, 30, 'tours/tour_main_69cfa008054c0.webp', '', 1, 1, '2026-04-03 18:10:00'),
(72, 30, 'tours/tour_69cfa00805a9c.webp', '', 2, 0, '2026-04-03 18:10:00'),
(73, 30, 'tours/tour_69cfa00805f0b.webp', '', 3, 0, '2026-04-03 18:10:00'),
(74, 30, 'tours/tour_69cfa008062d8.webp', '', 4, 0, '2026-04-03 18:10:00'),
(75, 30, 'tours/tour_69cfa0080668e.webp', '', 5, 0, '2026-04-03 18:10:00'),
(76, 30, 'tours/tour_69cfa00806c54.webp', '', 6, 0, '2026-04-03 18:10:00'),
(77, 30, 'tours/tour_69cfa008072d0.webp', '', 7, 0, '2026-04-03 18:10:00'),
(78, 31, 'tours/tour_main_69cfa22a8c595.webp', '', 1, 1, '2026-04-03 18:19:06'),
(79, 31, 'tours/tour_69cfa22a8cc8b.webp', '', 2, 0, '2026-04-03 18:19:06'),
(80, 31, 'tours/tour_69cfa22a8d2cb.webp', '', 3, 0, '2026-04-03 18:19:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_logs`
--

CREATE TABLE `tour_logs` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `guide_id` int DEFAULT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `description` text,
  `issue` text,
  `solution` text,
  `customer_feedback` text,
  `weather` varchar(100) DEFAULT NULL,
  `incident` text,
  `health_status` varchar(255) DEFAULT NULL,
  `special_activity` text,
  `handling_notes` text,
  `guide_rating` int DEFAULT NULL,
  `actual_cost` decimal(15,2) DEFAULT '0.00',
  `cost_description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_partner_services`
--

CREATE TABLE `tour_partner_services` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `partner_name` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_policies`
--

CREATE TABLE `tour_policies` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_policies`
--

INSERT INTO `tour_policies` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Chính sách hủy tour', 'chinh-sach-huy-tour', 'Hủy trước 30 ngày: hoàn 100%, 15-29 ngày: hoàn 70%...', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(2, 'Chính sách trẻ em', 'chinh-sach-tre-em', 'Trẻ dưới 5 tuổi miễn phí, 5-10 tuổi tính 75%...', '2025-11-26 12:06:20', '2025-11-26 12:06:20'),
(3, 'Chính sách bảo hiểm', 'chinh-sach-bao-hiem', 'Bảo hiểm du lịch toàn bộ hành trình, mức bồi thường tối đa 200 triệu', '2025-11-26 12:06:20', '2025-12-10 12:33:38'),
(4, 'Điều khoản thanh toán', 'dieu-khoan-thanh-toan', 'Đặt cọc 50%, thanh toán hết trước 15 ngày khởi hành', '2025-11-26 12:06:20', '2025-11-26 12:06:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_policy_assignments`
--

CREATE TABLE `tour_policy_assignments` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `policy_id` int NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_policy_assignments`
--

INSERT INTO `tour_policy_assignments` (`id`, `tour_id`, `policy_id`, `created_at`) VALUES
(231, 26, 1, '2026-04-03 09:01:26'),
(232, 26, 2, '2026-04-03 09:01:26'),
(233, 26, 3, '2026-04-03 09:01:26'),
(234, 26, 4, '2026-04-03 09:01:26'),
(235, 27, 1, '2026-04-03 09:41:09'),
(236, 27, 2, '2026-04-03 09:41:09'),
(237, 27, 3, '2026-04-03 09:41:09'),
(238, 27, 4, '2026-04-03 09:41:09'),
(239, 28, 1, '2026-04-03 09:48:57'),
(240, 28, 2, '2026-04-03 09:48:57'),
(241, 28, 3, '2026-04-03 09:48:57'),
(242, 28, 4, '2026-04-03 09:48:57'),
(243, 29, 1, '2026-04-03 10:20:09'),
(244, 29, 2, '2026-04-03 10:20:09'),
(245, 29, 3, '2026-04-03 10:20:09'),
(246, 29, 4, '2026-04-03 10:20:09'),
(247, 30, 1, '2026-04-03 11:10:00'),
(248, 30, 2, '2026-04-03 11:10:00'),
(249, 30, 3, '2026-04-03 11:10:00'),
(250, 30, 4, '2026-04-03 11:10:00'),
(251, 31, 1, '2026-04-03 11:19:20'),
(252, 31, 2, '2026-04-03 11:19:20'),
(253, 31, 3, '2026-04-03 11:19:20'),
(254, 31, 4, '2026-04-03 11:19:20');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_pricing_options`
--

CREATE TABLE `tour_pricing_options` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `description` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_reviews`
--

CREATE TABLE `tour_reviews` (
  `id` int NOT NULL,
  `tour_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `rating` tinyint(1) DEFAULT NULL,
  `comment` text,
  `images` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_vehicles`
--

CREATE TABLE `tour_vehicles` (
  `id` int NOT NULL,
  `tour_assignment_id` int NOT NULL,
  `bus_company_id` int NOT NULL,
  `vehicle_plate` varchar(20) NOT NULL,
  `vehicle_type` varchar(100) DEFAULT NULL,
  `vehicle_brand` varchar(100) DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `driver_phone` varchar(20) DEFAULT NULL,
  `driver_license` varchar(50) DEFAULT NULL,
  `notes` text,
  `status` enum('assigned','confirmed','completed','cancelled') DEFAULT 'assigned',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_versions`
--

CREATE TABLE `tour_versions` (
  `id` int NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_versions`
--

INSERT INTO `tour_versions` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Cao cấp', 'Phiên bản tour cao cấp', 'active', '2025-11-28 21:22:49', '2025-11-28 21:22:49'),
(3, 'VIP', 'Phiên bản tour VIP', 'active', '2025-11-28 21:22:49', '2025-11-28 21:22:49'),
(7, 'Mùa đông 2025', '', 'active', '2025-12-05 01:33:21', '2025-12-09 15:35:28'),
(10, 'Bình thường', '', 'active', '2025-12-10 11:28:53', '2025-12-10 11:28:53'),
(11, 'Mùa xuân', '', 'active', '2025-12-10 12:54:05', '2026-04-03 15:03:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tour_version_prices`
--

CREATE TABLE `tour_version_prices` (
  `id` int NOT NULL,
  `version_id` int DEFAULT NULL,
  `tour_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `adult_percent` decimal(5,2) DEFAULT '0.00' COMMENT '% tăng/giảm giá người lớn',
  `child_percent` decimal(5,2) DEFAULT '0.00' COMMENT '% tăng/giảm giá trẻ em',
  `infant_percent` decimal(5,2) DEFAULT '0.00' COMMENT '% tăng/giảm giá em bé',
  `child_base_percent` decimal(5,2) DEFAULT '75.00' COMMENT 'Trẻ em = % giá người lớn',
  `infant_base_percent` decimal(5,2) DEFAULT '50.00' COMMENT 'Em bé = % giá người lớn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `tour_version_prices`
--

INSERT INTO `tour_version_prices` (`id`, `version_id`, `tour_id`, `created_at`, `adult_percent`, `child_percent`, `infant_percent`, `child_base_percent`, `infant_base_percent`) VALUES
(1, 7, NULL, '2025-12-05 01:33:21', -5.00, -10.00, -15.00, 75.00, 50.00),
(3, 10, NULL, '2025-12-10 11:28:53', 0.00, 0.00, 0.00, 75.00, 50.00),
(4, 2, NULL, '2025-12-10 12:51:29', 40.00, 30.00, 20.00, 75.00, 50.00),
(5, 11, NULL, '2025-12-10 12:54:05', -10.00, 0.00, 0.00, 75.00, 50.00);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `transactions`
--

CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `booking_id` int NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `description` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','guide','customer') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `avatar` varchar(512) DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `phone`, `role`, `password_hash`, `avatar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Admin Kiên', 'admin@company.com', '0909123456', 'admin', '$2y$10$tjeVGJKb11GpupcrPsvp8ujKv0mIJkZyKBWfC3GdEH3tKC.7gf4Za', 'avatars/avatar_1_1765021970.jpg', 1, '2025-11-26 12:06:20', '2026-03-18 19:12:39'),
(19, 'Nguyễn Văn An', 'guide.an@gmail.com', '0912345678', 'guide', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-04-03 17:02:11', '2026-04-03 17:02:11'),
(20, 'Trần Thị Bình', 'guide.binh@gmail.com', '0987654321', 'guide', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-04-03 17:02:11', '2026-04-03 17:02:11'),
(21, 'Lê Hoàng Long', 'guide.long@gmail.com', '0901234567', 'guide', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-04-03 17:02:11', '2026-04-03 17:02:11'),
(22, 'Phạm Minh Đức', 'guide.duc@gmail.com', '0933445566', 'guide', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-04-03 17:02:11', '2026-04-03 17:02:11'),
(23, 'Khách hàng 1', 'kimkienpes@gmail.com', '0986951086', 'customer', '$2y$10$uL08XO.SG29bD4jdEfg8nejqwH2/KWtyUHBGUtRVKhafHwyKLg0X6', NULL, 1, '2026-04-03 18:33:03', '2026-04-03 18:33:03');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `version_dynamic_pricing`
--

CREATE TABLE `version_dynamic_pricing` (
  `id` int NOT NULL,
  `version_id` int DEFAULT NULL,
  `departure_id` int DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `adjust_type` enum('discount','surcharge') DEFAULT 'discount',
  `amount_type` enum('cash','percent') DEFAULT 'cash',
  `amount` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `version_dynamic_pricing`
--

INSERT INTO `version_dynamic_pricing` (`id`, `version_id`, `departure_id`, `start_date`, `end_date`, `notes`, `created_at`, `adjust_type`, `amount_type`, `amount`) VALUES
(3, NULL, NULL, '2025-04-28', '2025-05-04', 'Giá lễ 30/4', '2025-11-26 12:06:20', 'discount', 'cash', NULL),
(4, NULL, NULL, '2025-12-27', '2026-01-05', 'Tết Dương lịch + Âm lịch', '2025-11-26 12:06:20', 'discount', 'cash', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `bookings_ibfk_3` (`version_id`),
  ADD KEY `fk_bookings_departure` (`departure_id`),
  ADD KEY `driver_id` (`bus_company_id`);

--
-- Chỉ mục cho bảng `booking_customers`
--
ALTER TABLE `booking_customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `booking_price_adjustments`
--
ALTER TABLE `booking_price_adjustments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Chỉ mục cho bảng `booking_suppliers_assignment`
--
ALTER TABLE `booking_suppliers_assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `bus_companies`
--
ALTER TABLE `bus_companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_code` (`company_code`),
  ADD KEY `idx_company_code` (`company_code`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_rating` (`rating`);

--
-- Chỉ mục cho bảng `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `itineraries`
--
ALTER TABLE `itineraries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Chỉ mục cho bảng `tours`
--
ALTER TABLE `tours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_supplier_id` (`supplier_id`);

--
-- Chỉ mục cho bảng `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `fk_tour_assignments_bus_company` (`bus_company_id`);

--
-- Chỉ mục cho bảng `tour_categories`
--
ALTER TABLE `tour_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tour_date` (`tour_id`,`departure_date`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `fk_departures_version` (`version_id`);

--
-- Chỉ mục cho bảng `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `tour_gallery_images`
--
ALTER TABLE `tour_gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_logs`
--
ALTER TABLE `tour_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Chỉ mục cho bảng `tour_partner_services`
--
ALTER TABLE `tour_partner_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_policies`
--
ALTER TABLE `tour_policies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `tour_policy_assignments`
--
ALTER TABLE `tour_policy_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `policy_id` (`policy_id`);

--
-- Chỉ mục cho bảng `tour_pricing_options`
--
ALTER TABLE `tour_pricing_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `tour_reviews`
--
ALTER TABLE `tour_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tour_vehicles`
--
ALTER TABLE `tour_vehicles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tour_assignment` (`tour_assignment_id`),
  ADD KEY `idx_bus_company` (`bus_company_id`),
  ADD KEY `idx_vehicle_plate` (`vehicle_plate`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `tour_version_prices`
--
ALTER TABLE `tour_version_prices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `version_id` (`version_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Chỉ mục cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `version_dynamic_pricing`
--
ALTER TABLE `version_dynamic_pricing`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_vdp_version` (`version_id`),
  ADD KEY `fk_vdp_departure` (`departure_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT cho bảng `booking_customers`
--
ALTER TABLE `booking_customers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `booking_price_adjustments`
--
ALTER TABLE `booking_price_adjustments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `booking_suppliers_assignment`
--
ALTER TABLE `booking_suppliers_assignment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `bus_companies`
--
ALTER TABLE `bus_companies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `financial_reports`
--
ALTER TABLE `financial_reports`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `itineraries`
--
ALTER TABLE `itineraries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT cho bảng `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `tours`
--
ALTER TABLE `tours`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `tour_assignments`
--
ALTER TABLE `tour_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `tour_categories`
--
ALTER TABLE `tour_categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `tour_departures`
--
ALTER TABLE `tour_departures`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT cho bảng `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `tour_gallery_images`
--
ALTER TABLE `tour_gallery_images`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT cho bảng `tour_logs`
--
ALTER TABLE `tour_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tour_partner_services`
--
ALTER TABLE `tour_partner_services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `tour_policies`
--
ALTER TABLE `tour_policies`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `tour_policy_assignments`
--
ALTER TABLE `tour_policy_assignments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT cho bảng `tour_pricing_options`
--
ALTER TABLE `tour_pricing_options`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `tour_reviews`
--
ALTER TABLE `tour_reviews`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tour_vehicles`
--
ALTER TABLE `tour_vehicles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tour_versions`
--
ALTER TABLE `tour_versions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `tour_version_prices`
--
ALTER TABLE `tour_version_prices`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `version_dynamic_pricing`
--
ALTER TABLE `version_dynamic_pricing`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_bookings_bus_company` FOREIGN KEY (`bus_company_id`) REFERENCES `bus_companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_bookings_departure` FOREIGN KEY (`departure_id`) REFERENCES `tour_departures` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `booking_customers`
--
ALTER TABLE `booking_customers`
  ADD CONSTRAINT `booking_customers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `booking_price_adjustments`
--
ALTER TABLE `booking_price_adjustments`
  ADD CONSTRAINT `booking_price_adjustments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_price_adjustments_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT;

--
-- Các ràng buộc cho bảng `booking_suppliers_assignment`
--
ALTER TABLE `booking_suppliers_assignment`
  ADD CONSTRAINT `booking_suppliers_assignment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_suppliers_assignment_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE RESTRICT;

--
-- Các ràng buộc cho bảng `financial_reports`
--
ALTER TABLE `financial_reports`
  ADD CONSTRAINT `financial_reports_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `guides`
--
ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `itineraries`
--
ALTER TABLE `itineraries`
  ADD CONSTRAINT `itineraries_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `supplier_contracts`
--
ALTER TABLE `supplier_contracts`
  ADD CONSTRAINT `supplier_contracts_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `supplier_feedbacks`
--
ALTER TABLE `supplier_feedbacks`
  ADD CONSTRAINT `supplier_feedbacks_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_feedbacks_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tours`
--
ALTER TABLE `tours`
  ADD CONSTRAINT `fk_tours_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tours_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tour_categories` (`id`) ON DELETE RESTRICT;

--
-- Các ràng buộc cho bảng `tour_assignments`
--
ALTER TABLE `tour_assignments`
  ADD CONSTRAINT `fk_tour_assignments_bus_company` FOREIGN KEY (`bus_company_id`) REFERENCES `bus_companies` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tour_assignments_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_assignments_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE RESTRICT;

--
-- Các ràng buộc cho bảng `tour_departures`
--
ALTER TABLE `tour_departures`
  ADD CONSTRAINT `fk_departures_version` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_td_tour` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_td_version` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `tour_feedbacks`
--
ALTER TABLE `tour_feedbacks`
  ADD CONSTRAINT `tour_feedbacks_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_feedbacks_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_gallery_images`
--
ALTER TABLE `tour_gallery_images`
  ADD CONSTRAINT `tour_gallery_images_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_logs`
--
ALTER TABLE `tour_logs`
  ADD CONSTRAINT `tour_logs_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_logs_ibfk_2` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `tour_partner_services`
--
ALTER TABLE `tour_partner_services`
  ADD CONSTRAINT `tour_partner_services_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_policy_assignments`
--
ALTER TABLE `tour_policy_assignments`
  ADD CONSTRAINT `tour_policy_assignments_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_policy_assignments_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `tour_policies` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_pricing_options`
--
ALTER TABLE `tour_pricing_options`
  ADD CONSTRAINT `tour_pricing_options_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tour_vehicles`
--
ALTER TABLE `tour_vehicles`
  ADD CONSTRAINT `tour_vehicles_ibfk_1` FOREIGN KEY (`tour_assignment_id`) REFERENCES `tour_assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tour_vehicles_ibfk_2` FOREIGN KEY (`bus_company_id`) REFERENCES `bus_companies` (`id`) ON DELETE RESTRICT;

--
-- Các ràng buộc cho bảng `tour_version_prices`
--
ALTER TABLE `tour_version_prices`
  ADD CONSTRAINT `tour_version_prices_ibfk_1` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`),
  ADD CONSTRAINT `tour_version_prices_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `tours` (`id`);

--
-- Các ràng buộc cho bảng `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `version_dynamic_pricing`
--
ALTER TABLE `version_dynamic_pricing`
  ADD CONSTRAINT `fk_vdp_departure` FOREIGN KEY (`departure_id`) REFERENCES `tour_departures` (`id`),
  ADD CONSTRAINT `fk_vdp_version` FOREIGN KEY (`version_id`) REFERENCES `tour_versions` (`id`);
COMMIT;


-- --------------------------------------------------------
-- CẬP NHẬT DỮ LIỆU BỔ SUNG (PHASE 5, 6, 7) - 2026-04-06
-- --------------------------------------------------------

-- 1. Tính năng Xóa mềm & Thùng rác (Tour Soft Delete)
ALTER TABLE `tours` ADD COLUMN IF NOT EXISTS `deleted_at` TIMESTAMP NULL DEFAULT NULL;

-- 2. Quản lý Hợp đồng Nhà cung cấp (Master Agreement File)
ALTER TABLE `suppliers` ADD COLUMN IF NOT EXISTS `contract_file` VARCHAR(255) DEFAULT NULL AFTER `contact_info`;


-- 3. Logic giữ chỗ tự động (Seat Booking Management)
ALTER TABLE `tour_departures` ADD COLUMN IF NOT EXISTS `booked_seats` INT DEFAULT 0 AFTER `max_seats`;

-- 5. Bảng quản lý chi phí Logistics thực tế (Hotfix 7.5)
CREATE TABLE IF NOT EXISTS `departure_resources` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `departure_id` INT NOT NULL,
  `supplier_id` INT DEFAULT NULL,
  `service_type` VARCHAR(50) DEFAULT NULL,
  `quantity` INT DEFAULT 1,
  `unit_price` DECIMAL(15,2) DEFAULT 0,
  `total_amount` DECIMAL(15,2) DEFAULT 0,
  `payment_status` ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
  `paid_amount` DECIMAL(15,2) DEFAULT 0,
  `notes` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`departure_id`) REFERENCES `tour_departures` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. Bảng quản lý nội dung các trang tĩnh
CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `content` LONGTEXT,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
