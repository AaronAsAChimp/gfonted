create table Template (
	template_id int unsigned auto_increment,
	utf_codepoint char(1) not null,
	segments tinyint unsigned not null,
	max_children tinyint unsigned not null,
	
	primary key(template_id)
)
ENGINE=INNODB
DEFAULT CHARACTER SET utf8;



create table Children (
	children_id int unsigned auto_increment,
	template_id int unsigned not null,
	dob timestamp default now(),
	fitness double default 0,
	status enum('prenatal', 'live', 'dead') default 'prenatal',
	
	primary key(children_id),
	foreign key(template_id) references Template(template_id) on delete cascade
)
ENGINE=INNODB
DEFAULT CHARACTER SET utf8;


	
create table Points (
	point_id int unsigned auto_increment,
	children_id int unsigned not null,
	o tinyint unsigned not null,
	pt point not null,
	sz double not null,
	
	primary key(point_id),
	foreign key(children_id) references Children(children_id)  on delete cascade
)
ENGINE=INNODB
DEFAULT CHARACTER SET utf8;



create table Correct (
	vote_time timestamp default now(),
	correct_id int unsigned auto_increment,
	children_id int unsigned not null,
	correct bool not null,

	primary key(correct_id),
	foreign key(children_id) references Children(children_id)  on delete cascade
)
ENGINE=INNODB
DEFAULT CHARACTER SET utf8;



create table Events (
	event_id int unsigned auto_increment,
	template_id int unsigned not null,
	occured timestamp default now(),
	type enum('birth', 'death'),

	primary key(event_id),
	foreign key(template_id) references Template(template_id) on delete cascade
)
ENGINE=INNODB
DEFAULT CHARACTER SET utf8;
