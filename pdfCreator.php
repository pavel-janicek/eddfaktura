<?php

require_once('tfpdf.php');

class PdfCreator extends tFPDF{

protected $payment_id;
protected $payment_meta;
protected $seller_info;
protected $buyer_info;
protected $defaultfontfamily;
protected $pavelCart;
protected $defaultfontstyle;
protected $defaultfontsize;

public function __construct($defaultfontfamily,$defaultfontstyle,$defaultfontsize,$payment_id){
 $this->payment_id = $payment_id;
 parent::__construct();
 $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true); 
 $this->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true); 
 $this->AddFont('DejaVu','I','DejaVuSansCondensed-Oblique.ttf',true); 
 $this->SetFont($defaultfontfamily,$defaultfontstyle,$defaultfontsize);
 $this->defaultfontfamily = $defaultfontfamily;
 $this->defaultfontstyle = $defaultfontstyle;
 $this->defaultfontsize = $defaultfontsize;
 $this->AliasNbPages();
 $this->AddPage();  
}

public function czech($original_text){
	//$czech_text = iconv('UTF-8', 'windows-1252', $original_text);
	return $original_text;
}

public function setPaymentID($payment_id){
  $this->payment_id = $payment_id;
}

public function header()
{
    // Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('DejaVu','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
	$txt = 'Faktura ' . $this->payment_id;
	$cztxt = $this->czech($txt);
    $this->Cell(30,10,$cztxt,0,0,'C');
    // Line break
    $this->Ln(20);
    $this->SetFont('DejaVu','',1);
    $this->Sety(1);
    $this->Cell(1,1,'9',0,0,'C');
}

public function footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('DejaVu','I',8);
    // Page number
    $this->Cell(0,10,'Strana '.$this->PageNo().'/{nb}',0,0,'C');
}

/* Simple Table
* Inputs: $header - array of header columns
* $data - one dimensional array */
public function basicTable($header, $data)
{
   $this->SetFont($this->defaultfontfamily,$this->defaultfontstyle,$this->defaultfontsize);
    // Header
    foreach($header as $col)
        $this->Cell(65,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $column){
            $this->Cell(65,6,$column,1);
        }
        $this->Ln();
    }
}

public function basicTableHeader($header){
$this->SetFont($this->defaultfontfamily,$this->defaultfontstyle,$this->defaultfontsize);
    // Header
    foreach($header as $col)
        $this->Cell(70,7,$col,1);
    $this->Ln();
}

public function setSellerInfo($edd_options){
  $info = array();
  
  $info['eddfaktura_jmeno'] = isset($edd_options['eddfaktura_jmeno']) ? $edd_options['eddfaktura_jmeno'] : '';
  $info['eddfaktura_prijmeni'] = isset($edd_options['eddfaktura_prijmeni']) ? $edd_options['eddfaktura_prijmeni'] : '';
  $info['eddfaktura_stat'] = isset($edd_options['eddfaktura_stat']) ? $edd_options['eddfaktura_stat'] : '';
  $info['eddfaktura_ic'] = isset($edd_options['eddfaktura_ic']) ? $edd_options['eddfaktura_ic'] : '';
  $info['eddfaktura_dic'] = isset($edd_options['eddfaktura_dic']) ? $edd_options['eddfaktura_dic'] : '';
  $info['eddfaktura_dph'] = isset($edd_options['eddfaktura_dph']) ? $edd_options['eddfaktura_dph'] : '';
  $info['eddfaktura_ulice'] = isset($edd_options['eddfaktura_ulice']) ? $edd_options['eddfaktura_ulice'] : '';
  $info['eddfaktura_mesto'] = isset($edd_options['eddfaktura_mesto']) ? $edd_options['eddfaktura_mesto'] : '';
  $info['eddfaktura_psc'] = isset($edd_options['eddfaktura_psc']) ? $edd_options['eddfaktura_psc'] : '';
  $this->seller_info = $info;
}

