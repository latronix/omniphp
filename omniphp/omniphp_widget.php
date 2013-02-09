<?php

class OmniPHP_Widget
{
	
	function login_form()
	{
		//
	}
	
	/**
	 * A full-blown, hardcoded file manager
	 * 
	 * Something akin to Windows Explorer, Midnight Commander
	 * Allow editing file permissions, changing, saving, etc.
	 * 
	 * To consider:
	 * - Can be an I/O methodology (advantage: faster, disadvantage: hard to search)
	 *   (also note that I could probably perform a system-wide search through PHP and it will probably be way faster than
	 *   DB-driven ones).
	 * - Can be DB-driven (advantage: easy to search and maintain, disadvantage: might be super-slow for large amount of files)
	 *   (though DB-driven is just for creating 'indexes' of the files, not for actually storing any binary data).
	 * (ALLOW BOTH: user chooses)
	 * 
	 * Allow layout/template modification
	 * 
	 * Displayable in user-prefered format (i.e. detailed list, thumbnails, etc.), store the
	 * prefered format in cookies.
	 */
	function file_manager()
	{
		//
	}
	
	
	function gallery() {}
	function media_player() {}
	function text_editor() {} //based on TinyMCE? and maybe others?
	
	//note: the uploader and downloader can be integrated into the file_manager too or as standalones?
	//or just make the file_manager either a FM, Uploader, or Downloader only?
	function file_uploader() //based on Plupload
	{
		//
	}
	function file_import() //same as file_uploader
	{
		$this->file_uploader();
	}
	
	function file_downloader()
	{
		//
	}
	function file_export()
	{
		$this->file_downloader();
	}
	
	/*
	function charts() // or report_charts()
	//can use a number of plugins here, importantly I should use a truly FOSS plugin not
	//proprietary nor dual-licensed (non available free for commercial use) projects.
	*/
	
	
	
	//same as crud...
	/*function pagination_table()
	{
		//
	}*/
	
	/**
	 * [PROOF - OF - CONCEPT]
	 * (DESIGN IDEAS... for testing only)
	 * 
	 * options for: select-only pagination table
	 * configurable for all CRUD functionality.
	 * 
	 * Full customizable layout (table width/height, column width, row height, hover/odd/even colors,
	 * table color, font, 
	 * also allow adding classes to rows (tr), columns (td), table, thead, tfoot?, tbody, th, etc.
	 * 
	 * Allow customizable content: display images, text, HTML5 content, dynamic content, etc.
	 * Not just rows and columns like a classic table.
	 * 
	 * Allow INSERT/UPDATE/DELETE to be either inline or through a colorbox form.
	 * if inline: allow update/delete for multiple rows.
	 * 
	 * Basic and Advanced Searches (fully customizable).
	 * 
	 * Sortable Table through any of the columns, the sorting should fully integrate
	 * with the pagination.
	 * 
	 * Other options for 'non-pagination':
	 * 	- Show only defined number of rows (i.e. 100) and pagination only for searches?
	 *  - Show as a continual list? (i.e. endless pagination, load results upon scrolling down and have implicit "show results" button
	 *    see Google images for sample.
	 *  
	 * Maybe use HTML-like intuitive properties:
	 * crud(array("name" => "CRUD1", "pagination" => true, "colorbox" => true, "row_height" => "20px"));
	 * 
	 * defining crud functionality:
	 * crud(array("crud_delete" => false, "crud_update" => false)); //all crud will be true by default, to disable use like this?
	 */
	function crud()
	{
		//
	}
}

?>
