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
	body.user-edit-php .wrap { margin: 30px 0; padding: 25px 50px; background-color: #FFF; border-radius: 5px; box-shadow: 0 1px 4px rgb(18 25 97 / 8%); }
	body.user-edit-php .wrap hr.wp-header-end { border: none; border-top: 1px solid #EFEFEF; margin: 30px -50px; visibility: visible; }
    #wpbody { display: flex; gap: 40px; }
	#user-menu { margin-top: 60px; width: 300px; }
    #user-menu a { display: block; padding: 12px 20px; color: #555; text-decoration: none; border-radius: 5px; }
	#user-menu a:hover { background-color: rgba(255,255,255,.7); }
	#user-menu a.active { background-color: #e3e8ee; color: #333; }
	#user-menu a:focus { outline: 2px solid rgba(0,0,0,.5); }
    </style>
    <script>

	jQuery(document).ready(function($) {

	    // Create the nav element
	    var $menu = $( '<nav>', { id: 'user-menu' } );
	    $( '#wpbody' ).prepend( $menu );
	    var $form = $( '#your-profile' );

	    // Get the active section index from localStorage or use 0 as default
	    var storedIndex = localStorage.getItem( 'userEditSectionIndex' );
	    var activeSectionIndex = storedIndex !== null ? parseInt( storedIndex, 10 ) : 0;

	    // Initialize sections
	    var $sections = $form.find( 'h2, h3' ).map( function( index, elem ) {
	        var $header = $( elem );
	        var $parent = $header.parent();
	        var $section;

	        // Wrap the header and the following element in a div with class 'section'
	        if ( $parent.is( 'form' ) ) {
	            $section = $header.next().addBack().wrapAll( '<div class="section">' ).parent();
	        } else {
	            // If the header is inside a div, add the 'section' class to the div
	            $parent.addClass( 'section' );
	            $section = $parent;
	        }

	        // Hide the section except for the active one
	        if ( index !== activeSectionIndex ) {
	            $section.hide();
	        }

	        return $section;
	    });

	    // Create menu items
	    $sections.each( function( index, elem ) {
	        var $section = $( elem );
	        var menuItemText = $section.find( 'h2, h3' ).text();
	        var $menuItem = $( '<a>', { text: menuItemText, href: '#' } ).appendTo( $menu );

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

	});

    </script>
<?php
}
