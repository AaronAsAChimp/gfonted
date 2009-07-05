<?php
require_once("../config.php");
require_once("sql.php");
require_once("math.php");

class Mutation_Engine {
	protected $source_db = null;
	protected $sink_db = null;
	private $birth = "birth";
	private $death = "death";
	private $death_rate = GFE_DEATH_RATE;
	private $num_parents = GFE_NUM_PARENTS;
	
	function __construct() {
		$this->source_db = new mysqli(db_lctn, db_user, db_pass, db_db);
		$this->sink_db = new mysqli(db_lctn, db_user, db_pass, db_db);
	}
	
	private function display_parents($parent, $hilite, $title) {
		?>
		<h2><?= $title ?></h2>
		<table>
		<th><tr>
			<td>x</td>
			<td>y</td>
			<td>z</td>
		</tr></th>
		<?
		foreach($parent as $idx => $point) {
			?>
			<tr style="<?=(($idx == $hilite)? "color: red" : "")?>">
				<td><?=$point['x']?></td>
				<td><?=$point['y']?></td>
				<td><?=$point['sz']?></td>
			</tr>
			<?
		}
		?>
		</table>
		<?
	}
	
	function euthanize($id) {
		// create event
		$stmt = $this->sink_db->prepare(SQL_INSERT_EVENT);
		$stmt->bind_param("is", $id, $this->death);
		$stmt->execute();
		$stmt->close();
		
		// kill least fitting children
		//   I'm so disappointed in you son, if only your 
		//   fitness function returned a higher value.
		
		$stmt = $this->source_db->prepare(SQL_DELETE_LESS_FIT);
		$stmt->bind_param("ii",$id, $this->death_rate);
		$stmt->execute();
		$stmt->close();
	}
	
	function conceive($id) {
		// get number of segments
		$segments = 0;
		$stmt = $this->source_db->prepare(SQL_SELECT_NUM_SEGMENTS);
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->bind_result($segments);
		$stmt->fetch();
		$stmt->close();
		
		// select parents
		$parents = Array();
		$total_points = $this->num_parents * $segments;
		$stmt = $this->source_db->prepare(SQL_SELECT_MOST_FIT);
		$stmt->bind_param("ii", $id, $total_points);
		$stmt->execute();
		$stmt->bind_result($kid_id, $px, $py, $psz, $po);

		while($stmt->fetch()) {
			
			// messy I know
			$_SESSION['parent_ids'][$id][$kid_id] = $kid_id;
			
			// not messy, :)
			$parents[$po][] = Array (
				'x' => $px,
				'y' => $py,
				'sz' => $psz);
			
		}
		
		$_SESSION['parent_ids'][$id] = array_values($_SESSION['parent_ids'][$id]);
		
		/*echo "<pre>";
		var_dump($parents);
		echo "</pre>";*/
		
		$stmt->close();
		
		// create new genomes
		//    Science! She blinded me with Science!		
		$ch_prep = $this->source_db->prepare(SQL_INSERT_CHILD);
		$ch_prep->bind_param("i", $id);

		$pt_prep = $this->sink_db->prepare(SQL_INSERT_POINT);
		$pt_prep->bind_param("idddi", $cid, $cx, $cy, $csz, $co);
		
		for($i = 0; $i < GFE_DEATH_RATE; $i++) {
			$ch_prep->execute();
			$cid = $ch_prep->insert_id;
			
			$_SESSION['offspring_ids'][$id][] = $cid;

			// populate the points
			for($j = 0; $j < $segments; $j++) {
				$idx = mt_rand(0, GFE_NUM_PARENTS - 1);
				
				$cx = clamp($parents[$j][$idx]['x'] + random_val(-GFE_MUTATION_JITTER, GFE_MUTATION_JITTER), GFE_MAX_X, GFE_MIN_X);
				$cy = clamp($parents[$j][$idx]['y'] + random_val(-GFE_MUTATION_JITTER, GFE_MUTATION_JITTER), GFE_MAX_Y, GFE_MIN_Y);
				$csz = clamp($parents[$j][$idx]['sz'] + random_val(-GFE_MUTATION_JITTER, GFE_MUTATION_JITTER), GFE_MAX_SIZE, GFE_MIN_SIZE);
				$co = $j;
				
				//$this->display_parents($parents[$j], $idx, "item:  " . $j . "  index:  " . $idx);
				
				$pt_prep->execute();

			}
			
			// birth the child
			$this->source_db->query(sprintf(SQL_UPDATE_CHILD_BIRTH, $cid));
		}
		$pt_prep->close();
		$ch_prep->close();
		
		// create event
		$stmt = $this->sink_db->prepare(SQL_INSERT_EVENT);
		$stmt->bind_param("is",$id, $this->birth);
		$stmt->execute();
		$stmt->close();
	}
}
?>
