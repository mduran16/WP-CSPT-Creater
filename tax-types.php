<?php

//////////////////////////////////////////////////////////////////////
// Started From the Bottom: a Self Help Guide to Beginning WP Files //
//////////////////////////////////////////////////////////////////////

add_action('save_post','cspt_update_tax');
add_action('init','init_taxonomies');
add_action('add_meta_boxes','cspt_tax_meta_boxes');

/**
 * Adds the central meta box that contains all the inputs
**/
function cspt_tax_meta_boxes()
{
	add_meta_box('tax_settings_field','Settings','tax_main_settings_fields','cspt_tax','normal');
}


/**
 * This function is extremely identical to its custom post counterpart. The difference are 
 * very minute. Of course the $options contains a different set of values. Also, register_Taxonomy is
 * called instead of the one for registering post types.
**/
function init_taxonomies()
{
	global $post;
	$options = [
	'taxonomy','object_type','name','singular_name','menu_name',
	'all_items','edit_items','view_item','update_item','add_new_item',
	'new_item_name','parent_item','parent_item_colon','search_items',
	'popular_items','separate_items_with_commas','add_or_remove_items',
	'choose_from_most_used','not_found','public','show_ui','show_in_nav_menus',
	'show_tagcloud','show_admin_column','hierarchical','update_count_callback',
	'rewrite','manage_terms','edit_terms','delete_terms','assign_terms','sort'
	];

	$labels = array(
        'name' => 'cspt_tax', 'taxonomy type general name',
        'singular_name' => 'cspt_tax', 'taxonomy type singular name',
        'add_new' => 'Add New custom taxonomy', 'cspt_tax',
        'add_new_item' =>'Add New custom taxonomy',
        'edit_item' => 'Edit custom taxonomy',
        'new_item' => 'New custom taxonomy',
        'all_items' => 'All custom taxonomies',
        'view_item' => 'View custom taxonomy',
        'search_items' => 'Search custom taxonomy',
        'not_found' => 'No custom taxonomy found',
        'not_found_in_trash' => 'No custom taxonomy found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Custom Taxonomy'
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
	register_post_type('cspt_tax', $args);


	$query = new WP_Query(array('post_type' => array('cspt_tax')));
    while ($query->have_posts()) : $query->the_post();
		global $post;

		$tax_meta= [];

		foreach($options as $option){
			if(get_post_meta($post->ID,$option,true) == 'on')
				$tax_meta[$option] = true;
			elseif(get_post_meta($post->ID,$option,true) == '')
				$tax_meta[$option] = false;
			else $tax_meta[$option] = get_post_meta($post->ID,$option,true);

		}

		$labels = array(
			'name' => get_the_title($post->ID), 'taxonomy general name',
	        'singular_name' => $tax_meta['singular_name'], 'taxonomy singular name',
	        'add_new' => 'Add new ' . $tax_meta['add_new'], get_the_title($post->ID),
	        'add_new_item' =>$tax_meta['add_new_item'],
	        'edit_item' => $tax_meta['edit_item'],
	        'new_item' => $tax_meta['new_item'],
	        'all_items' => 'View all ' . $post_meta['all_items'],
	        'view_item' => $tax_meta['view_item'],
	        'search_items' => $tax_meta['search_items'],
	        'not_found' => $tax_meta['not_found'],
	        'not_found_in_trash' => $tax_meta['not_found_in_trash'],
	        'parent_item_colon' => $tax_meta['parent_item_colon'],
	        'menu_name' => get_the_title($post->ID)	
		);

		$args = array(
	        'labels' => $labels,
	        'public' => $tax_meta['public'],
	        'publicly_queryable' => $tax_meta['publicly_queryable'],
	        'show_ui' => $tax_meta['show_ui'],
	        'show_in_menu' => $tax_meta['show_in_menu'],
	        'rewrite' => $tax_meta['rewrite'],
	        'has_archive' => $tax_meta['has_archive'],
	        'hierarchical' => $tax_meta['hierarchical'],
	        'menu_position' => $tax_meta['menu_position'],
		);
		register_taxonomy(get_the_title($post->ID),$tax_meta['object_type'],$args);
	endwhile;
}

////////////////////////////
// Reduce, Reuse, Recycle //
////////////////////////////

/**
 * This function is again almost identical to its counterpart. Read post-types-alt.php for better docs 
**/
function cspt_update_tax()
{
	global $post;
	//All of our settings
	$options = [
	'taxonomy','object_type','name','singular_name','menu_name',
	'all_items','edit_items','view_item','update_item','add_new_item',
	'new_item_name','parent_item','parent_item_colon','search_items',
	'popular_items','separate_items_with_commas','add_or_remove_items',
	'choose_from_most_used','not_found','public','show_ui','show_in_nav_menus',
	'show_tagcloud','show_admin_column','hierarchical','update_count_callback',
	'rewrite','manage_terms','edit_terms','delete_terms','assign_terms','sort'
	];
	//if posted was set, then that means that a post type was either created or update
	//gets inputs and updates meta accordingly.
	if(isset($_POST['posted']))
	{
		foreach($options as $option)
		{
			update_post_meta($post->ID,$option, $_POST[$option], get_post_meta($post->ID,$option,true));
		}
	}
}


/////////////////////////////////////////////////////////////////
// The Actual Value of Your 2 Cents: Why No One Will Ever Care //
/////////////////////////////////////////////////////////////////
/**
 * This function takes outputs all input fields as well as populating them with data if 
 * the taxonomy is being edited.
**/
function tax_main_settings_fields()
{
	global $post;
	$options = [
	'taxonomy','object_type','name','singular_name','menu_name',
	'all_items','edit_items','view_item','update_item','add_new_item',
	'new_item_name','parent_item','parent_item_colon','search_items',
	'popular_items','separate_items_with_commas','add_or_remove_items',
	'choose_from_most_used','not_found','public','show_ui','show_in_nav_menus',
	'show_tagcloud','show_admin_column','hierarchical','update_count_callback',
	'rewrite','manage_terms','edit_terms','delete_terms','assign_terms','sort'
	];
	$tax_meta = [];
	foreach($options as $option) $tax_meta[$option] = get_post_meta($post->ID,$option,true);

	$post_types = get_post_types('','names');
	?>
	<input hidden name=posted >
	<input name=taxonomy placeholder=taxonomy value="<?php echo $tax_meta['taxonomy']  ?>">
	<p></p>
	<select name=object_type>
	<?php
		foreach($post_types as $post_type)
		{
			if($post_type != "cspt" && $post_type != "cspt_tax" && $post_type != "nav_menu_item")
			{
			$string = "<option name='". $post_type."' ";
			if($post_type == $tax_meta['object_type']) $string .= "selected";
			$string .= ">".$post_type."</option>";
			echo $string;
			}
		}
	?>
	</select>
	<p></p>
	<input name=name placeholder=Name value="<?php echo $tax_meta['name']  ?>"e><br/>
	<input name=singluar_name placeholder='Singular Name' value="<?php echo $tax_meta['singluar_name']  ?>"><br/>
	<input name=menu_name placeholder='Menu Name' value="<?php echo $tax_meta['menu_name']  ?>"><br/>
	<input name=all_items placeholder='All Items' value="<?php echo $tax_meta['all_items']  ?>"><br/>
	<input name=edit_items placeholder="Edit Item" value="<?php echo $tax_meta['edit_items']  ?>"><br/>
	<input name=view_item placeholder='View Item' value="<?php echo $tax_meta['view_item']  ?>"><br/>
	<input name=update_item placeholder='Update Item' value="<?php echo $tax_meta['update_item']  ?>"><br/>
	<input name=add_new_item placeholder='Add New Item' value="<?php echo $tax_meta['add_new_item']  ?>"><br/>
	<input name=new_item_name placeholder='New Item Name' value="<?php echo $tax_meta['new_item_name']  ?>"><br/>
	<input name=parent_item placeholder='Parent Item' value="<?php echo $tax_meta['parent_item']  ?>"><br/>
	<input name=parent_item_colon placeholder='Parent Item Colon' value="<?php echo $tax_meta['parent_item_colon']  ?>"><br/>
	<input name=search_items placeholder='Search Items' value="<?php echo $tax_meta['search_items']  ?>"><br/>
	<input name=popular_items placeholder="Popular Items" value="<?php echo $tax_meta['popular_items']  ?>"><br/>
	<input name=separate_items_with_commas placeholder="Separate Items With Commas Text"
		value="<?php echo $tax_meta['separate_items_with_commas']  ?>"><br/>
	<input name=add_or_remove_items placeholder='Add or Remove Items'
		value="<?php echo $tax_meta['add_or_remove_items']  ?>"><br/>
	<input name=choose_from_most_used placeholder='Choose From Most Used'
		value="<?php echo $tax_meta['choose_from_most_used']  ?>"><br/>
	<input name=not_found placeholder='Not Found'>
	<p></p>
	<input type=checkbox name='public' <?php if($tax_meta['public'] == true) echo "checked"; ?>>Public<br/>
	<input type=checkbox name='show_ui' <?php if($tax_meta['show_ui'] == true) echo "checked"; ?>>Show UI<br/>
	<input type=checkbox name='show_in_nav_menus' <?php if($tax_meta['show_in_nav_menus'] == true) echo "checked"; ?>>Show In Nav Menus<br/>
	<input type=checkbox name='show_tagcloud' <?php if($tax_meta['show_tagcloud'] == true) echo "checked"; ?>>Show Tagcloud<br/>
	<input type=checkbox name='show_admin_column' <?php if($tax_meta['show_admin_column'] == true) echo "checked"; ?>>Show Admin Column<br/>
	<input type=checkbox name='hierarchical' <?php if($tax_meta['hierarchical'] == true) echo "checked"; ?>>Hierarchical<br/>
	<input type=checkbox name='update_count_callback' <?php if($tax_meta['update_count_callback'] == true) echo "checked"; ?>>Update Count Callback<br/>
	<input type=checkbox name'rewrite' <?php if($tax_meta['rewrite'] == true) echo "checked"; ?>>Rewrite
	<p></p>
	<input type=checkbox name='manage_terms' <?php if($tax_meta['manage_terms'] == true) echo "checked"; ?>>Manage Terms<br/>
	<input type=checkbox name='edit_terms' <?php if($tax_meta['edit_terms'] == true) echo "checked"; ?>>Edit Terms<br/>
	<input type=checkbox name='delete_terms' <?php if($tax_meta['delete_terms'] == true) echo "checked"; ?>>Delete Terms<br/>
	<input type=checkbox name='assign_terms' <?php if($tax_meta['assign_terms'] == true) echo "checked"; ?>>Assign Terms<br/>
	<p></p>
	<input type=checkbox name=sort>Sort<br/>
	
	<?php
}

?>