public function setBuyerInfo(){
 $firma = isset( $this->payment_meta['edd_firma'] ) ? $this->payment_meta['edd_firma'] : '';
 $stat = isset( $this->payment_meta['edd_stat'] ) ? $this->payment_meta['edd_stat'] : '';
 $ic = isset( $this->payment_meta['edd_ic'] ) ? $this->payment_meta['edd_ic'] : '';
 $dic = isset( $this->payment_meta['edd_dic'] ) ? $this->payment_meta['edd_dic'] : '';
 $ulice = isset( $this->payment_meta['edd_ulice'] ) ? $this->payment_meta['edd_ulice'] : '';
 $mesto = isset( $this->payment_meta['edd_mesto'] ) ? $this->payment_meta['edd_mesto'] : '';
 $psc = isset( $this->payment_meta['edd_psc'] ) ? $this->payment_meta['edd_psc'] : '';
 $jmeno = isset( $this->payment_meta['user_info']['first_name'] ) ? $this->payment_meta['user_info']['first_name'] : '';
 $prijmeni = isset( $this->payment_meta['user_info']['last_name'] ) ? $this->payment_meta['user_info']['last_name'] : '';
 $email =   isset( $this->payment_meta['user_info']['email'] ) ? $this->payment_meta['user_info']['email'] : '';
 $this->buyer_info['firma'] = $firma;
 $this->buyer_info['stat'] = $stat;
 $this->buyer_info['ic'] = $ic;
 $this->buyer_info['dic'] = $dic;
 $this->buyer_info['ulice'] = $ulice;
 $this->buyer_info['mesto'] = $mesto;
 $this->buyer_info['psc'] = $psc;
 $this->buyer_info['jmeno'] = $jmeno;
 $this->buyer_info['prijmeni'] = $prijmeni;
 $this->buyer_info['email'] = $email;
}

public function setPaymentMeta($meta){
  $this->payment_meta = $meta;
}

public function sellerInfo(){

 $this->SetFont($this->defaultfontfamily,'B',$this->defaultfontsize);
 
 $txt ='Dodavatel:';
 $this->Cell(70,10,$txt,0,1);
 $this->SetFont($this->defaultfontfamily,$this->defaultfontstyle,$this->defaultfontsize);
 if(!empty($this->seller_info['eddfaktura_prijmeni'])){
   $ortxt = $this->seller_info['eddfaktura_jmeno'] ." ". $this->seller_info['eddfaktura_prijmeni'];
   $txt = $this->czech($ortxt);
 }else{
   $ortxt = $this->seller_info['eddfaktura_jmeno'];
   $txt = $this->czech($ortxt);
 }
 $this->Cell(70,10,$txt,0,1);
 $this->Cell(70,10,$this->czech($this->seller_info['eddfaktura_ulice']),0,1);
 $ortxt = $this->seller_info['eddfaktura_psc'] . " ". $this->seller_info['eddfaktura_mesto'];
 $txt = $this->czech($ortxt);
 $this->Cell(70,10,$txt,0,1);
 $this->Cell(70,10,$this->seller_info['eddfaktura_stat'],0,1);
 $ortxt = 'IČ: '. $this->seller_info['eddfaktura_ic'];
 $txt = $this->czech($ortxt);
 $this->Cell(0,10,$txt,0,1);
 $ortxt = 'DIČ: '. $this->seller_info['eddfaktura_dic'];
 $txt = $this->czech($ortxt);
 $this->Cell(70,10,$txt,0,1);
 if(!empty($this->seller_info['eddfaktura_dph'])){
  $this->Cell(70,10,$this->czech('Nejsem plátcem DPH'),0,1);
 }
}

public function buyerInfo(){
  $this->SetFont($this->defaultfontfamily,'B',$this->defaultfontsize);
  $txt = 'Odběratel:';
  $this->Cell(70,10,$txt,0,1);
  $this->SetFont($this->defaultfontfamily,$this->defaultfontstyle,$this->defaultfontsize);
  if (!empty ($this->buyer_info['firma'])){
    $fullname = $this->buyer_info['firma'];
  }else{
    $fullname = $this->buyer_info['jmeno'] . " " . $this->buyer_info['prijmeni'];    
  }
  /*if (empty($this->buyer_info['firma'])){
    $fullname = $this->buyer_info['jmeno'];
  }*/
  $this->Cell(70,10,$fullname,0,1);
  $this->Cell(70, 10, $this->buyer_info['email'],0,1);
  $this->Cell(70,10,$this->buyer_info['ulice'],0,1);  
  $mestopsc = $this->buyer_info['psc'] . ' ' . $this->buyer_info['mesto'];
  $this->Cell(70,10,$mestopsc,0,1);
  $this->Cell(70,10,$this->buyer_info['stat'],0,1);
  $ic = 'IČ: ' .$this->buyer_info['ic'];
  $dic = 'DIČ: ' .$this->buyer_info['dic'];
  $this->Cell(70,10,$ic,0,1);
  $this->Cell(70,10,$dic,0,1);
  
}

public function setPavelCart($pavelCart){
  $this->pavelCart = $pavelCart;
}

public function loadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

public function setCol($col)
{
    // Set position at a given column
    $this->col = $col;
    $x = 10+$col*55;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}


 
 
}





?>
