<?php 

require_once('tcpdf.php');

$conn = require __DIR__ . "/database.php";

if(isset($_GET['pdf_report_generate'])) {
    $frmDate =  $_GET['fromDate'];

    $select = "SELECT * FROM report WHERE reportDate >= '$frmDate' AND paid = '0'";
    $query = mysqli_query($conn, $select);
   
   

/**
 * 
 */
class PDF extends TCPDF
{
   public function Header(){
        $imageFile = K_PATH_IMAGES.'logo.jpg';
        $this->Image($imageFile, 10, 10, 70, '', 'JPG', '', 'T', false, 100, 'L', false, false,
        0, false, false, false);
        $this->Ln(5);   //fontName, size, style

        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
       
        $this->SetFont('dejavusans', '', 12, '', true);
        //189 is total width of A4 page, height, border, line,
        $this->MultiCell(189, 3, 'גבארין אבו רפיק', 0,'R', 0,1, '', '', true);
        $this->MultiCell(189, 3, 'עבודות בנייה ושיפוצים ע.מ. 203940218', 0,'R', 0,1, '', '', true);
        $this->MultiCell(189, 3, 'מעלה עירון-זלפה', 0,'R', 0,1, '', '', true);
        $this->MultiCell(189, 3, '0552802837', 0,'R', 0,1, '', '', true);
        $this->MultiCell(189, 3, '0524001227', 0,'R', 0,1, '', '', true);
        $this->MultiCell(189, 3, 'ת"ד 863', 0,'R', 0,1, '', '', true);
        $this->MultiCell(189, 3, 'aborafeekjbareen@gmail.com', 0,'R', 0,1, '', '', true);
        $this->SetFont('dejavusansb', 'B', 12);
        $this->Ln(22); //space
        $this->Cell(189, 3, 'רשימת דוחות לא שולמו',0,1,'C');
   
    }   

   public function Footer(){
        $this->setY(-148); //Position at 15 mm from bottom
        $this->Ln(110);
        $this->SetFont('dejavusansb', 'B', 10);
        
        date_default_timezone_set("Asia/Tel_Aviv");
        $today = date("d-m-Y  H:i", time());
        
        $datetoday = date("d.m.y", time());
        
        $this->MultiCell(180, 15, 'הערה: דו"ח זה תקף למועד הפקתו בתאריך '.$datetoday, 0, 'R', 0, 1, '', '', true);
        $this->Ln(2);

        $this->SetFont('dejavusansb', 'I', 8);
        //Page number



        $this->Ln(15);
        $this->Cell(40,5,'הופק ב: '.$today, 0,0, 'R');
        $this->Cell(50,5,'', 0,0, 'R');
        $this->Cell(5,5,'דו"ח רשימת דוחות לא שלומו', 0,0, 'R');
        $this->Cell(50,5,'', 0,0, 'R');
        $this->Cell(40, 5, 'דף '.$this->getAliasNumPage(). ' מתוך '.$this->getAliasNbPages(),
        0, false, 'R', 0, '', 0, false, 'T', 'M');
       
       

        
   
    }
    
}



// create new PDF document
$pdf = new PDF('p', 'mm', 'A4', true, 'UTF-8', false);

// convert TTF font to TCPDF format and store it on the fonts folder
//$fontname = TCPDF_FONTS::addTTFfont('/fonts/DejaVu Sans 400.ttf', 'TrueTypeUnicode', '', 96);
// use the font


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nermin');
$pdf->SetTitle('דו"ח רשימת דוחות לא שולמו');
$pdf->SetSubject('');
$pdf->SetKeywords('');
$pdf->setRTL(true);

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/heb.php')) {
    require_once(dirname(__FILE__).'/lang/heb.php');
    $pdf->setLanguageArray($l);
}



// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 14, '', true);




// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();


$pdf->Ln(60); 

//$this->MultiCell(189, 15, 'הערה: דו"ח זה תקף למועד הפקתו בתאריך '.$datetoday, 0, 'R', 0, 1, '', '', true);
$pdf ->SetFont('dejavusans', 'B',10);
$pdf ->SetTextColor(51, 99, 148);
$pdf->MultiCell(189, 3, 'מתאריך: '.$frmDate.' ', 0,'C', 0,1, '', '', true);
$pdf->Ln(10);

$pdf ->SetTextColor(0, 0, 0);
$pdf ->SetFont('dejavusans', '', 10, '', true);
$pdf->SetFillColor(162, 191, 220);
$pdf->Cell(20,5,'מס', 1,0,'C',1);
$pdf->Cell(25,5,'מס דו"ח ', 1,0,'C',1);
$pdf->Cell(30,5,'מספר רכב', 1,0,'C',1);
$pdf->Cell(25,5,'סכום', 1,0,'C',1);
$pdf->Cell(30,5,'תאריך הדו"ח', 1,0,'C',1);
$pdf->Cell(30,5,'הערות', 1,0,'C',1);

$i = 1; //no of page start
$max = 12; //when s1 no == 12 go to next page
$total = 0;
        while ($row = mysqli_fetch_array($query))
        {
            $reportId = $row['reportNumber'];
            $carNumber = $row['carNumber'];
            $price = $row['price'];
            $reportDate = $row['reportDate'];
            $notes = $row['notes'];
           
            if (($i%$max) == 0){
            $pdf->AddPage();
            $pdf->Ln(60); 
            $pdf ->SetFont('dejavusans', '', 10, '', true);
            $pdf->Ln(10);
            $pdf->SetFillColor(162, 191, 220);
            $pdf->Cell(20,5,'מס', 1,0,'C',1);
            $pdf->Cell(25,5,'מס דו"ח ', 1,0,'C',1);
            $pdf->Cell(30,5,'מספר רכב', 1,0,'C',1);
            $pdf->Cell(25,5,'סכום', 1,0,'C',1);
            $pdf->Cell(30,5,'תאריך הדו"ח', 1,0,'C',1);
            $pdf->Cell(30,5,'הערות', 1,0,'C',1);
        }

        $pdf->Ln(10);
        $pdf->Cell(20,5, $i, 0,0,'C');
        $pdf->MultiCell(25,5, $reportId, 0,'C', 0,0, '', '', true);
        $pdf->Cell(30,5, $carNumber, 0,0,'C');
        $priceFormatted = number_format($price, 0, '.', ',');
        $pdf->Cell(25,5, $priceFormatted, 0,0,'C');
        $pdf->Cell(30,5, $reportDate, 0,0,'C');
        $pdf->Cell(30,5, $notes, 0,0,'C');
        $i++;
        $total+=$price;
    }
    $pdf->Ln(15);
    $pdf ->SetFont('dejavusans', 'B',10);
    // Format the $total with commas
    $totalFormatted = number_format($total, 0, '.', ',');
    $pdf->Cell(180, 8, 'סה"כ סכום : '.$totalFormatted.'₪',0,1,'R',0);
    $pdf->Ln(25);
   
}
// Close and output PDF document
$pdf->Output('reports_notpaid.pdf', 'I');