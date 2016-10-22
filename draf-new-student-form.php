<?php
/*
Plugin Name: Form new student information
Plugin URI: http://drafshare.blogspot.co.id/
Description: Simple non-bloated WordPress Form
Version: 1.0
Author: Dirham
Author URI: https://www.facebook.com/draf.dirham
*/

// awal bagian pembuatan form untuk melakukan inputan pelajar course baru 
add_action('admin_menu','draf_student_form');//test add to admin menu

function draf_student_form() {

	//membuat menu pada halaman dashboard 
	add_menu_page('Student Information', //judul
	'Course Student Form Info', //judul menu 
	'read', //capabilities
	'draf_student_form', //menu slug
	'student_call' //function
	);

}


// TODO : tambahkan inputan gambar untuk foto student dan terapkan kode unik agar hanya yang mendapatkan email yang berhak melakukan pengimputan
function form_student_form_course() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" enctype="multipart/form-data" ccept-charset="utf-8" >';
	echo '<p>';
	echo 'Full Name : (required) <br/>';
	echo '<input type="text" name="f-name" value="' . ( isset( $_POST["f-name"] ) ? esc_attr( $_POST["f-name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Your mail : (required) <br/>';
	echo '<input type="text" name="f-email" value="' . ( isset( $_POST["f-email"] ) ? esc_attr( $_POST["f-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Genre :';
	echo '<select name="genre">
				  <option value="male">Male</option>
				  <option value="famale">Famale</option>
		  </select>';
	echo '</p>';
	echo '<p>';
	echo 'Address (required) <br/>';
	echo '<input type="text" name="f-address" value="' . ( isset( $_POST["f-address"] ) ? esc_attr( $_POST["f-address"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo '<p>';
	echo 'Country (required) <br/>';
	echo '<input type="text" name="f-country" value="' . ( isset( $_POST["f-country"] ) ? esc_attr( $_POST["f-country"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';	
	echo '<p>';
	echo 'Key from email (insert the whcich you see in your email , very key important) <br/>';
	echo '<input type="text" name="f-key_student" value="' . ( isset( $_POST["f-country"] ) ? esc_attr( $_POST["f-country"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Enter Dive Motto (the reason why) <br/>';
	echo '<textarea rows="10" cols="35" name="f-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
  echo '<input type="file" name="uploadfiles[]" id="uploadfiles" size="35" class="uploadfiles" />
        <!-- <input type="file" name="f-student_pp" id="f-student_pp"  multiple="false" /> -->
        ';
	echo '<p><input type="submit" id="btn" name="f-submitted" value="Send"></p>';
	echo '</form>';
	
}

function save_student_info() {
	// if the submit button is clicked, send the email
	if ( isset( $_POST['f-submitted'] ) ) {
  $student_key = $_POST['f-key_student'];
  global $wpdb;

  //pengecekan email dan key dari pendaftar
  
  $valid_key = $wpdb->get_results("select email_pelajar from wp_register_pelajar WHERE key_user_register = '$student_key'");
	
  	//pasang penanganan inputan foto

  $uploadfiles = $_FILES['uploadfiles'];
    if( is_array( $valid_key ) ){
        $pelajar_email = $valid_key[0]->email_pelajar;
        $get_pelajar_mail = explode(",", $pelajar_email);
        // print_r($get_pelajar_mail);
        $clean_pelajar_mail = array_map("trim", $get_pelajar_mail);
        // print_r($clean_pelajar_mail);
          if ( in_array(sanitize_text_field( $_POST["f-email"] ), $clean_pelajar_mail)){

              if (is_array($uploadfiles)) {

          foreach ($uploadfiles['name'] as $key => $value) {

          // cari file upload

            if ($uploadfiles['error'][$key] == 0) {

              $filetmp = $uploadfiles['tmp_name'][$key];

              //clean filename and extract extension
              $filename = $uploadfiles['name'][$key];

              // get file info
              // @fixme: wp checks the file extension....
              $filetype = wp_check_filetype( basename( $filename ), null );
              $filetitle = preg_replace('/\.[^.]+$/', '', basename( $filename ) );
              $filename = $filetitle . '.' . $filetype['ext'];
              $upload_dir = wp_upload_dir();

        /**
         * perikasa nama file di folder upload jika ada rename
         * file jika diperlukan
         */
              $i = 0;
              while ( file_exists( $upload_dir['path'] .'/' . $filename ) ) {
                $filename = $filetitle . '_' . $i . '.' . $filetype['ext'];
                $i++;
              }
              $filedest = $upload_dir['path'] . '/' . $filename;

        /**
         * periksa apakah folder yang dituju dapat di tulisi
         */
              if ( !is_writeable( $upload_dir['path'] ) ) {
                printf("Unable to write to directory %s. Is this directory writable by the server?",$upload_dir['path']);
                return;
              }

        /**
         * Save temporary file ke upload folder
         */
            if ( !@move_uploaded_file($filetmp, $filedest) ){
              sprintf("Error, the file %s could not moved to : %s ",$filetmp,$filedest );
              continue;
            }

            $attachment = array(
              'post_mime_type' => $filetype['type'],
              'post_title' => $filetitle,
              'post_content' => '',
              'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment( $attachment, $filedest );
            require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata( $attach_id, $filedest );
            wp_update_attachment_metadata( $attach_id,  $attach_data );

            // return $filedest;
          }
        }
  
    // dapatkan seluruh inputan
    $name    = sanitize_text_field( $_POST["f-name"] );
    $email   = sanitize_text_field( $_POST["f-email"] );
    $genre   = sanitize_text_field( $_POST["genre"] );
    $country   = sanitize_text_field( $_POST["f-country"] );
    $address = sanitize_text_field( $_POST["f-address"] );
    $message = esc_textarea( $_POST["f-message"] );
    // get the blog current user's email addressthe blog current user's email address
    $from = wp_get_current_user();
    $fromEmail = $from->user_email;
    // $to = get_option( 'admin_email' );
    $pengajar = $from->user_login;
    $subject = "Sukses melengkapi pendaftaran";
    $headers = "From: $name <$fromEmail>" . "\r\n";
    $isi_pesan = "Terimakasih telah melengkapi pendaftaran sebagai siswa kursus kami, silahkan tunggu atau hubungi mentor terkait jadwal kursus dan lain - lain pada email berikut : $pengajar ";
    // If email has been process for sending, display a success message
    if ( wp_mail( $email, $subject, $message, $headers ) ) {
     echo '<div>';
     echo '<p>Thanks for contacting me, expect a response soon.</p>';
     echo '</div>';
    echo $attach_id;
    
      $insert = $wpdb->insert('wp_registered_pelajar', array('id'=>'', 'nama_pelajar'=>$name,'email_pelajar'=>$email,'jkel'=>$genre,'negara'=>$country,'alamat'=>$address,'message'=>$message,'email_pengajar'=>$fromEmail, 'pengajar'=>$pengajar, 'profil_picture_id'=>$attach_id ), array('%d', '%s', '%s','%s','%s','%s','%s','%s','%s', '%d'));
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
        </div>";

    endif;

    } else {
     echo 'An unexpected error occurred';
    }

        // return $filedest;
      }
    }
  
    // sanitize form values
    
    }
  
  }
  
}


// TODO : buat pengecekan key pada tabel lain


//test function
function student_call(){

form_student_form_course();
save_student_info();

}
//end test


add_shortcode( 'student_info_form', 'student_call' );//test shord code
//akhir pembuatan form create

// panggil pengontroll file upload wp
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );
?>
