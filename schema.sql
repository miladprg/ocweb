-- ----------------------------
-- Table structure for ADMINS
-- ----------------------------
DROP TABLE IF EXISTS "ADMINS";
CREATE TABLE "ADMINS" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "fullname" TEXT,
  "username" TEXT,
  "password" TEXT,
  "type" TEXT,
  "addDate" TEXT
);

-- ----------------------------
-- Table structure for NOTES
-- ----------------------------
DROP TABLE IF EXISTS "NOTES";
CREATE TABLE "NOTES" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "text" TEXT,
  "type" TEXT,
  "addDate" TEXT
);

-- ----------------------------
-- Table structure for SERVERS
-- ----------------------------
DROP TABLE IF EXISTS "SERVERS";
CREATE TABLE "SERVERS" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "title" TEXT,
  "ip" TEXT,
  "password" TEXT,
  "port" TEXT,
  "addDate" TEXT
);

-- ----------------------------
-- Table structure for USERS
-- ----------------------------
DROP TABLE IF EXISTS "USERS";
CREATE TABLE "USERS" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "fullname" TEXT,
  "username" TEXT,
  "password" TEXT,
  "reagent" TEXT,
  "group" TEXT,
  "status" TEXT,
  "addDate" TEXT
);

-- ----------------------------
-- Table structure for COMMANDS
-- ----------------------------
DROP TABLE IF EXISTS "COMMANDS";
CREATE TABLE "COMMANDS" (
  "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  "command" TEXT,
  "addDate" TEXT
);

PRAGMA foreign_keys = true;
