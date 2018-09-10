
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
