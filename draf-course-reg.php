<?php
/*
Plugin Name: Form tambah course Plugin
Plugin URI: http://drafshare.blogspot.co.id/
Description: Simple non-bloated WordPress Form
Version: 1.0
Author: Dirham
Author URI: https://www.facebook.com/draf.dirham
*/

// awal bagian pembuatan form untuk melakukan registrasi course baru
add_action('admin_menu','draf_form_create');//test add to admin menu

function draf_form_create() {

	//this is the main item for the menu
	add_menu_page('Course', //page title
	'Draf Course', //menu title
	'read', //capabilities
	'draf_form', //menu slug
	'call' //function
	);

}

function randkey($length){
	//    karakter yang bisa dipakai sebagai key
    $string = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($string);
    
	//    mengenerate key
    for($i=1;$i<=$length; $i++){
        $start = rand(0, $len);
        $key .= substr($string, $start, 1);
    }
    
    return $key;
}

function form_daftar_course() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<p>';
	echo 'Instructor Name : (required) <br/>';
	echo '<input type="text" name="name" value="' . ( isset( $_POST["name"] ) ? esc_attr( $_POST["name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Email of student : (required) <br/>';
	echo '<input type="text" name="email" value="' . ( isset( $_POST["email"] ) ? esc_attr( $_POST["email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Select Level :';
	echo ' <select name="level">
				  <option value="AILA1">Dasar</option>
				  <option value="AILA2">Menengah</option>
				  <option value="AILA3">Mahir</option>
				  <option value="AILA4">Instructor</option>
				</select>';
	echo '</p>';
	echo '<p>';
	echo 'Subject (required) <br/>';
	echo '<input type="text" name="subject" pattern="[a-zA-Z ]+" value="' . ( isset( $_POST["subject"] ) ? esc_attr( $_POST["subject"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Enter your message (details for student) <br/>';
	echo '<textarea rows="10" cols="35" name="cf-message">' . ( isset( $_POST["message"] ) ? esc_attr( $_POST["message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<p><input type="submit" name="submitted" value="Send"></p>';
	echo '</form>';
			
}

function email_dan_simpan() {
		//membuat key ke student untuk kebutuhan registrasi student

		$key = randkey(11);

	// if the submit button is clicked, send the email
	if ( isset( $_POST['submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["name"] );
		$email   = sanitize_text_field( $_POST["email"] );
		$subject = sanitize_text_field( $_POST["subject"] );
		$message = esc_textarea( $_POST["message"] );
		$keysend = "klick this link ".'<a href="http://localhost/dive/wp-admin/admin.php?page=draf_form">this</a>'." and paste the key (bold) into the key form <b><h3>".$key." <-- this is the key </h3></b>";
		$text = $message.''.$keysend;
		// get the blog current user's email address
		$from = wp_get_current_user();
		$fromEmail = $from->user_email;
		// $to = get_option( 'admin_email' );
		$pengajar = $from->user_login;

		$headers = "From: $name <$fromEmail>" . "\r\n";


		add_filter( 'wp_mail_content_type', 'set_html_content_type' );

		//If email has been process for sending, display a success message
		if ( wp_mail( $email, $subject, $text, $headers ) ) {
			echo '<div>';
			echo '<p>Thanks for contacting me, expect a response soon.</p>';
			echo '</div>';
			remove_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
			global $wpdb;
			$insert = $wpdb->insert('wp_register_pelajar', array('id'=>'','email_pelajar'=>$email,'level'=>$_POST["level"],'email_pengajar'=>$fromEmail, 'key_user_register'=>$key, 'pengajar'=>$pengajar), array('%d', '%s', '%s','%s','%s', '%s'));
			if($insert){
				echo '<div>';
				echo '<p>Data telah tersimpan</p>';
				echo '</div>';
			}

 if($wpdb->last_error !== '') :

        $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
        $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

        print "<div id='error'>
        <p class='wpdberror'><strong>WordPress database error:</strong> [$str]<br />
        <code>$query</code></p>
        <p> $key</p>
        </div>";

    endif;

		} else {
			echo 'An unexpected error occurred';
		}
	}
}

//untuk mengaktifkan html tag
function set_html_content_type()
{
    return 'text/html';
}
//test function
function call(){
form_daftar_course();
email_dan_simpan();
}
//end test


add_shortcode( 'draf_form_course', 'call' );//test shord code
//akhir pembuatan form create
?>
