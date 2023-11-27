<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resistenza</title>
</head>
<body>
<form action="resistenza.php" method="post">
    <select name="prima_banda">
        <?php foreach ($colori as $colore):?>
            <option><?=$colore?></option>
        <?php endforeach;?>
    </select>

    <select name="seconda_banda">
        <?php foreach ($colori as $colore):?>
        <option><?=$colore?></option>
        <?php endforeach;?>
    </select>

    <select name="terza_banda">
        <?php foreach ($colori as $colore):?>
        <option><?=$colore?></option>
        <?php endforeach;?>
    </select>

    <select name="quarta_banda">
        <?php foreach ($colori as $colore):?>
        <option><?=$colore?></option>
        <?php endforeach;?>
    </select>
    <button type="submit">Calcola</button>
</form>
<p>Valore: <?=$risultato?></p>
</body>
</html>