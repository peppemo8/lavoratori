<?php
require 'fpdf/fpdf.php'; // Include FPDF

class PDF extends FPDF {
    function Header() {
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(0, 102, 204);
        $this->Cell(0, 10, 'Dettagli Lavoratore', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(0, 102, 204);
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo(), 0, 0, 'C');
    }

    function AddEmployeeDetails($data) {
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(0, 0, 0);
        $this->MultiCell(0, 10, $data);
    }

    function AddEmployeePhoto($photo) {
        $this->Image($photo, 10, $this->GetY(), 50, 50);
        $this->Ln(60);
    }
}

$conn = new mysqli("localhost", "root", "", "azienda");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$sql = "SELECT * FROM lavoratori";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pdf = new PDF();
        $pdf->AddPage();

        $data = "Nome: " . $row['nome'] . "\n" .
                "Cognome: " . $row['cognome'] . "\n" .
                "Mansione: " . $row['mansione'] . "\n" .
                "Sesso: " . $row['sesso'] . "\n" .
                "Data di Nascita: " . $row['data_nascita'] . "\n" .
                "Data di Assunzione: " . $row['data_assunzione'];

        $pdf->AddEmployeeDetails($data);
        $pdf->AddEmployeePhoto($row['foto']);

        $filename = 'pdf/' . $row['id'] . '.pdf';
        $pdf->Output('F', $filename);
    }
}

$conn->close();
?>
