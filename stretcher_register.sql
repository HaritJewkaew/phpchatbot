/*
 Navicat Premium Data Transfer

 Source Server         : Hosxp
 Source Server Type    : MySQL
 Source Server Version : 50744
 Source Host           : 192.168.2.50:3306
 Source Schema         : maechan

 Target Server Type    : MySQL
 Target Server Version : 50744
 File Encoding         : 65001

 Date: 21/05/2024 14:15:22
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for stretcher_register
-- ----------------------------
DROP TABLE IF EXISTS `stretcher_register`;
CREATE TABLE `stretcher_register`  (
  `stretcher_register_id` int(11) NOT NULL,
  `stretcher_register_date` date NULL DEFAULT NULL,
  `stretcher_register_time` time NULL DEFAULT NULL,
  `hn` varchar(9) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `from_depcode` varchar(3) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `from_note` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `send_depcode` varchar(3) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `send_note` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `stretcher_register_send_time` time NULL DEFAULT NULL,
  `stretcher_register_return_time` time NULL DEFAULT NULL,
  `stretcher_register_apm_date` date NULL DEFAULT NULL,
  `stretcher_register_apm_time` time NULL DEFAULT NULL,
  `stretcher_work_status_note` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `lastupdate` datetime NULL DEFAULT NULL,
  `stretcher_register_staff` int(11) NULL DEFAULT NULL,
  `stretcher_register_officer` int(11) NULL DEFAULT NULL,
  `stretcher_register_apm_chk` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `stretcher_type_id` int(11) NULL DEFAULT NULL,
  `stretcher_o2tube_type_id` int(11) NULL DEFAULT NULL,
  `stretcher_service_id` int(11) NULL DEFAULT NULL,
  `stretcher_work_status_id` int(11) NULL DEFAULT NULL,
  `stretcher_priority_id` int(11) NULL DEFAULT NULL,
  `stretcher_emergency_id` int(11) NULL DEFAULT NULL,
  `stretcher_team_list_id` int(11) NULL DEFAULT NULL,
  `stretcher_work_result_id` int(11) NULL DEFAULT NULL,
  `stretcher_work_result_detail` varchar(255) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `stretcher_register_hn_chk` char(1) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  `stretcher_register_accept_date` date NULL DEFAULT NULL,
  `stretcher_register_accept_time` time NULL DEFAULT NULL,
  `doctor_request` varchar(7) CHARACTER SET tis620 COLLATE tis620_thai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`stretcher_register_id`) USING BTREE,
  INDEX `ix_idx_stretcher_register_type`(`stretcher_type_id`) USING BTREE,
  INDEX `ix_idx_stretcher_register_send`(`send_depcode`) USING BTREE,
  INDEX `ix_idx_stretcher_register_priority`(`stretcher_priority_id`) USING BTREE,
  INDEX `ix_idx_stretcher_register_work_status`(`stretcher_work_status_id`) USING BTREE,
  INDEX `ix_idx_stretcher_register_date`(`stretcher_register_date`) USING BTREE,
  INDEX `ix_idx_stretcher_register_hn`(`hn`) USING BTREE,
  INDEX `ix_idx_stretcher_register_from`(`from_depcode`) USING BTREE,
  INDEX `ix_idx_stretcher_register_emergency`(`stretcher_emergency_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = tis620 COLLATE = tis620_thai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of stretcher_register
-- ----------------------------
INSERT INTO `stretcher_register` VALUES (69, '2024-04-09', '15:44:07', '660001886', '160', NULL, '170', NULL, NULL, NULL, '2024-04-09', '15:44:07', NULL, '2024-04-09 15:44:20', 879, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 'Y', NULL, NULL, '0879');
INSERT INTO `stretcher_register` VALUES (70, '2024-04-11', '13:13:48', '520006398', '160', NULL, '169', NULL, '13:20:42', '13:21:02', '2024-04-11', '13:13:48', NULL, '2024-04-11 13:21:05', 879, NULL, NULL, 1, NULL, 1, 4, 1, NULL, 5, NULL, NULL, NULL, '2024-04-11', '13:14:59', '0879');
INSERT INTO `stretcher_register` VALUES (73, '2024-04-11', '13:21:24', '510006459', '160', NULL, '190', NULL, NULL, NULL, '2024-04-11', '13:21:24', NULL, '2024-04-11 13:26:29', 879, NULL, NULL, 1, NULL, 1, 2, 1, NULL, 5, NULL, NULL, NULL, '2024-04-11', '13:26:19', '0879');
INSERT INTO `stretcher_register` VALUES (75, '2024-04-11', '13:28:31', '650012479', '010', NULL, '070', NULL, '13:33:51', '13:34:05', '2024-04-11', '13:28:31', NULL, '2024-04-11 13:34:08', 52, NULL, NULL, 3, NULL, 1, 4, 1, NULL, 5, NULL, NULL, NULL, '2024-04-11', '13:33:29', '0052');
INSERT INTO `stretcher_register` VALUES (77, '2024-04-11', '13:49:52', '670002750', '010', NULL, '170', NULL, '13:52:42', '13:53:09', '2024-04-11', '14:30:52', 'ทดสอบ', '2024-04-11 14:15:14', 52, NULL, NULL, 3, NULL, 1, 5, 1, NULL, 5, NULL, NULL, NULL, '2024-04-11', '13:51:37', '0052');
INSERT INTO `stretcher_register` VALUES (78, '2024-04-11', '13:54:03', '560004271', '155', NULL, '107', NULL, NULL, NULL, '2024-04-11', '13:54:03', NULL, '2024-04-11 13:54:38', 52, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0052');
INSERT INTO `stretcher_register` VALUES (80, '2024-04-11', '14:06:50', '', '010', NULL, '182', NULL, NULL, NULL, '2024-04-11', '14:06:50', NULL, '2024-04-11 14:10:32', 52, NULL, NULL, 1, NULL, 1, 2, 1, NULL, 5, NULL, NULL, 'Y', '2024-04-11', '14:10:23', '0052');
INSERT INTO `stretcher_register` VALUES (82, '2024-04-11', '15:04:36', '430019894', '170', NULL, '211', NULL, NULL, NULL, '2024-04-11', '15:04:36', NULL, '2024-04-11 15:04:46', 879, NULL, NULL, 4, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0879');
INSERT INTO `stretcher_register` VALUES (84, '2024-04-11', '15:08:10', '430019894', '170', NULL, '182', NULL, NULL, NULL, '2024-04-11', '15:08:10', NULL, '2024-04-11 15:08:19', 879, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, 'Y', NULL, NULL, '0879');
INSERT INTO `stretcher_register` VALUES (89, '2024-04-11', '15:37:02', '450023286', '024', NULL, '107', NULL, NULL, NULL, '2024-04-11', '15:37:02', 'ยกเลิก', '2024-04-11 15:40:25', 1328, NULL, NULL, 4, NULL, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1328');
INSERT INTO `stretcher_register` VALUES (103, '2024-04-17', '08:36:16', '480013571', '104', NULL, '032', NULL, NULL, NULL, '2024-04-17', '08:36:16', NULL, '2024-04-17 08:37:02', 82, NULL, NULL, 4, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0082');
INSERT INTO `stretcher_register` VALUES (116, '2024-04-18', '10:33:55', '640011150', '157', 'ใช้ออกซิเจน', '032', 'อาคารรักษาพยาบาล', NULL, NULL, '2024-04-18', '10:33:55', NULL, '2024-04-18 10:35:31', 81, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0081');
INSERT INTO `stretcher_register` VALUES (119, '2024-04-26', '14:15:43', '450002692', '020', 'มีเปลนอนแล้ว', '146', NULL, NULL, NULL, '2024-04-26', '14:15:43', 'ขอเปลผ่านระบบวิทยุสื่อสารแล้ว', '2024-04-26 14:23:57', 1126, NULL, NULL, 1, NULL, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1126');
INSERT INTO `stretcher_register` VALUES (123, '2024-05-01', '09:59:45', '480009406', '020', NULL, '070', NULL, NULL, NULL, '2024-05-01', '09:59:45', NULL, '2024-05-01 10:00:08', 1273, NULL, NULL, 4, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1273');
INSERT INTO `stretcher_register` VALUES (127, '2024-05-01', '11:27:32', '460014016', '126', 'ออกซิเจน', '070', NULL, NULL, NULL, '2024-05-01', '11:27:32', NULL, '2024-05-01 11:28:25', 788, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0788');
INSERT INTO `stretcher_register` VALUES (128, '2024-05-01', '13:25:38', '560000349', '047', 'ออกซิเจน', '158', NULL, NULL, NULL, '2024-05-01', '13:25:38', 'ICU', '2024-05-01 13:30:31', 1171, NULL, NULL, 1, NULL, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1171');
INSERT INTO `stretcher_register` VALUES (129, '2024-05-01', '13:28:02', '460014016', '126', 'ออกซิเจน', '070', NULL, NULL, NULL, '2024-05-01', '13:30:00', 'ซ้ำ', '2024-05-01 13:29:37', 1171, 1171, NULL, 1, NULL, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1171');
INSERT INTO `stretcher_register` VALUES (130, '2024-05-01', '13:28:57', '460014016', '126', 'ออกซิเจน', '070', NULL, NULL, NULL, '2024-05-01', '13:28:57', NULL, '2024-05-01 13:29:21', 1171, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1171');
INSERT INTO `stretcher_register` VALUES (131, '2024-05-01', '13:30:33', '560000349', '126', 'ออกซิเจน', '158', NULL, NULL, NULL, '2024-05-01', '13:30:33', NULL, '2024-05-01 13:30:50', 1171, NULL, NULL, 1, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1171');
INSERT INTO `stretcher_register` VALUES (133, '2024-05-03', '09:08:42', '540001436', '027', NULL, '070', NULL, NULL, NULL, '2024-05-03', '10:15:00', NULL, '2024-05-03 09:09:35', 1298, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1298');
INSERT INTO `stretcher_register` VALUES (134, '2024-05-03', '09:10:15', '450020776', '027', NULL, '070', NULL, NULL, NULL, '2024-05-03', '10:20:00', NULL, '2024-05-03 09:12:04', 1332, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1332');
INSERT INTO `stretcher_register` VALUES (135, '2024-05-03', '09:10:22', '630002812', '027', NULL, '070', NULL, NULL, NULL, '2024-05-03', '10:20:00', NULL, '2024-05-03 09:12:02', 294, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0294');
INSERT INTO `stretcher_register` VALUES (136, '2024-05-03', '09:12:44', '440017484', '027', NULL, '070', NULL, NULL, NULL, '2024-05-03', '10:20:00', NULL, '2024-05-03 09:13:49', 1332, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1332');
INSERT INTO `stretcher_register` VALUES (137, '2024-05-03', '09:14:48', '430009161', '027', NULL, '070', NULL, NULL, NULL, '2024-05-03', '10:15:00', NULL, '2024-05-03 09:16:32', 1332, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1332');
INSERT INTO `stretcher_register` VALUES (138, '2024-05-03', '09:36:07', '460007775', '046', NULL, '071', NULL, NULL, NULL, '2024-05-03', '09:36:07', NULL, '2024-05-03 09:36:53', 318, NULL, NULL, 4, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0318');
INSERT INTO `stretcher_register` VALUES (139, '2024-05-03', '15:48:33', '600000872', '022', NULL, '127', NULL, NULL, NULL, '2024-05-03', '15:48:33', 'ระบบยังไม่สมบูรณ์', '2024-05-03 15:50:36', 1110, NULL, NULL, 1, NULL, NULL, 5, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1110');
INSERT INTO `stretcher_register` VALUES (140, '2024-05-07', '08:21:15', '540001436', '027', NULL, '070', NULL, NULL, NULL, '2024-05-07', '10:15:00', NULL, '2024-05-07 08:22:21', 1240, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1240');
INSERT INTO `stretcher_register` VALUES (143, '2024-05-15', '14:16:52', '490004534', '170', NULL, '169', NULL, '14:19:33', '14:20:34', '2024-05-15', '14:16:52', NULL, '2024-05-15 14:20:41', 879, NULL, NULL, 5, 1, 1, 4, 1, NULL, 5, NULL, NULL, NULL, '2024-05-15', '14:18:44', '0879');
INSERT INTO `stretcher_register` VALUES (144, '2024-05-16', '10:55:37', '540010402', '047', NULL, '070', NULL, NULL, NULL, '2024-05-16', '10:55:37', NULL, '2024-05-16 10:56:00', 81, NULL, NULL, 3, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0081');
INSERT INTO `stretcher_register` VALUES (145, '2024-05-20', '09:35:56', '420017002', '104', NULL, '007', NULL, NULL, NULL, '2024-05-20', '09:35:56', NULL, '2024-05-20 09:36:36', 90, NULL, NULL, 4, NULL, NULL, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0090');
INSERT INTO `stretcher_register` VALUES (146, '2024-05-21', '11:05:44', '610006742', '047', NULL, '010', NULL, NULL, NULL, '2024-05-21', '11:05:44', NULL, '2024-05-21 11:06:11', 81, NULL, NULL, 1, NULL, NULL, 1, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0081');

SET FOREIGN_KEY_CHECKS = 1;
