/*
 sqlyog community
 mysql - 8.0.21 : database - a7pro
 *********************************************************************
 */
/*!40101 set names utf8 */
;

/*!40101 set sql_mode=''*/
;

/*!40014 set @old_unique_checks=@@unique_checks, unique_checks=0 */
;

/*!40014 set @old_foreign_key_checks=@@foreign_key_checks, foreign_key_checks=0 */
;

/*!40101 set @old_sql_mode=@@sql_mode, sql_mode='no_auto_value_on_zero' */
;

/*!40111 set @old_sql_notes=@@sql_notes, sql_notes=0 */
;

create database
/*!32312 if not exists*/
`a7pro`
/*!40100 default character set utf8mb4 collate utf8mb4_unicode_ci */
/*!80016 default encryption='n' */
;

/*table structure for table `chats` */
create table `chats` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `sender_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `receiver_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `message` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `is_read` tinyint(1) not null default '0',
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `cost_categories` */
create table `cost_categories` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `description` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `couriers` */
create table `couriers` (
    `id` varchar(36) collate utf8mb4_unicode_ci not null,
    `name` varchar(255) collate utf8mb4_unicode_ci not null,
    `code` varchar(20) collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `customers` */
create table `customers` (
    `user_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `gender` char(1) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `date_of_birth` date default null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`user_id`),
    constraint `customers_ibfk_1` foreign key (`user_id`) references `users` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `device_types` */
create table `device_types` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `description` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `dpc` */
create table `dpc` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `code` varchar(10) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `dpd_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    unique key `code` (`code`),
    key `dpd_id` (`dpd_id`),
    constraint `dpc_ibfk_1` foreign key (`dpd_id`) references `dpd` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `dpd` */
create table `dpd` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `code` varchar(10) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `cost_category_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    unique key `code` (`code`),
    key `cost_category_id` (`cost_category_id`),
    constraint `dpd_ibfk_1` foreign key (`cost_category_id`) references `cost_categories` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `invoices` */
create table `invoices` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `code` varchar(20) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `amount` double not null,
    `detail` json default null,
    `status` varchar(10) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `payment_method` varchar(20) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `external_id` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `expiration` datetime default null,
    `user_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    unique key `external_id` (`external_id`),
    key `user_id` (`user_id`),
    constraint `invoices_ibfk_1` foreign key (`user_id`) references `users` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `order_products` */
create table `order_products` (
    `id` varchar(36) collate utf8mb4_unicode_ci not null,
    `order_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `product_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `quantity` int not null,
    `is_rated` tinyint not null default '0',
    `amount` double not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `order_id` (`order_id`),
    key `product_id` (`product_id`),
    constraint `order_products_ibfk_1` foreign key (`order_id`) references `orders` (`id`),
    constraint `order_products_ibfk_2` foreign key (`product_id`) references `products` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `orders` */
create table `orders` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `invoice_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `seller_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `customer_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `receipt_no` varchar(50) collate utf8mb4_unicode_ci default null,
    `courier_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `status` smallint not null default '1',
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `invoice_id` (`invoice_id`),
    key `seller_id` (`seller_id`),
    key `customer_id` (`customer_id`),
    key `courier_id` (`courier_id`),
    constraint `orders_ibfk_1` foreign key (`invoice_id`) references `invoices` (`id`),
    constraint `orders_ibfk_2` foreign key (`seller_id`) references `sellers` (`user_id`),
    constraint `orders_ibfk_3` foreign key (`customer_id`) references `customers` (`user_id`),
    constraint `orders_ibfk_4` foreign key (`courier_id`) references `couriers` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `product_categories` */
create table `product_categories` (
    `id` int unsigned not null auto_increment,
    `name` varchar(20) collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`)
) engine = innodb auto_increment = 3 default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `product_category_pivot` */
create table `product_category_pivot` (
    `id` int unsigned not null auto_increment,
    `product_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `category_id` int unsigned not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `product_id` (`product_id`),
    key `category_id` (`category_id`),
    constraint `product_category_pivot_ibfk_1` foreign key (`product_id`) references `products` (`id`),
    constraint `product_category_pivot_ibfk_2` foreign key (`category_id`) references `product_categories` (`id`)
) engine = innodb auto_increment = 15 default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `product_photos` */
create table `product_photos` (
    `id` varchar(36) collate utf8mb4_unicode_ci not null,
    `product_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `photo_url` varchar(255) collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `product_id` (`product_id`),
    constraint `product_photos_ibfk_1` foreign key (`product_id`) references `products` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `products` */
create table `products` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `description` text collate utf8mb4_unicode_ci,
    `specification` json default null,
    `seller_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `storefront_id` varchar(36) collate utf8mb4_unicode_ci default null,
    `price` double not null,
    `stock` int not null,
    `product_pict` varchar(36) collate utf8mb4_unicode_ci default null,
    `weight` int unsigned default null,
    `min_order` int not null default '1',
    `condition` tinyint(1) not null default '1',
    `is_active` tinyint not null default '0',
    `verified_id` varchar(36) collate utf8mb4_unicode_ci default null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `seller_id` (`seller_id`),
    key `product_pict` (`product_pict`),
    key `storefront_id` (`storefront_id`),
    key `verified_id` (`verified_id`),
    constraint `products_ibfk_1` foreign key (`seller_id`) references `sellers` (`user_id`),
    constraint `products_ibfk_2` foreign key (`product_pict`) references `product_photos` (`id`),
    constraint `products_ibfk_3` foreign key (`storefront_id`) references `storefronts` (`id`),
    constraint `products_ibfk_4` foreign key (`verified_id`) references `products` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `reviews` */
create table `reviews` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `product_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `customer_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `rating` smallint unsigned default null,
    `review_content` text character set utf8mb4 collate utf8mb4_unicode_ci,
    `in_reply_to` varchar(36) collate utf8mb4_unicode_ci default null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `product_id` (`product_id`),
    key `customer_id` (`customer_id`),
    key `in_reply_to` (`in_reply_to`),
    constraint `reviews_ibfk_1` foreign key (`product_id`) references `products` (`id`),
    constraint `reviews_ibfk_2` foreign key (`customer_id`) references `customers` (`user_id`),
    constraint `reviews_ibfk_3` foreign key (`in_reply_to`) references `reviews` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `roles` */
create table `roles` (
    `user_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `role` varchar(15) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    primary key (`user_id`, `role`),
    constraint `roles_ibfk_1` foreign key (`user_id`) references `users` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `seller_courier_pivot` */
create table `seller_courier_pivot` (
    `id` int unsigned not null auto_increment,
    `seller_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `courier_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `seller_id` (`seller_id`),
    key `courier_id` (`courier_id`),
    constraint `seller_courier_pivot_ibfk_1` foreign key (`seller_id`) references `sellers` (`user_id`),
    constraint `seller_courier_pivot_ibfk_2` foreign key (`courier_id`) references `couriers` (`id`)
) engine = innodb auto_increment = 7 default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `sellers` */
create table `sellers` (
    `user_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(50) collate utf8mb4_unicode_ci default null,
    `gender` char(1) collate utf8mb4_unicode_ci default null,
    `place_of_birth` varchar(30) collate utf8mb4_unicode_ci default null,
    `date_of_birth` date default null,
    `regency` varchar(30) collate utf8mb4_unicode_ci default null,
    `location` varchar(255) collate utf8mb4_unicode_ci default null,
    `postal_code` varchar(10) collate utf8mb4_unicode_ci default null,
    `lat` double default null,
    `lng` double default null,
    `description` text collate utf8mb4_unicode_ci,
    `working_day` varchar(15) collate utf8mb4_unicode_ci default null,
    `opening_hour` varchar(5) collate utf8mb4_unicode_ci default null,
    `closing_hour` varchar(5) collate utf8mb4_unicode_ci default null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`user_id`),
    constraint `sellers_ibfk_1` foreign key (`user_id`) references `users` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `service_order` */
create table `service_order` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `code` varchar(20) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `customer_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `task_category_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `device_type_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `description` text character set utf8mb4 collate utf8mb4_unicode_ci,
    `order_date` date not null,
    `address` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `latitude` double not null,
    `longitude` double not null,
    `order_type` varchar(2) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `status` varchar(10) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `technician_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `invoice_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `rating` tinyint unsigned default null,
    `review` text character set utf8mb4 collate utf8mb4_unicode_ci,
    `warranty_expiration_date` date default null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    unique key `code` (`code`),
    key `customer_id` (`customer_id`),
    key `task_category_id` (`task_category_id`),
    key `device_type_id` (`device_type_id`),
    key `technician_id` (`technician_id`),
    key `invoice_id` (`invoice_id`),
    constraint `service_order_ibfk_1` foreign key (`customer_id`) references `users` (`id`),
    constraint `service_order_ibfk_2` foreign key (`task_category_id`) references `task_categories` (`id`),
    constraint `service_order_ibfk_3` foreign key (`device_type_id`) references `device_types` (`id`),
    constraint `service_order_ibfk_4` foreign key (`technician_id`) references `users` (`id`),
    constraint `service_order_ibfk_5` foreign key (`invoice_id`) references `invoices` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `storefronts` */
create table `storefronts` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `seller_id` varchar(36) collate utf8mb4_unicode_ci not null,
    `name` varchar(50) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `seller_id` (`seller_id`),
    constraint `storefronts_ibfk_1` foreign key (`seller_id`) references `sellers` (`user_id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `task_categories` */
create table `task_categories` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `description` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `task_costs` */
create table `task_costs` (
    `task_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `cost_category_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `cost` double not null,
    primary key (`task_id`, `cost_category_id`),
    key `cost_category_id` (`cost_category_id`),
    constraint `task_costs_ibfk_1` foreign key (`task_id`) references `tasks` (`id`) on delete cascade,
    constraint `task_costs_ibfk_2` foreign key (`cost_category_id`) references `cost_categories` (`id`) on delete cascade
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `tasks` */
create table `tasks` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `description` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `category_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    key `category_id` (`category_id`),
    constraint `tasks_ibfk_1` foreign key (`category_id`) references `task_categories` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `technicians` */
create table `technicians` (
    `user_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `apitu_id` varchar(50) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `dpc_id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `address` text character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `area` varchar(50) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `city` varchar(50) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `zip_code` varchar(5) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `latitude` double not null,
    `longitude` double not null,
    `receive_order` tinyint(1) not null default '0',
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`user_id`),
    unique key `apitu_id` (`apitu_id`),
    key `dpc_id` (`dpc_id`),
    constraint `technicians_ibfk_1` foreign key (`user_id`) references `users` (`id`),
    constraint `technicians_ibfk_2` foreign key (`dpc_id`) references `dpc` (`id`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*table structure for table `users` */
create table `users` (
    `id` varchar(36) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `name` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `username` varchar(20) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `email` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `email_verified_at` timestamp null default null,
    `phone` varchar(15) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `phone_verified_at` timestamp null default null,
    `profile_pict` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `password` varchar(255) character set utf8mb4 collate utf8mb4_unicode_ci default null,
    `status` varchar(10) character set utf8mb4 collate utf8mb4_unicode_ci not null,
    `created_at` timestamp null default current_timestamp,
    `updated_at` timestamp null default current_timestamp on update current_timestamp,
    `deleted_at` timestamp null default null,
    primary key (`id`),
    unique key `username` (`username`),
    unique key `email` (`email`),
    unique key `phone` (`phone`)
) engine = innodb default charset = utf8mb4 collate = utf8mb4_unicode_ci;

/*!40101 set sql_mode=@old_sql_mode */
;

/*!40014 set foreign_key_checks=@old_foreign_key_checks */
;

/*!40014 set unique_checks=@old_unique_checks */
;

/*!40111 set sql_notes=@old_sql_notes */
;