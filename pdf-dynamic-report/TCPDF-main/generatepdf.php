<?php

require_once('tcpdf.php');

$conn = require __DIR__ . "/database.php";

session_start();
if (!isset($_SESSION["email"])) {
    // Redirect to the login page if the user is not logged in
    header('Location: index.php');
    exit();
}

$email = mysqli_real_escape_string($conn, $_SESSION['email']);
$query = "SELECT * FROM account WHERE email='$email'";
$result = mysqli_query($conn, $query);

if ($row = mysqli_fetch_assoc($result)) {
    $name = $row['userName'];
    $password = $row['password'];
    $phone = $row['phoneNum'];
    $role = $row['role'];
} else {
    // Handle case where email is not found in the database
    $name = '';
    $password = '';
    $phone = '';
    $role = '';
}



if (isset($_GET['pdf_report_generate'])) {
    $projectName = $_GET['projectName'];

    $select = "SELECT description, price FROM exception WHERE projectName = '$projectName'";
    $query = mysqli_query($conn, $select);
    while ($row = mysqli_fetch_array($query)) {
        $description = $row['description'];
        $price = $row['price'];
    }
}
/**
 * 
 */
class PDF extends TCPDF
{
    public function Header()
    {
        $imageFile = K_PATH_IMAGES . 'logo.jpg';
        $this->Image(
            $imageFile,
            10,
            10,
            70,
            '',
            'JPG',
            '',
            'T',
            false,
            100,
            '',
            false,
            false,
            0,
            false,
            false,
            false
        );
        $this->Ln(5);   //fontName, size, style


        $this->SetFont('dejavusansb', '', 10);
        //189 is total width of A4 page, height, border, line,
        $this->Cell(189, 3, 'גבארין אבו רפיק', 0, 1, 'C');
        $this->Cell(189, 3, 'עבודות בנייה ושיפוצים ע.מ. 203940218', 0, 1, 'C');
        $this->Cell(189, 3, 'מעלה עירון-זלפה', 0, 1, 'C');
        $this->Cell(189, 3, '0552802837', 0, 1, 'C');
        $this->Cell(189, 3, '0524001227', 0, 1, 'C');
        $this->Cell(189, 3, 'ת"ד 863', 0, 1, 'C');
        $this->Cell(189, 3, 'aborafeekjbareen@gmail.com', 0, 1, 'C');
        $this->SetFont('dejavusansb', 'B', 12);
        $this->Ln(12); //space
        $this->Cell(189, 3, 'חריגות פרויקט', 0, 1, 'C');
    }

    public function Footer()
    {
        $this->setY(-148); //Position at 15 mm from bottom
        $this->Ln(5);
        $this->SetFont('dejavusansb', 'B', 10);
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

        date_default_timezone_set("Asia/Tel_Aviv");
        $today = date("d-m-Y  H:i", time());

        $datetoday = date("d.m.y", time());

        $this->MultiCell(189, 15, 'הערה: דו"ח זה תקף למועד הפקתו בתאריך ' . $datetoday, 0, 'R', 0, 1, '', '', true);
        $this->Ln(2);

        $this->SetFont('dejavusansb', 'I', 8);
        //Page number



        $this->Ln(117);
        $this->Cell(
            40,
            5,
            'דף ' . $this->getAliasNumPage() . ' מתוך ' . $this->getAliasNbPages(),
            0,
            false,
            'R',
            0,
            '',
            0,
            false,
            'T',
            'M'
        );
        $this->Cell(50, 5, '', 0, 0, 'R');
        $this->Cell(5, 5, ' דו"ח חריגות פרויקט' . $projectName, 0, 0, 'R');
        $this->Cell(50, 5, '', 0, 0, 'R');
        $this->Cell(40, 5, 'הופק ב: ' . $today, 0, 0, 'R');
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
$pdf->SetTitle('Exceptions Report');
$pdf->SetSubject('');
$pdf->SetKeywords('');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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
if (@file_exists(dirname(__FILE__) . '/lang/heb.php')) {
    require_once(dirname(__FILE__) . '/lang/heb.php');
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



// Close and output PDF document
$pdf->Output('example_001.pdf', 'I');
