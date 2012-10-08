<?php
class mf_menu
{
	public function unique_post_type(){
		global $submenu,$menu,$mf_pt_unique,$wpdb;

		if (count($mf_pt_unique)) {
			foreach ($submenu as $key => $value) {
				if (in_array($key, $mf_pt_unique)) {
					unset($submenu[$key][5]);

					//exist element crated for this post type?
					$name = preg_replace('/edit.php\?post_type\=/', '', $key);
					$has_posts = get_posts( array( 'post_type' => $name, ) );
					if ($has_posts) {
						//replace url and text
						$submenu[$key][10][0] = preg_replace('/Add/', 'Edit', $submenu[$key][10][0]);
						$submenu[$key][10][2] = "post.php?post=".$has_posts[0]->ID."&action=edit";
					}
				}
				
			}
		}
	}

}