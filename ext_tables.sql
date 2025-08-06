CREATE TABLE tx_hisnulmuslim_chapter (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    pid INT DEFAULT 0 NOT NULL,
    sys_language_uid INT DEFAULT 0 NOT NULL,
    l10n_parent INT DEFAULT 0 NOT NULL,
    tstamp INT DEFAULT 0 NOT NULL,
    crdate INT DEFAULT 0 NOT NULL,
    cruser_id INT DEFAULT 0 NOT NULL,
    deleted TINYINT(1) DEFAULT 0 NOT NULL,
    hidden TINYINT(1) DEFAULT 0 NOT NULL,
    sorting INT DEFAULT 0 NOT NULL,
    chapter_id INT DEFAULT 0 NOT NULL,
    title VARCHAR(255) DEFAULT '' NOT NULL,
    title_ar TEXT
);

CREATE TABLE tx_hisnulmuslim_dua (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    pid INT DEFAULT 0 NOT NULL,
    sys_language_uid INT DEFAULT 0 NOT NULL,
    l10n_parent INT DEFAULT 0 NOT NULL,
    tstamp INT DEFAULT 0 NOT NULL,
    crdate INT DEFAULT 0 NOT NULL,
    cruser_id INT DEFAULT 0 NOT NULL,
    deleted TINYINT(1) DEFAULT 0 NOT NULL,
    hidden TINYINT(1) DEFAULT 0 NOT NULL,
    sorting INT DEFAULT 0 NOT NULL,
    dua_id INT DEFAULT 0 NOT NULL,
    chapter INT DEFAULT 0 NOT NULL
);

CREATE TABLE tx_hisnulmuslim_dua_item (
    uid INT AUTO_INCREMENT PRIMARY KEY,
    pid INT DEFAULT 0 NOT NULL,
    sys_language_uid INT DEFAULT 0 NOT NULL,
    l10n_parent INT DEFAULT 0 NOT NULL,
    tstamp INT DEFAULT 0 NOT NULL,
    crdate INT DEFAULT 0 NOT NULL,
    cruser_id INT DEFAULT 0 NOT NULL,
    deleted TINYINT(1) DEFAULT 0 NOT NULL,
    hidden TINYINT(1) DEFAULT 0 NOT NULL,
    sorting INT DEFAULT 0 NOT NULL,
    dua INT DEFAULT 0 NOT NULL,
    type VARCHAR(50) DEFAULT '' NOT NULL,
    content MEDIUMTEXT
);