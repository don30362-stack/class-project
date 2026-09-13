<?php
require_once __DIR__ . '/escape.php';
function activeShow($num, $chkPoint)
{
	return (($num == $chkPoint) ? 'active' : '');
}

function getCategoryTree($link)
{
	static $categoryTree = null;

	if ($categoryTree !== null) {
		return $categoryTree;
	}

	$categoryTree = array(
		'parents' => array(),
		'children' => array(),
	);
	$categoryStatement = $link->query(
		'SELECT classid, cname, fonticon, level, uplink
		 FROM pyclass
		 WHERE level IN (1, 2)
		 ORDER BY level, sort, classid'
	);

	foreach ($categoryStatement->fetchAll(PDO::FETCH_ASSOC) as $category) {
		if ((int)$category['level'] === 1) {
			$categoryTree['parents'][] = $category;
			continue;
		}

		$parentId = (int)$category['uplink'];
		$categoryTree['children'][$parentId][] = $category;
	}

	return $categoryTree;
}

function buildNavigation($pageNum_Recordset1, $totalPages_Recordset1, $prev_Recordset1, $next_Recordset1, $separator = " | ", $max_links = 10, $show_page = true, $selmode = 1, $sname = "")
{
	$gmaxRows = "maxRows_" . $sname;
	$gtotalRows = "totalRows_" . $sname;

	global $$gmaxRows, $$gtotalRows;
	$pagesArray = "";
	$firstArray = "";
	$lastArray = "";
	if ($max_links < 2) $max_links = 2;
	if ($pageNum_Recordset1 <= $totalPages_Recordset1 && $pageNum_Recordset1 >= 0) {
		if ($pageNum_Recordset1 > ceil($max_links / 2)) {
			$fgp = $pageNum_Recordset1 - ceil($max_links / 2) > 0 ? $pageNum_Recordset1 - ceil($max_links / 2) : 1;
			$egp = $pageNum_Recordset1 + ceil($max_links / 2);
			if ($egp >= $totalPages_Recordset1) {
				$egp = $totalPages_Recordset1 + 1;
				$fgp = $totalPages_Recordset1 - ($max_links - 1) > 0 ? $totalPages_Recordset1 - ($max_links - 1) : 1;
			}
		} else {
			$fgp = 0;
			$egp = $totalPages_Recordset1 >= $max_links ? $max_links : $totalPages_Recordset1 + 1;
		}
		if ($totalPages_Recordset1 >= 1) {
			#	------------------------
			#	Searching for $_GET vars
			#	------------------------
			$allowedQuery = array();
			foreach (array('search_name', 'classid', 'level') as $name) {
				if (isset($_GET[$name]) && is_string($_GET[$name])) $allowedQuery[$name] = $_GET[$name];
			}
			$self = basename($_SERVER['PHP_SELF'] ?? 'productList.php');
			if ($self !== 'productList.php') $self = 'productList.php';
			$urlFor = static function (int $page) use ($allowedQuery, $sname, $self): string {
				$params = array_merge(array('pageNum_' . $sname => $page), $allowedQuery);
				return e($self . '?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986));
			};
			$successivo = $pageNum_Recordset1 + 1;
			$precedente = $pageNum_Recordset1 - 1;

			switch ($selmode) {
				case 1:
					$firstArray = ($pageNum_Recordset1 > 0) ? '<a href="' . $urlFor($precedente) . '">' . $prev_Recordset1 . '</a>' : "$prev_Recordset1";
					break;
				case 2:
					$firstArray = ($pageNum_Recordset1 > 0) ? '<li><a href="' . $urlFor($precedente) . '" aria-label="Previous"><span aria-hidden="true">' . $prev_Recordset1 . '</span></a></li>' : "<li class='disabled'><span aria-hidden='true'>$prev_Recordset1</span></li>";
					break;
				case 3:
					$firstArray = ($pageNum_Recordset1 > 0) ? '<li class="page-item"><a class="page-link" href="' . $urlFor($precedente) . '" aria-label="Previous"><span aria-hidden="true">' . $prev_Recordset1 . '</span></a></li>' : "<li class='page-item disabled'><span class='page-link' aria-label='Previous' aria-hidden='true'>$prev_Recordset1</span></li>";
					break;
			}
			# ----------------------
			# page numbers
			# ----------------------
			for ($a = $fgp + 1; $a <= $egp; $a++) {
				$theNext = $a - 1;
				if ($show_page) {
					$textLink = $a;
				} else {
					$min_l = (($a - 1) * $$gmaxRows) + 1;
					$max_l = ($a * $$gmaxRows >= $$gtotalRows) ? $$gtotalRows : ($a * $$gmaxRows);
					$textLink = "$min_l - $max_l";
				}
				$_ss_k = floor($theNext / 26);
				if ($theNext != $pageNum_Recordset1) {
					switch ($selmode) {
						case 1:
							$pagesArray .= '<a href="' . $urlFor($theNext) . '">';
							$pagesArray .= "$textLink</a>" . ($theNext < $egp - 1 ? $separator : "");
							break;
						case 2:
							$pagesArray .= '<li><a href="' . $urlFor($theNext) . '">';
							$pagesArray .= "$textLink</a></li>";
							break;
						case 3:
							$pagesArray .= '<li class="page-item"><a class="page-link" href="' . $urlFor($theNext) . '">';
							$pagesArray .= "$textLink</a></li>";
							break;
					}
				} else {
					switch ($selmode) {
						case 1:
							$pagesArray .= "$textLink" . ($theNext < $egp - 1 ? $separator : "");
							break;
						case 2:
							$pagesArray .= "<li class='active'>$textLink</li>";
							break;
						case 3:
							$pagesArray .= "<li class='page-item active' aria-current='page'><span class='page-link'>$textLink</span></li>";
							break;
					}
				}
			}
			$theNext = $pageNum_Recordset1 + 1;
			$offset_end = $totalPages_Recordset1;
			switch ($selmode) {
				case 1:
					$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? '<a href="' . $urlFor($successivo) . '">' . $next_Recordset1 . '</a>' : "$next_Recordset1";
					break;
				case 2:
					$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? '<li><a href="' . $urlFor($successivo) . '" aria-label="Next"><span aria-hidden="true">' . $next_Recordset1 . '</span></a></li>' : "<li class='disabled'><span aria-hidden='true'>$next_Recordset1</span></li>";
					break;
				case 3:
					$lastArray = ($pageNum_Recordset1 < $totalPages_Recordset1) ? '<li class="page-item"><a class="page-link" href="' . $urlFor($successivo) . '" aria-label="Next"><span aria-hidden="true">' . $next_Recordset1 . '</span></a></li>' : "<li class='page-item disabled'><span class='page-link' aria-label='Next' aria-hidden='true'>$next_Recordset1</span></li>";
					break;
			}
		}
	}
	return array($firstArray, $pagesArray, $lastArray);
}
