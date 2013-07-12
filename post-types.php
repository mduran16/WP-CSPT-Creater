<?php

/////////////////
// Maintenance //
/////////////////

//add the action to save the post type
add_action('save_post','cspt_update_post_type');
//add the main meta box function. This function will handle all other meta boxes
add_action('add_meta_boxes','cspt_meta_boxes');
//add init action so we can register the post
add_action('init','cspt_init');



/**
 * this function will handle all other metaboxes. Create metabox in seperate function and then
 * add it here.
**/
function cspt_meta_boxes(){
add_meta_box('settings_field','SETTIngs','main_setting_fields','cspt','normal');
}



///////////////////////////////////////////
// THE MEAT: A Story About Functionality //
///////////////////////////////////////////



/**
 * This is our initialize function. This creates the main CPT post type so we can get it on the menu.
 * whats created can then be used to handle the creation of other post types. This function will create this item
 * as well as newer post items.
**/
function cspt_init(){
	$labels = array(
        'name' => 'cspt', 'post type general name',
        'singular_name' => 'cspt', 'post type singular name',
        'add_new' => 'Add New custom post type', 'cspt',
        'add_new_item' =>'Add New Post type',
        'edit_item' => 'Edit custom post type',
        'new_item' => 'New custom post type',
        'all_items' => 'All custom post type',
        'view_item' => 'View custom post type',
        'search_items' => 'Search custom post type',
        'not_found' => 'No custom post type found',
        'not_found_in_trash' => 'No custom post type found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Custom Post Types'
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 100, //bottom of the menu
        'supports' => array('title')
    );
	//now we register it and put it on the menu
	register_post_type('cspt', $args);
	//possibly creating options as a global in scotland.php could prevent repetition
	$options = array(
		"name", "singular_name", "menu_name", "all_items",
		"add_new", "add_new_item", "edit_item", "new_item",
		"view_item", "search_item", "not_found", "not_found_in_trash",
		"parent_item_colon", "menu_icon", "description", "public", "exclude_from_search",
		"publicly_queryable", "show_ui", "show_in_nav_menus", "show_in_admin_bar",
		"menu_position", "capability_types", "map_meta_cap", "hierarchical",
		"title", "editor", "author", "thumbnail", "excerpt",
		"trackbacks", "custom_fields", "comments","revisions",
		"page_attributes", "post_formats", "has_archive", "can_export"
	);
		
	$query = new WP_Query(array('post_type' => array('cspt')));
    while ($query->have_posts()) : $query->the_post();
		global $post;

		$post_meta= array();
		
		foreach($options as $option){
			//if post meta = on then it was a checked checkbox
			if(get_post_meta($post->ID,$option,true) == 'on')
				$post_meta[$option] = true;
			//if its empty then it either was an empty string or a unchecked box
			elseif(get_post_meta($post->ID,$option,true) == '')
				$post_meta[$option] = false;
			//it was anything else
			else $post_meta[$option] = get_post_meta($post->ID,$option,true);
			
		}
		//populate the labels array with the appropriate meta data
		$labels = array(
			'name' => $post_meta['name'], 'post type general name',
	        'singular_name' => $post_meta['singular_name'], 'post type singular name',
	        'add_new' => $post_meta['add_new'], get_the_title($post->ID),
	        'add_new_item' => $post_meta['add_new_item'],
	        'edit_item' => $post_meta['edit_item'],
	        'new_item' => $post_meta['new_item'],
	        'all_items' => $post_meta['all_items'],
	        'view_item' => $post_meta['view_item'],
	        'search_items' => $post_meta['search_items'],
	        'not_found' => $post_meta['not_found'],
	        'not_found_in_trash' => $post_meta['not_found_in_trash'],
	        'parent_item_colon' => $post_meta['parent_item_colon'],
	        'menu_name' => $post_meta['menu_name']	
		);
		//and now we make the post and register it. too easy
		$args = array(
	        'labels' => $labels,
	        'public' => $post_meta['public'],
	        'publicly_queryable' => $post_meta['publicly_queryable'],
	        'show_ui' => $post_meta['show_ui'],
	        'show_in_menu' => $post_meta['show_in_menu'],
	        'rewrite' => $post_meta['rewrite'],
	        'has_archive' => $post_meta['has_archive'],
	        'hierarchical' => $post_meta['hierarchical'],
	        'menu_position' => (int)$post_meta['menu_position'],
		);
		register_post_type(get_the_title($post->ID), $args);
	endwhile;
		
}



