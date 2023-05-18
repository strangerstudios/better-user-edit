<?php
/*
* Plugin Name: Better User Edit
* Version:     0.1
* Description: A better display for the user edit screen
* Author:      Derek Ashauer
* Author URI:  https://wpsunshine.com
*/

add_action( 'edit_user_profile', 'bue_edit_user_profile', 9999 );
function bue_edit_user_profile() {
?>

    <style>
	body.user-edit-php { background-color: #f2f5fa; }
	/*
	body.user-edit-php .wrap { margin: 30px 0; padding: 25px 50px; background-color: #FFF; border-radius: 5px; box-shadow: 0 1px 4px rgb(18 25 97 / 8%); }
	body.user-edit-php .wrap hr.wp-header-end { border: none; border-top: 1px solid rgba(0,0,0,.1); margin: 30px 0; visibility: visible; }
	*/
    #your-profile { display: flex; gap: 40px; }
	#user-menu { flex-shrink: 0; margin-top: 40px; width: 300px; }
    #user-menu a { display: block; padding: 12px 40px 12px 20px; color: #555; text-decoration: none; border-radius: 5px; position: relative; }
	#user-menu a:hover { background-color: rgba(255,255,255,.7); }
	#user-menu a.active { background-color: #e3e8ee; color: #333; }
	#user-menu a:focus { outline: 2px solid rgba(0,0,0,.6); }
	#user-menu a.changed:after { content: ""; position: absolute; right: 15px; top: 50%; transform: translateY(-50%); width: 5px; height: 5px; border-radius: 50%; background: green; }
	#better-user-edit-fields { flex-grow: 10; margin: 30px 0; padding: 25px 50px; background-color: #FFF; border-radius: 5px; box-shadow: 0 1px 4px rgb(18 25 97 / 8%); }
    </style>
    <script>

	jQuery(document).ready(function($) {

	    // Create the nav element
	    var $menu = $( '<nav>', { id: 'user-menu' } );
	    var $form = $( '#your-profile' );
		$form.wrapInner('<div id="better-user-edit-fields"></div>');
		var $fields = $( '#better-user-edit-fields' );
		$form.prepend( $menu );

	    // Get the active section index from localStorage or use 0 as default
	    var storedIndex = localStorage.getItem( 'userEditSectionIndex' );
	    var activeSectionIndex = storedIndex !== null ? parseInt( storedIndex, 10 ) : 0;

	    // First pass. Add class to wrapping divs.
	    var $sections = $fields.find( 'h2, h3' ).map( function( index, elem ) {
	    	var $heading = $( elem );
			var $parent = $heading.parent();
	    	
			if ( ! $parent.is( '#better-user-edit-fields' ) ) {
				// If the heading is not a direct child of the form, add a class to the parent.
				$parent.addClass( 'section' );
			}
			
	    });

		// Second pass. Wrap headings that aren't already wrapped.
		$sections = $fields.find( 'h2, h3' ).map( function( index, elem ) {
	    	var $heading = $( elem );
			var $parent = $heading.parent();
	    	
			if ( $parent.is( '#better-user-edit-fields' ) ) {
				$heading.nextUntil( 'h2, h3, div.section, p.submit' ).addBack().wrapAll( '<div class="section" data-index="' + index + '"></div>' );
			} else {
			}
			
	    });

		// Reset sections to be the wrapping divs and hide them all.
		$sections = $( 'div.section' ).hide();
		
	    // Create menu items.
	    $sections.each( function( index, elem ) {
	        var $section = $( elem );
	        var menuItemText = $section.find( 'h2, h3' ).text();
	        var $menuItem = $( '<a>', { text: menuItemText, href: '#', 'data-index': index } ).appendTo( $menu );

	        // Click event for menu items
	        $menuItem.on( 'click', function(event) {
	            event.preventDefault();

	            // Show the clicked section and hide others
	            $( '.section' ).hide();
	            $section.show();

				// Set menu item to active
	            $( '#user-menu a' ).removeClass( 'active' );
	            $( this ).addClass( 'active' );

	            // Store the active section index in localStorage
	            localStorage.setItem( 'userEditSectionIndex', index );
	        });
	    });

	    // Show the active section and menu item
	    $sections.eq( activeSectionIndex ).show();
	    $menu.find( 'a' ).eq( activeSectionIndex ).addClass( 'active' );

		// Attach event handler to the document, delegate to form elements in sections
	    $(document).on( 'change keyup', '.section input, .section textarea, .section select', function() {

	        // Get the index of the parent section
	        var index = $(this).closest('.section').data('index');

	        // Add "notified" class to the corresponding menu item when a form element changes
	        $('#user-menu a[data-index="' + index + '"]').addClass('changed');
	    });

	});

    </script>
<?php
}
