<?php
/*
Plugin Name: PDF Faktura pro Easy Digital Downloads
Plugin URL: http://cleverstart.cz
Description: Vygeneruje PDF fakturu po zprocesování platby a pošle ji zákazníkovi na e-mail který uvedl při nákupu
Version: 0.8.5
Author: Pavel Janíček
Author URI: http://cleverstart.cz
*/


function eddfaktura_add_settings($settings){
	$eddfaktura_settings = array (
		array(
			'id' => 'eddfaktura_settings',
			'name' => '<strong>Nastavení fakturačních údajů</strong>',
			'desc' => 'Níže uvedené údaje se budou zobrazovat na každé vystavené faktuře.',
			'type' => 'header'
		),
		array(
			'id' => 'eddfaktura_jmeno',
			'name' => 'Jméno / Název společnosti',
			'desc' => 'Vyplňte obchodní jméno',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_prijmeni',
			'name' => 'Příjmení',
			'desc' => 'Vyplňte své přijímení. Pokud necháte prázdné, nic se na faktuře neobjeví',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_stat',
			'name' => 'Stát',
			'desc' => 'Stát ve kterém sídlí vaše společnost',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_ic',
			'name' => 'IČ',
			'desc' => 'IČ vaší společnosti',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_dic',
			'name' => 'DIČ',
			'desc' => 'DIČ vaší společnosti',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_dph',
			'name' => 'Nejsem plátcem DPH',
			'desc' => 'Po zaškrtnutí se tato informace přidá na fakturu',
			'type' => 'checkbox'
		),
		array(
			'id' => 'eddfaktura_ulice',
			'name' => 'Ulice a číslo popisné',
			'desc' => 'Ulice a číslo popisné vaší společnosti',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_mesto',
			'name' => 'Město',
			'desc' => 'Město ve kterém sídlí vaše společnost',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_psc',
			'name' => 'PSČ',
			'desc' => 'PSČ',
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'eddfaktura_zobraz',
			'name' => 'Zobrazit fakturační údaje',
			'desc' => 'Jakmile zaškrtnete, zákazník bude moci vyplnit své fakturační údaje',
			'type' => 'checkbox'

		),
	);
	return array_merge( $settings, $eddfaktura_settings );

}

add_filter( 'edd_settings_taxes', 'eddfaktura_add_settings' );

function eddfaktura_edd_display_checkout_fields(){
	global $edd_options;
	if ( (isset($edd_options['eddfaktura_zobraz'])) AND (!empty($edd_options['eddfaktura_zobraz'])) ){
		eddfaktura_edd_checkout_fields();
	}else{
		return;
	}
}

function eddfaktura_edd_checkout_fields() {

?>
    <p id="edd-faktura-general">
	  <strong>Fakturační údaje:</strong>
	</p>
    <p id="edd-faktura-firma-wrap">
        <label class="edd-label" for="edd-firma">Název společnosti</label>
        <span class="edd-description">
        	Vyplňte název vaší společnosti. Pokud toto pole vyplníte, vaše křestní jméno se na faktuře neobjeví
        </span>
        <input class="edd-input" type="text" name="edd_firma" id="edd-firma" placeholder="" />
    </p>
	<p id="edd-faktura-stat-wrap">
        <label class="edd-label" for="edd-stat">Stát</label>
        <span class="edd-description">
        	Vyplňte stát vaší společnosti
        </span>
        <input class="edd-input" type="text" name="edd_stat" id="edd-stat" placeholder="" />
    </p>
    <p id="edd-faktura-ic-wrap">
        <label class="edd-label" for="edd-ic">IČ</label>
        <span class="edd-description">
        	Vyplňte IČ vaší společnosti
        </span>
        <input class="edd-input" type="text" name="edd_ic" id="edd-ic" placeholder="" />
    </p>
    <p id="edd-faktura-dic-wrap">
        <label class="edd-label" for="edd-dic">DIČ</label>
        <span class="edd-description">
        	Vyplňte DIČ vaší společnosti
        </span>
        <input class="edd-input" type="text" name="edd_dic" id="edd-dic" placeholder="" />
    </p>
	<p id="edd-faktura-ulice-wrap">
        <label class="edd-label" for="edd-ulice">Ulice a číslo popisné</label>
        <span class="edd-description">
        	Vyplňte ulici sídla vaší společnosti
        </span>
        <input class="edd-input" type="text" name="edd_ulice" id="edd-ulice" placeholder="" />
    </p>
	<p id="edd-faktura-mesto-wrap">
        <label class="edd-label" for="edd-mesto">Město</label>
        <span class="edd-description">
        	Vyplňte město sídla vaší společnosti
        </span>
        <input class="edd-input" type="text" name="edd_mesto" id="edd-mesto" placeholder="" />
    </p>
	<p id="edd-faktura-psc-wrap">
        <label class="edd-label" for="edd-ulice">PSČ</label>
        <span class="edd-description">
        	Vyplňte PSČ
        </span>
        <input class="edd-input" type="text" name="edd_psc" id="edd-psc" placeholder="" />
    </p>
    <?php
}

