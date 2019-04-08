/*
ColumnType  | Max Value (signed/unsigned)

  TINYINT   |   127 / 255
 SMALLINT   |   32767 / 65535
MEDIUMINT   |   8388607 / 16777215
      INT   |   2147483647 / 4294967295
   BIGINT   |   9223372036854775807 / 18446744073709551615
*/

create database sdstuca
	character set utf8mb4
	collate utf8mb4_unicode_520_ci;

create table if not exists restaurant (
	id          tinyint unsigned not null auto_increment, -- PK
	name        varchar(20)      not null, -- UK
	website_url varchar(50)      null,
	json_url    varchar(255),
	food        boolean          not null,
	kela        boolean          not null,
	latitude    decimal(10, 7)   not null comment 'in degrees',
	longitude   decimal(10, 7)   not null comment 'in degrees',
	address     varchar(50)      not null,
	city        varchar(20)      not null,
	primary key (id),
	unique key (name)
)
	default charset = utf8mb4
	collate = utf8mb4_unicode_520_ci
	auto_increment = 1;

create table if not exists openinghours (
	restaurant_id tinyint unsigned not null, -- PK, FK
	day_index     tinyint          not null comment 'mon = 1, sun = 7',
	open          varchar(5)       null comment 'e.g. "10:30", parsed when needed.',
	close         varchar(5)       null,
	lunch_open    varchar(5)       null comment 'Some restaurants have separate hours for lunch',
	lunch_close   varchar(5)       null,
	primary key (restaurant_id, day_index),
	constraint fk_normallunchhours_restaurant foreign key (restaurant_id) references restaurant (id)
)
	default charset = utf8mb4
	collate = utf8mb4_unicode_520_ci
	auto_increment = 1;

create table if not exists menuurls (
	restaurant_id tinyint unsigned not null,                                         -- PK, FK
	language      varchar(3)       not null comment 'Three character language code', -- PK
	url           varchar(255)     not null comment 'url to online menu, for given language',
	json_url      varchar(255)     null comment 'url to json menu, for given language',
	primary key ( restaurant_id, language ),
	constraint fk_menuurls_restaurant foreign key ( restaurant_id ) references restaurant( id )
)
	default charset = utf8mb4
	collate = utf8mb4_unicode_520_ci
	auto_increment = 1;

create table if not exists lang (
	lang     varchar(3)   not null comment 'Three character language code', -- PK
	txt_page varchar(25)  not null, -- PK
	txt_type varchar(25)  not null, -- PK
	txt      varchar(255) not null,
	primary key (lang, txt_page, txt_type)
)
	default charset = utf8mb4
	collate = utf8mb4_unicode_520_ci
	auto_increment = 1;

