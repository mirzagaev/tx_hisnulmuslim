--
-- Table structure for table 'tt_content'
--
CREATE TABLE tt_content (
    tx_hisnulmuslim_chapter int(11) unsigned DEFAULT '0',
);


--
-- Table structure for table 'tx_hisnulmuslim_chapter'
--
CREATE TABLE tx_hisnulmuslim_chapter (
    id
    sort
);

--
-- Table structure for table 'tx_hisnulmuslim_dua'
--
CREATE TABLE tx_hisnulmuslim_dua (
    id
    chapter_id
    sort
);

--
-- Table structure for table 'tx_hisnulmuslim_dua'
--
CREATE TABLE tx_hisnulmuslim_dua_item (
    id
    dua_id
    sort
);