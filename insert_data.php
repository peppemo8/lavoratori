<?php
$conn = new mysqli("localhost", "root", "", "azienda");

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $conn->real_escape_string($_POST['nome']);
    $cognome = $conn->real_escape_string($_POST['cognome']);
    $mansione = $conn->real_escape_string($_POST['mansione']);
    $sesso = $conn->real_escape_string($_POST['sesso']);
    $data_nascita = $conn->real_escape_string($_POST['data_nascita']);
    $data_assunzione = $conn->real_escape_string($_POST['data_assunzione']);
    $foto = $_FILES['foto']['name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($foto);
    move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);

    $sql = "INSERT INTO lavoratori (nome, cognome, mansione, sesso, data_nascita, data_assunzione, foto)
            VALUES ('$nome', '$cognome', '$mansione', '$sesso', '$data_nascita', '$data_assunzione', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "Dati inseriti con successo";
    } else {
        echo "Errore: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Nessun dato inserito.";
}

$conn->close();
?>
