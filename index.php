<?php
$conn = new mysqli("localhost", "root", "", "azienda");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM lavoratori WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<h1>Dettagli Lavoratore</h1>";
        echo "Nome: " . $row['nome'] . "<br>";
        echo "Cognome: " . $row['cognome'] . "<br>";
        echo "Mansione: " . $row['mansione'] . "<br>";
        echo "Sesso: " . $row['sesso'] . "<br>";
        echo "Data di Nascita: " . $row['data_nascita'] . "<br>";
        echo "Data di Assunzione: " . $row['data_assunzione'] . "<br>";
        echo "<img src='" . $row['foto'] . "' alt='" . $row['nome'] . " " . $row['cognome'] . "' style='width:150px;height:150px;'><br>";
    }
} else {
    echo "Nessun lavoratore trovato.";
}

$conn->close();
?>
