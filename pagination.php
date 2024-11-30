<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Bootstrap Styled Pagination
function custom_pagination1($pages = '', $range = 5) {
    $showitems = ($range * 2) + 1; // Total number of page links to show
    global $paged;

    if (empty($paged)) {
        $paged = 1;
    }

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;

        if (!$pages) {
            $pages = 1;
        }
    }

    if (1 != $pages) {
        echo '<nav class="pt-2" aria-label="Page navigation" role="navigation">';
        echo '<ul class="pagination justify-content-center">';

        // Display current page info
        echo '<li class="page-item disabled d-none d-md-block"><span class="page-link">' . $paged . ' / ' . $pages . '</span></li>';

        // First page link
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) {
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link(1) . '" aria-label="First Page">&laquo;</a></li>';
        }

        // Previous page link
        if ($paged > 1 && $showitems < $pages) {
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged - 1) . '" aria-label="Previous Page">&lsaquo;</a></li>';
        }

        // Page numbers
        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                echo ($paged == $i)
                    ? '<li class="page-item active"><span class="page-link">' . $i . '</span></li>'
                    : '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($i) . '">' . $i . '</a></li>';
            }
        }

        // Next page link
        if ($paged < $pages && $showitems < $pages) {
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($paged + 1) . '" aria-label="Next Page">&rsaquo;</a></li>';
        }

        // Last page link
        if ($paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages) {
            echo '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($pages) . '" aria-label="Last Page">&raquo;</a></li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
}

// Call the pagination function
custom_pagination1();
?>