/**
 * This function will handle the creation of the post. It takes all inputs from metaboxes around the page, tosses them
 * into arrays, and creates post types accordingly.
**/
function cspt_update_post_type(){
	global $post;
	//All of our settings
	$options = array(
		"name", "singular_name", "menu_name", "all_items",
		"add_new", "add_new_item", "edit_item", "new_item",
		"view_item", "search_item", "not_found", "not_found_in_trash",
		"parent_item_colon", "menu_icon", "description", "public", "exclude_from_search",
		"publicly_queryable", "show_ui", "show_in_nav_menus", "show_in_admin_bar",
		"menu_position", "capability_types", "map_meta_cap", "hierarchical",
		"title", "editor", "author", "thumbnail", "excerpt",
		"trackbacks", "custom_fields", "comments","revisions",
		"page_attributes", "post_formats", "has_archive", "can_export"
	);
	//if posted was set, then that means that a post type was either created or update
	//gets inputs and updates meta accordingly.
	if(isset($_POST['posted'])){
		foreach($options as $option)
		{
			update_post_meta($post->ID,$option, $_POST[$option], get_post_meta($post->ID,$option,true));
		}
	}
} 



//////////////////////////////////////////////////////////
// Judged by the Cover: Displays, Aesthetics, and More! //
//////////////////////////////////////////////////////////

