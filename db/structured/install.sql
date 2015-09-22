-- ----------------------------
-- Table structure for `admin_rule`
-- ----------------------------
DROP TABLE IF EXISTS `admin_role`;
CREATE TABLE `admin_role` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`parent_id` int(11) NOT NULL,
	`resource` varchar(200) not null,
	`name` varchar(50),
	`status` tinyint(2)	default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `admin_rule`
-- ----------------------------
DROP TABLE IF EXISTS `admin_rule`;
CREATE TABLE `admin_rule` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`role_id` int(11) NOT NULL,
	`resource` varchar(200) not null COMMENT 'backend/user/create', 
	`resource_name` varchar(50) COMMENT 'Create new user',
	`status` tinyint(2)	default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`username` varchar(32) not null,
	`password` varchar(32) not null,
	`role` varchar(32),
	`full_name` varchar(50),
	`status` tinyint(2)	default 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO admin(id, username, password, role, full_name, status) VALUES(1, 'superadmin', md5('superadminoephalconcms_2015'), 1, 'Super Admin', 1);
INSERT INTO admin(id, username, password, role, full_name, status) VALUES(2, 'admin', md5('adminoephalconcms_2015'),  2, 'Admin', 1);