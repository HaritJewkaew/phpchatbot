/*
 Navicat Premium Data Transfer

 Source Server         : hosxp_master_2_50
 Source Server Type    : MySQL
 Source Server Version : 50744
 Source Host           : 192.168.2.50:3306
 Source Schema         : maechan

 Target Server Type    : MySQL
 Target Server Version : 50744
 File Encoding         : 65001

 Date: 04/10/2024 14:16:23
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for stretcher_type
-- ----------------------------
DROP TABLE IF EXISTS `stretcher_type`;
CREATE TABLE `stretcher_type`  (
  `stretcher_type_id` int(11) NOT NULL,
  `stretcher_type_name` varchar(200) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `stretcher_type_active` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `stretcher_type_o2tube_chk` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`stretcher_type_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stretcher_type
-- ----------------------------
INSERT INTO `stretcher_type` VALUES (1, 'นอน', 'Y', NULL);
INSERT INTO `stretcher_type` VALUES (3, 'นั่ง', 'Y', NULL);
INSERT INTO `stretcher_type` VALUES (4, 'นั่ง(มีล้อแล้ว)', 'Y', NULL);
INSERT INTO `stretcher_type` VALUES (5, 'ล้อเข็นนอนออกซิเจน', 'Y', 'Y');

SET FOREIGN_KEY_CHECKS = 1;
