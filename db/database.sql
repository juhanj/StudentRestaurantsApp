create table if not exists restaurant (
	id          tinyint unsigned not null auto_increment, -- PK
	name        varchar(20)      not null, -- UK
	website_url varchar(50)      not null,
	json_url    varchar(255),
	latitude    decimal(10, 7)   not null comment 'in degrees',
	longitude   decimal(10, 7)   not null comment 'in degrees',
	food        boolean          not null,
	kela        boolean          not null,
	address     varchar(50)      not null,
	city        varchar(20)      not null,
	primary key (id),
	unique key (name)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;

create table if not exists openinghours (
	restaurant_id tinyint unsigned not null, -- PK, FK
	day_index     tinyint          not null comment 'mon = 1, sun = 7',
	open          varchar(5)       null comment 'e.g. "10:30", parsed when needed.',
	close         varchar(5)       null,
	lunch_open    varchar(5)       null comment 'Some restaurants have separate hours for lunch',
	lunch_close   varchar(5)       null,
	primary key (restaurant_id),
	constraint fk_normallunchhours_restaurant foreign key (restaurant_id) references restaurant (id)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;


create table if not exists food (
	id            smallint unsigned not null, -- PK
	restaurant_id tinyint unsigned  not null, -- PK, FK
	name          varchar(20)       not null,
	price_student decimal(4, 2)     null,
	price_staff   decimal(4, 2)     not null,
	price_guest   decimal(4, 2)     not null,
	vegetarian    boolean           not null,
	primary key (id, restaurant_id),
	constraint fk_food_restaurant foreign key (restaurant_id) references restaurant (id)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;

create table if not exists menu_components (
	food_id          smallint unsigned not null, -- PK
	foodcomponent_id varchar(50)       not null,
	primary key (food_id, foodcomponent_id)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;

create table if not exists food_component (
	id   smallint unsigned not null, -- PK
	name varchar(50)       not null, -- UK
	primary key (id),
	unique key (name)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;


create table if not exists user (
	id   tinyint unsigned not null, -- PK
	name varchar(20)      not null, -- UK
	primary key (id),
	unique key (name)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;


create table if not exists favourite_food (
	user_id smallint unsigned not null, -- PK
	food_id varchar(50)       not null, -- PK
	primary key (user_id, food_id)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;

create table if not exists lang (
	lang     varchar(3)   not null comment 'Three character language code', -- PK
	txt_page varchar(25)  not null, -- PK
	txt_type varchar(25)  not null, -- PK
	txt      varchar(255) not null,
	primary key (lang, txt_page, txt_type)
)
	default charset = utf8
	collate = utf8_swedish_ci
	auto_increment = 1;
