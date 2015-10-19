
-- ----------------------------
-- Table structure for `acl_role`
-- ----------------------------
DROP TABLE IF EXISTS `acl_role`;
CREATE TABLE `acl_role` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`name` varchar(32) not null,
	`note` varchar(100),
	`status` tinyint(2)	default 1,
	`created_at` int(11),
	`updated_at` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `acl_resource`
-- ----------------------------
DROP TABLE IF EXISTS `acl_resource`;
CREATE TABLE `acl_resource` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`id_parent` int(11),
	`name` varchar(200) not null,
	`action` varchar(50) not null, 
	`title` varchar(100),
	`note` varchar(100),
	`status` tinyint(2)	default 1,
	`created_at` int(11),
	`updated_at` int(11)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `acl_role_resource`
-- ----------------------------
DROP TABLE IF EXISTS `acl_role_resource`;
CREATE TABLE `acl_role_resource` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`id_acl_role` int(11),
	`id_acl_resource` int(11),
	`status` tinyint(2)	default 1,
	`created_at` int(11),
	`updated_at` int(11),
	KEY `acl_role` (`id_acl_role`),
	KEY `acl_resource` (`id_acl_resource`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
	`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	`id_acl_role` int(11) NOT NULL,
	`username` varchar(32) not null,
	`password` varchar(32) not null,
	`full_name` varchar(50),
	`status` tinyint(2)	default 1,
	`created_at` int(11),
	`updated_at` int(11),
	KEY `acl_role` (`id_acl_role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO acl_role(id, name, note, status) VALUES(1, 'Superadmin', 'superadmin', 1);
INSERT INTO acl_role(id, name, note, status) VALUES(2, 'Admin', 'admin', 1);
INSERT INTO admin(id, id_acl_role, username, password, full_name, status) VALUES(1, 1, 'superadmin', md5('superadmin_oe_phalcon_cms_2015'), 'Admin', 1);
INSERT INTO admin(id, id_acl_role, username, password, full_name, status) VALUES(2, 2, 'admin', md5('admin_oe_phalcon_cms_2015'), 'Admin', 1);