	<div class="d-flex flex-wrap justify-content-center border-bottom">
		<?php
		$categories = custom_get_category_children('stage');
		$button_class = 'btn-light';
		echo '<a href="#" class="btn m-2 border ' . $button_class . '">전체단계</a>';
		foreach ($categories as $category) {
			if ($category) {
				$category_name = $category->name;
				$category_link = get_term_link($category);

				echo '<a href="' . $category_link . '" class="btn m-2 border ' . $button_class . '">' . $category_name . '</a>';
			}
		}
		?>
	</div>