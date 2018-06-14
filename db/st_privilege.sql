--liquibase formatted sql

--changeset weipinglee:2018061401
INSERT INTO `st_privilege` VALUES ('1', '权限管理', '', '', '', '0', '1');
INSERT INTO `st_privilege` VALUES ('2', '权限列表', 'Admin', 'Privilege', 'lst', '1', '0');
INSERT INTO `st_privilege` VALUES ('3', '添加权限', 'Privilege', 'Admin', 'add', '2', '1');
INSERT INTO `st_privilege` VALUES ('4', '修改权限', 'Admin', 'Privilege', 'edit', '2', '1');
INSERT INTO `st_privilege` VALUES ('5', '删除权限', 'Admin', 'Privilege', 'delete', '2', '1');
INSERT INTO `st_privilege` VALUES ('6', '角色列表', 'Admin', 'Role', 'lst', '1', '0');
INSERT INTO `st_privilege` VALUES ('7', '添加角色', 'Admin', 'Role', 'add', '6', '1');
INSERT INTO `st_privilege` VALUES ('8', '修改角色', 'Admin', 'Role', 'edit', '6', '1');
INSERT INTO `st_privilege` VALUES ('9', '删除角色', 'Admin', 'Role', 'delete', '6', '1');
INSERT INTO `st_privilege` VALUES ('10', '管理员列表', 'Admin', 'Admin', 'lst', '1', '1');
INSERT INTO `st_privilege` VALUES ('11', '添加管理员', 'Admin', 'Admin', 'add', '10', '1');
INSERT INTO `st_privilege` VALUES ('12', '修改管理员', 'Admin', 'Admin', 'edit', '10', '1');
INSERT INTO `st_privilege` VALUES ('13', '删除管理员', 'Admin', 'Admin', 'delete', '10', '1');
INSERT INTO `st_privilege` VALUES ('20', '人员管理', '', '', '', '0', '1');
INSERT INTO `st_privilege` VALUES ('114', '人员列表', 'Admin', 'Person', 'lst', '20', '1');
INSERT INTO `st_privilege` VALUES ('115', '人员添加', 'Admin', 'Person', 'add', '114', '1');
INSERT INTO `st_privilege` VALUES ('116', '人员删除', 'Admin', 'Person', 'delete', '114', '1');
INSERT INTO `st_privilege` VALUES ('117', '人员编辑', 'Admin', 'Person', 'edit', '114', '1');
INSERT INTO `st_privilege` VALUES ('118', '已结束', 'Admin', 'Loan', 'lst?status=over', '126', '1');
INSERT INTO `st_privilege` VALUES ('119', '借贷添加', 'Admin', 'Loan', 'add', '118', '1');
INSERT INTO `st_privilege` VALUES ('120', '借贷编辑', 'Admin', 'Loan', 'edit', '118', '1');
INSERT INTO `st_privilege` VALUES ('121', '未结束', 'Admin', 'Storage', 'lst?status=over', '128', '1');
INSERT INTO `st_privilege` VALUES ('122', '入仓添加', 'Admin', 'Storage', 'add', '121', '1');
INSERT INTO `st_privilege` VALUES ('123', '入仓编辑', 'Admin', 'Storage', 'edit', '121', '1');
INSERT INTO `st_privilege` VALUES ('124', '入仓删除', 'Admin', 'Storage', 'delete', '121', '1');
INSERT INTO `st_privilege` VALUES ('125', '借贷删除', 'Admin', 'Loan', 'delete', '118', '1');
INSERT INTO `st_privilege` VALUES ('126', '借贷管理', '', '', '', '0', '1');
INSERT INTO `st_privilege` VALUES ('127', '未结束', 'Admin', 'Loan', 'lst?status=unover', '126', '1');
INSERT INTO `st_privilege` VALUES ('128', '入仓管理', '', '', '', '0', '1');
INSERT INTO `st_privilege` VALUES ('129', '已结束', 'Admin', 'Storage', 'lst?status=unover', '128', '1');
