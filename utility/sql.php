<?php

define("SQL_COUNT_CHILDREN", <<<SQL
	select 
		count(*)
	from Children
	where
		Children.status = 'live'
SQL
);

define("SQL_SELECT_RANDOM_CHILDREN", <<<SQL
	select
		utf_codepoint,
		template_id,
		children_id
	from Children
	Left Join Template
		Using (template_id)
	where
		Children.status = 'live'
	limit 1
	offset ?
SQL
);

define("SQL_SELECT_POINTS_FOR_CHILD", <<<SQL
	select
		X(pt) as x,
		Y(pt) as y,
		sz as s
	from Points
	where
		children_id = ?
	order by o
SQL
);

define("SQL_INSERT_CORRECT", <<<SQL
	insert into
		Correct (
			children_id,
			correct
		)
		values (
			?,
			?
		)
SQL
);

define("SQL_UPDATE_FITNESS", <<<SQL
	update
		Children,
		Correct
	set
		fitness = (
			select
				avg(correct)
			from
				Correct
			where
				Correct.children_id = ?
		)
	where
		Children.children_id = ?
SQL
);

define("SQL_DELETE_LESS_FIT", <<<SQL
	update Children
	set
		status = 'dead'
	where
		template_id = ?
	order by
		fitness
	limit
		?
SQL
);

define("SQL_SELECT_MOST_FIT", <<<SQL
	select
		X(pt),
		Y(pt),
		sz,
		o
	from
		Points
	Left Join Children
		using(children_id)
	where
		template_id = ? and
		status = 'live'
	order by
		fitness desc,
		children_id,
		o
	limit ?
SQL
);

define("SQL_INSERT_EVENT", <<<SQL
	insert into Events (
		template_id,
		type
	) values (
		?,
		?
	)
		
SQL
);

define("SQL_SELECT_NUM_SEGMENTS", <<<SQL
	select 
		segments
	from Template
	where template_id = ?
SQL
);


define("SQL_INSERT_CHILD", <<<SQL
	insert into Children (
		template_id
	) values (
		?
	)
SQL
);

define("SQL_INSERT_POINT", <<<SQL
	insert into Points (
		children_id,
		pt,
		sz,
		o
	) values (
		?,
		GeomFromWKB(Point(?, ?)),
		?,
		?
	)
SQL
);

define("SQL_UPDATE_CHILD_BIRTH", <<<SQL
	update Children
	set
		status = 'live'
	where
		children_id = '%d'
SQL
);

define("SQL_SELECT_LESS_FIT", <<<SQL
	select
		count(*) as cn,
		fitness,
		children_id
	from Correct
	left join Children
		using (children_id)
	group by children_id
	order by
		cn desc,
		fitness
SQL
);

define("SQL_SELECT_CHILD_DETAIL", <<<SQL
select
	utf_codepoint,
	segments,
	(now() - dob) as age,
	status,
	fitness
from Children
left join Template
	using (template_id)
where
	children_id = ?
SQL
);
?>
