<div class="container-fluid">
	<div class="d-flex flex-wrap justify-content-center">
		<?php
		$categories = get_document_category_children();
		foreach ($categories as $category) {
			if ($category) {
				$category_name = $category->name;
				$category_link = get_term_link($category);
				$button_class = 'btn-light';

				echo '<a href="' . $category_link . '" class="btn m-2 border ' . $button_class . '">' . $category_name . '</a>';
			}
		}
		?>
	</div>
</div>