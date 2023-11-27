<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../styles.css">
    <title>Hamming Distance Calculator</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@3.9.4/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>

<br><br>
<h1 class="font-bold text-5xl">Hamming Distance Calculator</h1>
<br><br>
<form method="post" action="">
    <label for="input1">Input DNA String 1:</label>
    <br>
    <input class="input input-ghost w-full max-w-xs" type="text" name="input1" id="input1" value="<?= $input1 ?>" required>
    <br><br>
    <label for="input2">Input DNA String 2:</label>
    <br>
    <input class="input input-ghost w-full max-w-xs" type="text" name="input2" id="input2" value="<?= $input2 ?>" required>
    <br><br>
    <input type="submit" class="btn" name="calculate" value="Calculate Hamming Distance">
</form>
<br><br>
<?php if (!empty($result)) : ?>
<p class="text-3xl">Result: <?= $result ?></p>
<?php endif; ?>
</body>
</html>