add_action( 'edd_purchase_form_user_info_fields', 'eddfaktura_edd_display_checkout_fields' );

function eddfaktura_all_extra_fields() {
  $eddfaktura_fields[] = "edd_firma";
  $eddfaktura_fields[] = "edd_stat";
  $eddfaktura_fields[] = "edd_ic";
  $eddfaktura_fields[] = "edd_dic";
  $eddfaktura_fields[] = "edd_ulice";
  $eddfaktura_fields[] = "edd_mesto";
  $eddfaktura_fields[] = "edd_psc";
  return $eddfaktura_fields;
}

/**
 * Store the custom field data into EDD's payment meta
 */

function eddfaktura_store_payment_meta($payment_meta){
  $extra_fields = eddfaktura_all_extra_fields();
  foreach ($extra_fields as $key => $extra_field){
    $payment_meta[$extra_field] = isset( $_POST[$extra_field] ) ? sanitize_text_field( $_POST[$extra_field] ) : '';
  }
  return $payment_meta;
}

add_filter( 'edd_payment_meta', 'eddfaktura_store_payment_meta');

/**
 * Add the fields to the "View Order Details" page
 */
function eddfaktura_edd_view_order_details( $payment_meta, $user_info ) {
	$firma = isset( $payment_meta['edd_firma'] ) ? $payment_meta['edd_firma'] : 'položka neuvedena';
    $stat = isset( $payment_meta['edd_stat'] ) ? $payment_meta['edd_stat'] : 'položka neuvedena';
    $ic = isset( $payment_meta['edd_ic'] ) ? $payment_meta['edd_ic'] : 'položka neuvedena';
    $dic = isset( $payment_meta['edd_dic'] ) ? $payment_meta['edd_dic'] : 'položka neuvedena';
    $ulice = isset( $payment_meta['edd_ulice'] ) ? $payment_meta['edd_ulice'] : 'položka neuvedena';
    $mesto = isset( $payment_meta['edd_mesto'] ) ? $payment_meta['edd_mesto'] : 'položka neuvedena';
    $psc = isset( $payment_meta['edd_psc'] ) ? $payment_meta['edd_psc'] : 'položka neuvedena';
	$payment_id = $_GET['id'];
	$link = eddfaktura_on_complete_purchase($payment_id);
  	$html = "<a href=\"" .$link. "\">Fakturu si stáhněte zde</a>";
?>
    <div class="column-container">
    	<div class="column">
    		<strong>Firma: </strong>
    		 <?php echo $firma; ?>
    	</div>
		<div class="column">
    		<strong>Stát: </strong>
    		 <?php echo $stat; ?>
    	</div>
		<div class="column">
    		<strong>IČ: </strong>
    		 <?php echo $ic; ?>
    	</div>
		<div class="column">
    		<strong>DIČ: </strong>
    		 <?php echo $dic; ?>
    	</div>
		<div class="column">
    		<strong>Ulice a číslo popisné: </strong>
    		 <?php echo $ulice; ?>
    	</div>
		<div class="column">
    		<strong>Město: </strong>
    		 <?php echo $mesto; ?>
    	</div>
		<div class="column">
    		<strong>PSČ: </strong>
    		 <?php echo $psc; ?>
    	</div>
		<div class="column">
			<strong>Faktura: </strong>
			<?php echo $html; ?>
		</div>	
    </div>
<?php
}
add_action( 'edd_payment_personal_details_list', 'eddfaktura_edd_view_order_details', 10, 2 );