/**
 * this is our main settings field. This is called by the chief metabox function. So far its just straight HTML but 
 * the goal is to have it handle all inputs and pass them along to create the new post types.
**/
function main_setting_fields(){
	global $post;
	$options = array(
		"name", "singular_name", "menu_name", "all_items",
		"add_new", "add_new_item", "edit_item", "new_item",
		"view_item", "search_item", "not_found", "not_found_in_trash",
		"parent_item_colon", "menu_icon", "description", "public", "exclude_from_search",
		"publicly_queryable", "show_ui", "show_in_nav_menus", "show_in_admin_bar",
		"menu_position", "capability_types", "map_meta_cap", "hierarchical",
		"title", "editor", "author", "thumbnail", "excerpt",
		"trackbacks", "custom_fields", "comments","revisions",
		"page_attributes", "post_formats", "has_archive", "can_export"
	);
	$post_meta = array();
	//populate post meta array so we can prefill fields with any values they might need. 
	//If I was to make a 2d array that had type as well as name I could probably do all the fields in a loop.
	//#yolo
	foreach($options as $option) $post_meta[$option] = get_post_meta($post->ID,$option, true);
	//Beware Ahead: We are now leaving the world of PHP
	?>
	<!-- Welcome to HTML -->
	<input hidden name=posted>
	<p>
		<input name=name placeholder="Name" value="<?php echo $post_meta['name']  ?>">
		<input name=singular_name placeholder="Singular Name" value="<?php echo $post_meta['singular_name']  ?>">
		<input name=menu_name placeholder="Menu Name" value="<?php echo $post_meta['menu_name']  ?>">
		<input name=all_items placeholder="All Items" value="<?php echo $post_meta['all_items']  ?>">
		<input name=add_new placeholder ="Add New" value="<?php echo $post_meta['add_new']  ?>">
		<input name=add_new_item placeholder="Add New Item" value="<?php echo $post_meta['add_new_item']  ?>">
		<input name=edit_item placeholder="Edit Item" value="<?php echo $post_meta['edit_item']  ?>">
		<input name=new_item placeholder="New Item" value="<?php echo $post_meta['new_item']  ?>">
		<input name=view_item placeholder="View Item" value="<?php echo $post_meta['view_item']  ?>">
		<input name=search_item placeholder="Search Items" value="<?php echo $post_meta['search_item']  ?>">
		<input name=not_found placeholder="Not Found" value="<?php echo $post_meta['not_found']  ?>">
		<input name=not_found_in_trash placeholder="Not Found In Trash" value="<?php echo $post_meta['not_found_in_trash']  ?>">
		<input name=parent_item_colon placeholder="Parent Item Colon" value="<?php echo $post_meta['parent_item_colon']  ?>">
		<input name=menu_icon placeholder="Menu Icon (URL)" value="<?php echo $post_meta['menu_icon']  ?>">
	</p>
	<p>
		<textarea name=description placeholder="Description..." value="<?php echo $post_meta['description']  ?>"></textarea>
	</p>
	<p>
		<input type=checkbox name=public <?php if($post_meta['public'] == true) echo "checked"; ?>>Public<br/>
		<input type=checkbox name=exclude_from_search <?php if($post_meta['exclude_from_search'] == true) echo "checked"; ?>>Exclude From Search<br/>
		<input type=checkbox name=publicly_queryable <?php if($post_meta['publicly_queryable'] == true) echo "checked"; ?>>Publicly Query-able<br/>
		<input type=checkbox name=show_ui <?php if($post_meta['show_ui'] == true) echo "checked"; ?>>Show UI<br/><br/>
		<input type=checkbox name=show_in_nav_menus <?php if($post_meta['show_in_nav_menus'] == true) echo "checked"; ?>>Show in Nav Menus<br/>
		<input type=checkbox name=show_in_admin_bar <?php if($post_meta['show_in_admin_bar'] == true) echo "checked"; ?>>Show In Admin Bar
	</p>
	<p><select name=menu_position>
		<option value=5 <?php if($post_meta['menu_position'] == '5') echo "selected"; ?>>5</option>
		<option value=10 <?php if($post_meta['menu_position'] == '10') echo "selected"; ?>>10</option>
		<option value=15 <?php if($post_meta['menu_position'] == '15') echo "selected"; ?>>15</option>
		<option value=20 <?php if($post_meta['menu_position'] == '20') echo "selected"; ?>>20</option>
		<option value=25 <?php if($post_meta['menu_position'] == '25') echo "selected"; ?>>25</option>
		<option value=60 <?php if($post_meta['menu_position'] == '60') echo "selected"; ?>>60</option>
		<option value=65 <?php if($post_meta['menu_position'] == '65') echo "selected"; ?>>65</option>
		<option value=70 <?php if($post_meta['menu_position'] == '70') echo "selected"; ?>>70</option>
		<option value=75 <?php if($post_meta['menu_position'] == '75') echo "selected"; ?>>75</option>
		<option value=80 <?php if($post_meta['menu_position'] == '80') echo "selected"; ?>>80</option>
		<option value=100 <?php if($post_meta['menu_position'] == '100') echo "selected"; ?>>100</option>
	</select></p>
	
	<p><select name=capability_types>
		<option value=post <?php if($post_meta['capability_types'] == 'post') echo "selected"; ?>>Post</option>
		<option value=page <?php if($post_meta['capability_types'] == 'page') echo "selected"; ?>>Page</option>
	</select></p>
	<p>
		<input type=checkbox name=map_meta_cap <?php if($post_meta['map_meta_cap'] == true) echo "checked"; ?>>Map Meta Box<br/>
		<input type=checkbox name=hierarchical <?php if($post_meta['hierarchical'] == true) echo "checked"; ?>>Hierarchical<br/>
	</p>
	<p>
		<input type=checkbox name=title <?php if($post_meta['title'] == true) echo "checked"; ?>>Title<br/>
		<input type=checkbox name=Editor <?php if($post_meta['editor'] == true) echo "checked"; ?>>Editor<br/>
		<input type=checkbox name=author <?php if($post_meta['author'] == true) echo "checked"; ?>>Author<br/>
		<input type=checkbox name=thumbnail <?php if($post_meta['thumbnail'] == true) echo "checked"; ?>>Thumbnail<br/>
		<input type=checkbox name=excerpt <?php if($post_meta['excerpt'] == true) echo "checked"; ?>>Excerpt<br/>
		<input type=checkbox name=trackbacks <?php if($post_meta['trackbacks'] == true) echo "checked"; ?>>Trackbacks<br/>
		<input type=checkbox name=custom_fields <?php if($post_meta['custom_fields'] == true) echo "checked"; ?>>Custom Fields<br/>
		<input type=checkbox name=comments <?php if($post_meta['comments'] == true) echo "checked"; ?>>Comments<br/>
		<input type=checkbox name=revisions <?php if($post_meta['revisions'] == true) echo "checked"; ?>>Revisions<br/>
		<input type=checkbox name=page_attributes <?php if($post_meta['page_attributes'] == true) echo "checked"; ?>>Page Attributes<br/>
		<input type=checkbox name=post_formats <?php if($post_meta['post_formats'] == true) echo "checked"; ?>>Post Formats
	</p>
	<p>
		<input type=checkbox name=has_archive <?php if($post_meta['has_archive'] == true) echo "checked"; ?>>Has Archive<br/>
		<input type=checkbox name=can_export <?php if($post_meta['can_export'] == true) echo "checked"; ?>>Can Export
	</p>
	<!-- Thank you, Come Again -->
	<?php
	//Welcome to PHP
}


?>