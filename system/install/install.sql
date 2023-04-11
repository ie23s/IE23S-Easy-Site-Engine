CREATE TABLE sessions
(
    session_id  VARCHAR(26) NULL,
    server_id   VARCHAR(64) NOT NULL,
    user_id     INT NOT NULL,
    expired     TINYINT(1) DEFAULT 0 NOT NULL,
    login_time  DATETIME NULL,
    expire_time DATETIME NULL,
    CONSTRAINT session_id UNIQUE (session_id)
);

CREATE TABLE users
(
    id         INT auto_increment PRIMARY KEY,
    email      VARCHAR(255) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name  VARCHAR(255) NULL,
    salt       VARCHAR(255) NOT NULL,
    hash       VARCHAR(255) NULL,
    `group`    INT NULL,
    CONSTRAINT email UNIQUE (email)
);

CREATE TABLE files
(
    id      INT auto_increment PRIMARY KEY,
    name    VARCHAR(255) NULL,
    date    INT NULL,
    used_by INT NULL
);

CREATE TABLE `groups`
(
    id        INT auto_increment PRIMARY KEY,
    name      VARCHAR(255) NULL,
    parent_id INT NULL
);

INSERT INTO `groups`(`id`, `name`, `parent_id`) VALUES (1,'guest',NULL);
INSERT INTO `groups`(`id`, `name`, `parent_id`) VALUES (2,'user',1);

CREATE TABLE group_permissions
(
    id         INT auto_increment PRIMARY KEY,
    group_id   INT NULL,
    permission VARCHAR(255) NOT NULL,
    CONSTRAINT group_permissions_groups_id_fk FOREIGN KEY (group_id) REFERENCES
        `groups` (id)
);

CREATE TABLE languages
(
    id            INT auto_increment PRIMARY KEY,
    original_name VARCHAR(255) NOT NULL comment 'Name of lang',
    icon          VARCHAR(255) NULL comment 'Icon name of lang',
    shorts        VARCHAR(255) NOT NULL comment
        'Short names of lang (separate with commas ",")',
    name          VARCHAR(2) NOT NULL,
    CONSTRAINT name UNIQUE (name)
)
    comment 'Languages list';

CREATE TABLE language
(
    id      INT auto_increment PRIMARY KEY,
    lang_id INT NOT NULL comment 'ID of lang',
    `key`   VARCHAR(255) NOT NULL,
    value   TEXT NOT NULL,
    CONSTRAINT language_ibfk_1 FOREIGN KEY (lang_id) REFERENCES languages (id)
)
    comment 'Language table with all texts';

CREATE INDEX lang_id ON language (lang_id);

CREATE TABLE language_editable
(
    id          INT auto_increment PRIMARY KEY,
    lang_id     INT NOT NULL comment 'ID of lang',
    value       TEXT NOT NULL,
    type        VARCHAR(255) NOT NULL,
    external_id INT NULL,
    CONSTRAINT language_editable_ibfk_1 FOREIGN KEY (lang_id) REFERENCES
        languages (id)
)
    comment 'Language table with all user-editable texts';

CREATE INDEX lang_id ON language_editable (lang_id);