function eddfaktura_add_all_email_tags($payment_id){
	edd_add_email_tag( 'eddfaktura_firma', 'Firma zákazníka', 'eddfaktura_edd_email_tag_firma' );
	edd_add_email_tag( 'eddfaktura_stat', 'Stát zákazníka', 'eddfaktura_edd_email_tag_stat' );
	edd_add_email_tag( 'eddfaktura_ic', 'IČ zákazníka', 'eddfaktura_edd_email_tag_ic' );
	edd_add_email_tag( 'eddfaktura_dic', 'DIČ zákazníka', 'eddfaktura_edd_email_tag_dic' );
	edd_add_email_tag( 'eddfaktura_ulice', 'Ulice a číslo popisné zákazníka', 'eddfaktura_edd_email_tag_ulice' );
	edd_add_email_tag( 'eddfaktura_mesto', 'Město zákazníka', 'eddfaktura_edd_email_tag_mesto' );
	edd_add_email_tag( 'eddfaktura_psc', 'PSČ zákazníka', 'eddfaktura_edd_email_tag_psc' );
	edd_add_email_tag( 'eddfaktura_polozky', 'Položky v košíku', 'eddfaktura_edd_email_tag_polozky' );
	edd_add_email_tag( 'eddfaktura_datum', 'Datum nákupu', 'eddfaktura_edd_email_tag_datum' );
	edd_add_email_tag( 'eddfaktura_faktura_link', 'Neformátovaný odkaz na fakturu', 'eddfaktura_on_complete_purchase' );
	edd_add_email_tag( 'eddfaktura_faktura_invoice', 'Vloží odkaz s textem fakturu si stáhněte zde', 'download_invoice' );

}
add_action( 'edd_add_email_tags', 'eddfaktura_add_all_email_tags' );

/**
 * Add a custom fields  tag for use in either the purchase receipt email or admin notification emails
 */


/**
 * The {Firma} email tag
 */
function eddfaktura_edd_email_tag_firma( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_firma'];
}

/**
 * The {Stat} email tag
 */
function eddfaktura_edd_email_tag_stat( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_stat'];
}

/**
 * The {IC} email tag
 */
function eddfaktura_edd_email_tag_ic( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_ic'];
}

/**
 * The {DIC} email tag
 */
function eddfaktura_edd_email_tag_dic( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_dic'];
}

/**
 * The {Ulice} email tag
 */
function eddfaktura_edd_email_tag_ulice( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_ulice'];
}

/**
 * The {Mesto} email tag
 */
function eddfaktura_edd_email_tag_mesto( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_mesto'];
}

/**
 * The {PSC} email tag
 */
function eddfaktura_edd_email_tag_psc( $payment_id ) {
	$payment_data = edd_get_payment_meta( $payment_id );
	return $payment_data['edd_psc'];
}

function eddfaktura_edd_email_tag_polozky($payment_id){
   $decissions = eddfaktura_format_polozky($payment_id);
   return $decissions[1];
}

