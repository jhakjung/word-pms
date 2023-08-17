<?php get_header(); ?>
<div class="site-main container">

<?php
while(have_posts()) {
    the_post();
    $slug = get_post_field('post_name', get_the_ID()); // 슬러그
    ?>

    <div class="post-title">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        <span class="post-title__slug"><?php echo '#' .$slug; ?></span>
    </div>
    <div class="post-meta d-flex my-2">
        <span class="meta">분류 <span class="badge badge__dark"><?php echo get_the_category_list(',');?></span>
        <span>
        <span class="meta">단계 <span class="badge badge__blue"><?php custom_get_the_tax_meta('project_state');?></span>
        </span>
        <span class="meta">공종 <span class="badge badge__green"><?php custom_get_the_tax_meta('system_type');?></span></span>
        <span class="meta">키워드 <span class="badge bg_warning"><?php custom_get_the_tag_meta();?></span></span>
        </span>
        <span class="meta">이슈</span>
        <span class="meta">작성</span>
        <span class="meta">일시</span>
    </div>

    <?php the_content(); ?>
<?php } ?>

</div>
</div>

<?php get_footer(); ?>