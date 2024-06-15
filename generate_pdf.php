<?php
require 'fpdf/fpdf.php'; // Include FPDF

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 102, 204);
        $this->Cell(0, 10, 'QR Code Lavoratori', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 102, 204);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function AddQRCode($x, $y, $id, $url) {
        $tempDir = 'temp/';
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        $filePath = $tempDir . 'qrcode_' . uniqid() . '.png';

        // Usa un servizio esterno per generare il QR Code
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($url);
        file_put_contents($filePath, file_get_contents($qrCodeUrl));

        // Disegna un bordo attorno al QR code
        $this->SetDrawColor(0, 102, 204);
        $this->Rect($x - 5, $y - 5, 60, 60);

        $this->Image($filePath, $x, $y, 50, 50);
        $this->SetXY($x, $y + 55);
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(50, 10, "ID: $id", 0, 1, 'C');
        $this->Cell(50, 10, "URL: $url", 0, 1, 'C');
    }
}

$pdf = new PDF();
$pdf->SetFont('Arial', '', 12);
$pdf->AddPage();

$conn = new mysqli("localhost", "root", "", "azienda");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

// URL pubblico fornito da ngrok
$publicUrl = 'https://fdc7-79-52-18-241.ngrok-free.app/lavoratori/pdf/';

$sql = "SELECT id FROM lavoratori";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $x = 10;
    $y = 20;
    $counter = 0;
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $url = $publicUrl . $id . '.pdf';
        $pdf->AddQRCode($x, $y, $id, $url);

        $x += 80; // Spostarsi di 80 unità a destra per il prossimo QR code
        $counter++;

        if ($counter % 2 == 0) { // Dopo due QR code, spostarsi in basso e resettare la posizione orizzontale
            $x = 10;
            $y += 100;
        }

        if ($counter % 4 == 0 && $counter < $result->num_rows) { // Dopo quattro QR code, aggiungere una nuova pagina se ci sono più lavoratori
            $pdf->AddPage();
            $x = 10;
            $y = 20;
        }
    }
}

$conn->close();
$pdf->Output('D', 'qrcodes.pdf');
?>
