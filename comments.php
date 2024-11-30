<?php
if(post_password_required()) {
  	return;
}
?>
<div class="comments">
	<div class="row">
		<div class="w-100 mx-auto p-1">
			<p class="fs-3 text-center text-primary"><i class="fa fa-comments"></i></p>

		<!-- <br> -->

		<!-- Comments List: inc폴더의 comment-template.php로 이동 -->
		<!-- Comments Pagination -->
		<?php
		wp_list_comments('type=comment&callback=bestmedical_comments_list');
		// wp_list_comments('type=pings&callback=bestmedical_comments_list'); // If ping & trackback is allowed
		bestmedical_comments_pagination();
		?>
		<!-- Comments Form -->
		<div class="media"> <!-- media-body 클래스부터는 template 파일 -->
			<div class="media-body">
				<?php comment_form(bestmedical_comments_template()); ?>
				<?php if(!comments_open() && get_comments_number()) {
					if(is_single()) { ?>
				<h4 class="no-comments text-center">
					코멘트가 없습니다.
				</h4>
				<?php }
				} ?>
			</div>
		</div>
	</div>
	<br>
	</div>
</div>