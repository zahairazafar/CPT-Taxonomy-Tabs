/* --------------------- Demo Custom post type ------------------------------------*/

function templates_demo() {
	register_post_type('demo', array(
		'labels' => array(
			'name' => __('Demo'),
			'singular_name' => __('Demo')
		),
		'public' => true,
		'has_archive' => false,
		'menu_icon' => 'dashicons-forms',
		'rewrite' => array('slug' => 'demo'),
		'show_in_rest' => true,
		'supports' => array('title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
	));
}
add_action('init', 'templates_demo');

function templates_categories() {
	$labels = array(
		'name' => _x('Categories', 'taxonomy general name'),
		'singular_name' => _x('Category', 'taxonomy singular name'),
		'search_items' => __('Search Categories'),
		'all_items' => __('All Categories'),
		'parent_item' => __('Parent Category'),
		'parent_item_colon' => __('Parent Category:'),
		'edit_item' => __('Edit Category'),
		'update_item' => __('Update Category'),
		'add_new_item' => __('Add New Category'),
		'new_item_name' => __('New Category Name'),
		'menu_name' => __('Categories'),
	);

	register_taxonomy('categories', array('demo'), array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_in_rest' => true,
		'show_admin_column' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'categories'),
	));
}
add_action('init', 'templates_categories', 0);

// Shortcode to display Demo Templates ---------------------------------------------

function display_demotemp() {
	$demo_temp = '';
	// Get all categories for the taxonomy
	$categories = get_categories(array(
		'taxonomy'   => 'categories', 
		'hide_empty' => false,
	));
	if ($categories) {
		// Create tabs for each category
		$demo_temp .= '<div class="d-flex tabs">';
		$demo_temp .= '<ul id="tabs-nav">';

		// Add a link for all categories
		$demo_temp .= '<li><a href="#tab_all">All</a></li>';
		foreach ($categories as $category) {
			$demo_temp .= '<li><a href="#tab' . $category->term_id . '">' . $category->name . '</a></li>';
		}
		$demo_temp .= '</ul>';

		// Create tab content for each category
		$demo_temp .= '<div id="tabs-content">';
		$demo_temp .= '<div id="tab_all" class="tab-content">';
		// display all posts 
		$demo_temp .= get_demo_posts(null);
		$demo_temp .= '</div>';

		foreach ($categories as $category) {
			$demo_temp .= '<div id="tab' . $category->term_id . '" class="tab-content">';
			// display posts for the current category
			$demo_temp .= get_demo_posts($category->term_id);
			$demo_temp .= '</div>';
		}
		$demo_temp .= '</div>';
		$demo_temp .= '</div>';
	} else {
		$demo_temp .= 'No categories found.';
	}
	return $demo_temp;
}
add_shortcode('get_demotemps', 'display_demotemp');

// Function to get and display demo posts based on category ID
function get_demo_posts($category_id) {
	$args = array(
		'post_type'      => 'demo', 
		'posts_per_page' => -1,
		'tax_query'      => array(),
	);

	if ($category_id) {
		$args['tax_query'][] = array(
			'taxonomy' => 'categories', 
			'field'    => 'id',
			'terms'    => $category_id,
		);
	}
	$query = new WP_Query($args);
	$output = '';
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			// post content 
			$output .= '<div class="demo-tempcards">';
			$output .= '<a class="demo-thumbnails" href="' . get_permalink() . '">';
			$output .= '<div class="demo-thumbnailsinner">';
			/*	Here i am getting the images that i've enabled in the post by using ACF Image Field by Array & each is named as "hover_img_1 , 2 & 3 . So here i am using for loop */
			for ($i = 1; $i <= 3; $i++) {
				$image = get_field('hover_image_' . $i);
				if (!empty($image)) {
					$output .= '<img src="' . esc_url($image['url']) . '" />';
				}
			}
			$output .= '</div>';
			$output .= '</a>';
			$output .= '<a class="title-link" href="' . get_permalink() . '" ><p class="demo-temptitle">' . get_the_title() . '</p></a>';
			$output .= '</div>';
		}
		wp_reset_postdata();
	} else {
		$output .= 'No posts found.';
	}
	return $output;
}

