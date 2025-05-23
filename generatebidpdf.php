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
    $bidDate =  $_GET['bidDate'];
    $forward =  $_GET['forward'];
    $address =  $_GET['address'];
    $body =  $_GET['body'];
    $price =  $_GET['price'];
    // Check if the checkbox is checked
    $includePrice = isset($_GET['includePrice']) ? true : false;



    //$select = "SELECT * FROM checks WHERE checkDate BETWEEN '$frmDate' AND '$toDate'";
    //$query = mysqli_query($conn, $select);



    /**
     * 
     */
    class PDF extends TCPDF
    {
        private $isHeaderAdded = false; // Flag to track whether the header has been added

        public function Header()
        {
            // Check if the header has not been added
            if (!$this->isHeaderAdded) {
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
                    'L',
                    false,
                    false,
                    0,
                    false,
                    false,
                    false
                );
                $this->Ln(5);   //fontName, size, style

                // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)

                $this->SetFont('dejavusans', '', 10);


                //189 is total width of A4 page, height, border, line,
                $this->MultiCell(189, 3, 'גבארין אבו רפיק', 0, 'R', 0, 1, '', '', true);
                $this->MultiCell(189, 3, 'עבודות בנייה ושיפוצים ע.מ. 203940218', 0, 'R', 0, 1, '', '', true);
                $this->MultiCell(189, 3, 'מעלה עירון-זלפה', 0, 'R', 0, 1, '', '', true);
                $this->MultiCell(189, 3, '0552802837', 0, 'R', 0, 1, '', '', true);
                $this->MultiCell(189, 3, '0524001227', 0, 'R', 0, 1, '', '', true);
                $this->MultiCell(189, 3, 'ת"ד 863', 0, 'R', 0, 1, '', '', true);
                $this->MultiCell(189, 3, 'aborafeekjbareen@gmail.com', 0, 'R', 0, 1, '', '', true);

                // Set font for the title
                $this->SetFont('dejavusans', 'B', 12);

                // Add space after header information
                $this->Ln(12);
                $this->isHeaderAdded = true; // Set the flag to indicate that the header has been added

            }
        }

        public function Footer()
        {
            // Set position from bottom
            $this->SetY(-25);
            $this->Ln(10);
            // Set font for the footer
            $this->SetFont('dejavusans', 'B', 10);

            // Add page number
            $this->Cell(0, 10, 'דף ' . $this->getAliasNumPage() . ' מתוך ' . $this->getAliasNbPages(), 0, 0, 'C');
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
    $pdf->SetTitle('הצעת מחיר');
    $pdf->SetSubject('');
    $pdf->SetKeywords('');
    $pdf->setRTL(true);

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


    $pdf->Ln(28); // Add space


    // Add content to the PDF


    // Date
    $pdf->SetTextColor(44, 86, 122);
    $pdf->SetFont('dejavusans', 'B', 12);
    $formattedBidDate = date('d/m/Y', strtotime($bidDate));
    $pdf->Cell(0, 10, '' . $formattedBidDate, 0, 1, 'R');

    // Recipient Name
    $pdf->SetFont('dejavusans', 'B', 12);
    $pdf->Cell(0, 10, 'לכבוד ' . $forward, 0, 1, 'R');

    $pdf->SetTextColor(0, 0, 0);

    // Address
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(0, 10, '' . $address, 0, 1, 'R');

    // Head of the Letter
    $pdf->Ln(10); // Add space
    $pdf->SetFont('dejavusans', 'B', 12);
    $pdf->Cell(0, 10, 'הנדון: הצעת מחיר', 0, 1, 'C');

    // Body of the Letter
    $pdf->Ln(1);
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->writeHTML($body, true, false, false, false, ''); // Use the $body variable here

    // Price
    if ($includePrice) {

        $formattedPrice = number_format($price, 0, '.', ',');



        // Add price content
        $pdf->Ln(3); // Add space
        $pdf->SetFont('dejavusans', 'B', 12);
        $pdf->Cell(0, 10, 'מחיר סופי כולל מע"מ: ' . $formattedPrice . ' ₪.', 0, 1, 'R');
    }

    // Closing words
    $closingWords = 'נשמח אם תקבלו הצעתנו';
    // Add closing words and signature
    $pdf->Ln(20); // Add space
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(0, 10, $closingWords, 0, 1, 'R');
    $pdf->Cell(0, 10, 'בברכה', 0, 1, 'R');
    $pdf->SetFont('dejavusans', 'B', 12);
    $pdf->Cell(0, 10, 'גבארין אבו רפיק', 0, 1, 'R');
}
// Close and output PDF document
$pdf->Output('bid_pdf.pdf', 'I'); // Display the PDF in the browser