function eddfaktura_format_polozky($payment_id){
    if (function_exists('get_home_path')){
    	$path = get_home_path();
    	$path .= "wp-content/plugins/easy-digital-downloads/includes/payments/class-edd-payment.php";
    }else{
    	$path = dirname(__FILE__) . "/../easy-digital-downloads/includes/payments/class-edd-payment.php";
    }
    require_once($path);

	$cart_items = edd_get_payment_meta_cart_details( $payment_id );
    $payment = new EDD_Payment( $payment_id );
	$payment_data  = $payment->get_meta();
	$download_list = '<ul>';
	$cart_items    = $payment->cart_details;
	$email         = $payment->email;
    	if ( $cart_items ) {
		$show_names = apply_filters( 'edd_email_show_names', true );
		$show_links = apply_filters( 'edd_email_show_links', true );
        $i = 0;
		foreach ( $cart_items as $item ) {
			if ( edd_use_skus() ) {
				$sku = edd_get_download_sku( $item['id'] );
			}

				$quantity = $item['quantity'];
                $pavelCart[$i]['quantity'] = $item['quantity'];

        $price_id = edd_get_cart_item_price_id( $item );
			//if ( $show_names ) {
			if ( false ) {
				$title = '<strong>' . get_the_title( $item['id'] ) . '</strong>';

				if ( ! empty( $quantity ) && $quantity > 1 ) {
					$title .= "&nbsp;&ndash;&nbsp;" . __( 'Quantity', 'easy-digital-downloads' ) . ': ' . $quantity;
				}
				if ( ! empty( $sku ) ) {
					$title .= "&nbsp;&ndash;&nbsp;" . __( 'SKU', 'easy-digital-downloads' ) . ': ' . $sku;
				}
				if ( $price_id !== null ) {
					$title .= "&nbsp;&ndash;&nbsp;" . edd_get_price_option_name( $item['id'], $price_id, $payment_id );

				}
				$download_list .= '<li>' . apply_filters( 'edd_email_receipt_download_title', $title, $item, $price_id, $payment_id ) . '<br/>';
			}
      			$price_id = edd_get_cart_item_price_id( $item );
                $pavelCart[$i]['price_id']  = edd_get_cart_item_price_id( $item );
			if ( $show_names ) {
				$title = '<strong>' . get_the_title( $item['id'] ) . '</strong>';
                $pavelCart[$i]['title'] = get_the_title( $item['id'] );
				if ( ! empty( $quantity )  ) {
					$title .= "&nbsp;&ndash;&nbsp;" . __( 'Množství', 'easy-digital-downloads' ) . ': ' . $quantity ." ks";
				}
				if ( ! empty( $sku ) ) {
					$title .= "&nbsp;&ndash;&nbsp;" . __( 'SKU', 'easy-digital-downloads' ) . ': ' . $sku;
				}
				if ( $price_id !== null ) {
					$title .= "&nbsp;&ndash;&nbsp;" . edd_get_price_option_name( $item['id'], $price_id, $payment_id );
                    $pavelCart[$i]['price_option_name'] = edd_get_price_option_name( $item['id'], $price_id, $payment_id );
				}
                $title .="&nbsp;&ndash;&nbsp;" . "Jednotková cena: " .$item["price"]. " CZK</span>";
                $pavelCart[$i]['jednotkova_cena'] = $item["price"];
                //$title .="<span>; " .$item["quantity"]. " ks";
                $i++;
				$download_list .= '<li>' . apply_filters( 'edd_email_receipt_download_title', $title, $item, $price_id, $payment_id ) . '<br/>';
			}
			$files = edd_get_download_files( $item['id'], $price_id );
			if ( ! empty( $files ) ) {
				foreach ( $files as $filekey => $file ) {
					if ( $show_links ) {
						$download_list .= '<div>';
							$file_url = edd_get_download_file_url( $payment_data['key'], $email, $filekey, $item['id'], $price_id );
							$download_list .= '<a href="' . esc_url( $file_url ) . '">' . edd_get_file_name( $file ) . '</a>';
							$download_list .= '</div>';
					} else {
						$download_list .= '<div>';
							$download_list .= edd_get_file_name( $file );
						$download_list .= '</div>';
					}
				}
			} elseif ( edd_is_bundled_product( $item['id'] ) ) {
				$bundled_products = apply_filters( 'edd_email_tag_bundled_products', edd_get_bundled_products( $item['id'] ), $item, $payment_id, 'download_list' );
				foreach ( $bundled_products as $bundle_item ) {
					$download_list .= '<div class="edd_bundled_product"><strong>' . get_the_title( $bundle_item ) . '</strong></div>';
					$files = edd_get_download_files( $bundle_item );
					foreach ( $files as $filekey => $file ) {
						if ( $show_links ) {
							$download_list .= '<div>';
							$file_url = edd_get_download_file_url( $payment_data['key'], $email, $filekey, $bundle_item, $price_id );
							$download_list .= '<a href="' . esc_url( $file_url ) . '">' . edd_get_file_name( $file ) . '</a>';
							$download_list .= '</div>';
						} else {
							$download_list .= '<div>';
							$download_list .= edd_get_file_name( $file );
							$download_list .= '</div>';
						}
					}
				}
			}
			if ( '' != edd_get_product_notes( $item['id'] ) ) {
				$download_list .= ' &mdash; <small>' . edd_get_product_notes( $item['id'] ) . '</small>';
			}
			if ( $show_names ) {
				$download_list .= '</li>';
			}
		}
	}
	$download_list .= '</ul>';


	/*foreach ($cart_items as $cart_item){
		$html .= "<li>";
		$html .= "<strong>";
		$html .= $cart_item["name"];
		$html .= "</strong>";
		$html .= "<span> ";
		$html .= $cart_item["quantity"];
		$html .= " ks </span>";
		$html .= "<span> Jednotková cena: ";
		$html .= $cart_item["item_price"];
		$html .= " Kč</span>";
		$html .= "</li>";
	}
	$html .= "</ul>";*/
    $decissions = array(
       '1' =>$download_list,
       '2' =>$pavelCart
    );
	return $decissions;

}

function totalPrice($payment_id){
    if (function_exists('get_home_path')){
    	$path = get_home_path();
    	$path .= "wp-content/plugins/easy-digital-downloads/includes/payments/class-edd-payment.php";
    }else{
    	$path = dirname(__FILE__) . "/../easy-digital-downloads/includes/payments/class-edd-payment.php";
    }
    require_once($path);
	$payment = new EDD_Payment( $payment_id );
	$price   = edd_currency_filter( edd_format_amount( $payment->total ), $payment->currency );
	return html_entity_decode( $price, ENT_COMPAT, 'UTF-8' );
}

