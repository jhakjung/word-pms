<?php
// Bootstrap4 Styled Pagination
function custom_pagination($pages = '', $range = 5) {
	$showitems = ($range * 2) + 1;
	global $paged;
	if(empty($paged)) $paged = 1;
	if($pages == '')
	{
		global $wp_query;
		$pages = $wp_query->max_num_pages;

		if(!$pages)
			$pages = 1;
	}

	if(1 != $pages)
	{
	    echo '<nav class="pt-2" aria-label="Page navigation" role="navigation">';
        // echo '<span class="sr-only">Page navigation</span>';
        echo '<ul class="pagination justify-content-center ft-wpbs">';

        // echo '<li class="page-item disabled d-none d-lg-block"><span class="page-link">' . sprintf(__('%d 의 %d 페이지', 'bestmedical'), $paged, $pages) . '</span></li>';

        echo '<li class="page-item disabled d-none d-md-block"><span class="page-link">' . $paged . ' 의 ' . $pages . ' 페이지</span></li>';


	 	if($paged > 2 && $paged > $range+1 && $showitems < $pages)
			echo '<li class="page-item"><a class="page-link" href="'.get_pagenum_link(1).'" aria-label="First Page">&laquo;<span class="d-none d-md-block"></span></a></li>';

	 	if($paged > 1 && $showitems < $pages)
			echo '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($paged - 1).'" aria-label="Previous Page">&lsaquo;<span class="d-none d-md-block"></span></a></li>';

		for ($i=1; $i <= $pages; $i++)
		{
		    if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
				echo ($paged == $i)? '<li class="page-item active"><span class="page-link"><span class="sr-only">Current Page </span>'.$i.'</span></li>' : '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($i).'"><span class="sr-only">Page </span>'.$i.'</a></li>';
		}

		if ($paged < $pages && $showitems < $pages)
			echo '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($paged + 1).'" aria-label="Next Page"><span class="d-none d-md-block"></span>&rsaquo;</a></li>';

	 	if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages)
			echo '<li class="page-item"><a class="page-link" href="'.get_pagenum_link($pages).'" aria-label="Last Page"><span class="d-none d-md-block"></span>&raquo;</a></li>';

	 	echo '</ul>';
        echo '</nav>';
	}
} ?>