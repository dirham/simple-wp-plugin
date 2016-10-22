<?php
/*
Plugin Name: sertification test
Description: add new student for the Course, thanks for http://sinetiks.com for great tutorial
Version: 1
Author: dirham
Author URI:https://www.facebook.com/draf.dirham
*/

add_action('admin_menu','test_required');//test add to admin menu

function test_required() {

	//membuat menu pada halaman dashboard 
	add_menu_page('Student Information', //judul
	'persiapan sertifikat', //judul menu 
	'read', //capabilities
	'test_required', //menu slug
	'test' //function
	);

}

function show_all_(){
	global $wpdb;
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post" >';
	echo '<p>';
	echo 'Pilih Mentor :';
	echo '<select name="mentor">;
		// tampilkan semua mentor untuk dipilih
				  
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
        <input type="file" name="f-student_pp" id="f-student_pp"  multiple="false" /> ';
	echo '<p><input type="submit" id="btn" name="f-submitted" value="Send"></p>';
	echo '</form>';
}

function test(){

// test include template
    // ob_start();
    // $test = include(dirname(__FILE__). '/html2pdf/examples/res/exemple10.php');<img src="./res/exemple10c.gif" alt="" style="border: none;"></div>
    
    // $content = ob_get_clean();

    $content = '<div style="position:relative;overflow:hidden;width:454px;height:138px;padding:0px;font-size:11px;text-align:left;font-weight:normal;background:url(http://localhost/dive/wp-content/uploads/2016/10/00027594.Abyss-300x169.jpg);" >';
    // $content .= '<img class="icone" src="" alt="HTML2PDF" >';
    $content .= '<img style="position: absolute; border: none; left: 5px;   top: 5px;  width: 240px; height: 128px;overflow: hidden;" src="http://localhost/dive/wp-content/uploads/2016/10/Screenshot-Downloading...-1-150x150.png" >';
        $content .= '<div style="position: absolute; border: none; left: 257px; top: 8px;  width: 188px; height: 14px; padding-top: 1px; overflow: hidden; text-align: center; font-weight: bold;">HTML2PDF</div>
        <div style="position: absolute; border: none; left: 315px; top: 28px; width: 131px; height: 14px; padding-top: 1px; overflow: hidden; text-align: left; font-weight: normal;">PHP</div>
        <div style="position: absolute; border: none; left: 315px; top: 48px; width: 131px; height: 14px; padding-top: 1px; overflow: hidden; text-align: left; font-weight: normal;">Utilitaire</div>
        <div style="position: absolute; border: none; left: 315px; top: 68px; width: 131px; height: 14px; padding-top: 1px; overflow: hidden; text-align: left; font-weight: normal;">1.00</div>
        <div style="position: absolute; border: none; left: 315px; top: 88px; width: 131px; height: 14px; padding-top: 1px; overflow: hidden; text-align: left; font-weight: normal;">01/01/1901</div>
        <div style="position: absolute; border: none; left: 257px; top: 108px;width: 188px; height: 22px; overflow: hidden; text-align: center; font-weight: normal;"></div>
    </div>';
echo $content;
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'en');
        $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
                // $html2pdf->Output(dirname(__FILE__) . '/html2pdf/9.pdf','F');

        $html2pdf->Output(dirname(__FILE__) . '/html2pdf/jorgess.pdf', 'F');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }



}


function admin_load_js(){
    wp_enqueue_script( 'custom_js', plugins_url( '/test.js', __FILE__ ), array('jquery') );
    wp_enqueue_style('cssji', plugins_url( '/css-sertificat.css', __FILE__ ));
    wp_enqueue_script( 'custom_e_js', plugins_url( '/html2canvas.js', __FILE__ ), array('jquery') );
    wp_enqueue_script( 'custom_eq_js', plugins_url( '/canvas2image.js', __FILE__ ), array('jquery') );
}
add_action('admin_enqueue_scripts', 'admin_load_js');

function my_custom_email_content_type() {
    return 'text/html';
}
require_once(dirname(__FILE__) . '/html2pdf/html2pdf.class.php');