function eddfaktura_prepare_data($pavelCart){
   $i =0;
   $data = array();
   foreach ($pavelCart as $item){
     //if(!empty($item['price_option_name'])){
     if(false){
      $data[$i][] = $item['title'] .' - '. $item['price_option_name'];
   }else{
      $data[$i][] = $item['title'];
   }
     $data[$i][] = $item['quantity'];
     $data[$i][] = $item['jednotkova_cena'];
     $i++;
   }
  return $data;
}

function eddfaktura_edd_email_tag_datum($payment_id){
  $payment_meta = edd_get_payment_meta( $payment_id );
  $date = DateTime::createFromFormat('Y-m-d G:i:s', $payment_meta['date']);
  return $date->format('d.m. Y');
}

function eddfaktura_on_complete_purchase( $payment_id ) {
     $filename = plugin_dir_path(__FILE__). "invoices/". $payment_id .".pdf";
	 require_once('encryption.php');
     if (file_exists($filename)){

       require('password.php');
       $tajne = eddfaktura_edd_email_tag_datum($payment_id) .'|'.$payment_id;
       //$secret = base64_encode(eddfaktura_encrypt($tajne,$key));
		$secret = base64_encode($tajne);
       $link = get_site_url(). "/wp-content/plugins/eddfaktura/download.php?fileid=" .$secret;
       return $link;
     }else{
     require('pdfCreator.php');

     require('password.php');

     $pdf = new PdfCreator('DejaVu','','10');
    global $edd_options;

	// Basic payment meta
	$payment_meta = edd_get_payment_meta( $payment_id );
	// Cart details
	$cart_items = edd_get_payment_meta_cart_details( $payment_id );
    $pdf->setSellerInfo($edd_options);
    $pdf->SetY(25);
    $pdf->sellerInfo();
    $pdf->setPaymentMeta($payment_meta);
    $pdf->setBuyerInfo();
    $pdf->setCol(2);
    $pdf->SetY(25);
    $pdf->buyerInfo();
    $pdf->setCol(0);
    $pdf->Ln();
    $pdf->Ln();
    $datum = eddfaktura_edd_email_tag_datum($payment_id);
    $vystaveni = 'Datum vystavení: '. $datum;
    $plneni = 'Datum zdanitelného plnění: ' . $datum;
    $splatnost = 'Datum splatnosti: ' . $datum;
    $pdf->Cell(70,10,$vystaveni,0,1);
    $pdf->Cell(70,10,$plneni,0,1);
    $pdf->Cell(70,10,$splatnost,0,1);
    $pdf->SetFont('DejaVu','B','10');
    $pdf->Ln();
    $pdf->Cell(70,10,'Zakoupené položky:',0,1);
    $pdf->Ln();
    $tableheader = ['Jméno položky', 'Množství (ks)', 'Jednotková cena (CZK)'];
    $cart = eddfaktura_format_polozky($payment_id);

    $data = eddfaktura_prepare_data($cart[2]);
    $x = 4;
    $pdf->SetLeftMargin($x);
    $pdf->SetX($x);
    $pdf -> basicTable($tableheader,$data);
    $pdf->Ln();
    $pdf->setCol(0);
    $pdf->SetFont('DejaVu','B','10');
    $celkem = totalPrice($payment_id);
    $cenacelkem = "Cena celkem: " .$celkem;
    $pdf->Cell(70,10,$cenacelkem,0,1);
    $pdf->SetFont('DejaVu','B','14');
    $pdf->Ln();
    $pdf->Cell(70,10,'Neplaťte! Již bylo uhrazeno.',0,1);

    $filename = plugin_dir_path(__FILE__). "invoices/". $payment_id .".pdf";


    $pdf->Output($filename, 'F');
    //print_r($tableheader);
    //exit;
    //$link = get_site_url(). "/wp-content/plugins/eddfaktura/invoices/" .$payment_id .".pdf";
    $tajne = eddfaktura_edd_email_tag_datum($payment_id) .'|'.$payment_id;
    //$secret = base64_encode(eddfaktura_encrypt($tajne,$key));
	$secret = base64_encode($tajne);
    $link = get_site_url(). "/wp-content/plugins/eddfaktura/download.php?fileid=" .$secret;
    return $link;

    }

}


function download_invoice($payment_id){
  $link = eddfaktura_on_complete_purchase($payment_id);
  $html = "<a href=\"" .$link. "\">Fakturu si stáhněte zde</a>";
  return $html;
}




function echo_file($payment_id){
  $filename = plugin_dir_path(__FILE__). "countries.txt";
  file_put_contents($filename, eddfaktura_on_complete_purchase( $payment_id ));

}



//add_action( 'edd_complete_purchase', 'echo_file' );

?>
