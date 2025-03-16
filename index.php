<?php
$host = '';
$dbname = 'kilometrage';
$username = '';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer la liste des véhicules
$vehicules = $pdo->query("SELECT * FROM vehicules")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les relevés de kilométrage pour un véhicule donné
$kilometrages = [];
$vehicule_id = $_POST['vehicule_id'] ?? null;
if ($vehicule_id) {
    $stmt = $pdo->prepare("SELECT date_releve, kilometrage FROM kilometrage WHERE vehicule_id = ? ORDER BY date_releve ASC");
    $stmt->execute([$vehicule_id]);
    $kilometrages = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Trouver le kilométrage estimé à une date donnée
function trouverKilometrage($pdo, $vehicule_id, $date)
{
    $stmt = $pdo->prepare("SELECT * FROM kilometrage WHERE vehicule_id = ? AND date_releve <= ? ORDER BY date_releve DESC LIMIT 1");
    $stmt->execute([$vehicule_id, $date]);
    $releve_avant = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare("SELECT * FROM kilometrage WHERE vehicule_id = ? AND date_releve >= ? ORDER BY date_releve ASC LIMIT 1");
    $stmt->execute([$vehicule_id, $date]);
    $releve_apres = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($releve_avant && $releve_avant['date_releve'] == $date)
        return $releve_avant['kilometrage'];
    if ($releve_apres && $releve_apres['date_releve'] == $date)
        return $releve_apres['kilometrage'];

    if (!$releve_avant)
        return $releve_apres['kilometrage'] ?? null;
    if (!$releve_apres)
        return $releve_avant['kilometrage'] ?? null;

    $date_avant = strtotime($releve_avant['date_releve']);
    $date_apres = strtotime($releve_apres['date_releve']);
    $date_donnee = strtotime($date);

    $km_avant = $releve_avant['kilometrage'];
    $km_apres = $releve_apres['kilometrage'];

    return round($km_avant + (($km_apres - $km_avant) * ($date_donnee - $date_avant) / ($date_apres - $date_avant)));
}

$kilometrage_estime = null;
if (isset($_POST['date'])) {
    $date_demandee = $_POST['date'];
    $kilometrage_estime = trouverKilometrage($pdo, $vehicule_id, $date_demandee);
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi Kilométrage</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="mt-4">Suivi du kilométrage</h2>

        <form method="post" class="my-3">
            <label for="vehicule_id" class="form-label">Véhicule :</label>
            <select name="vehicule_id" class="form-select" required>
                <option value="">-- Sélectionner --</option>
                <?php foreach ($vehicules as $vehicule) { ?>
                    <option value="<?= $vehicule['id'] ?>" <?= ($vehicule_id == $vehicule['id']) ? 'selected' : '' ?>>
                        <?= $vehicule['marque'] . " " . $vehicule['modele'] ?>
                    </option>
                <?php } ?>
            </select>

            <label for="date" class="form-label mt-2">Date :</label>
            <input type="date" name="date" class="form-control" required>

            <button type="submit" class="btn btn-primary mt-3">Rechercher</button>
        </form>

        <?php if ($kilometrage_estime !== null) { ?>
            <div class="alert alert-success">
                Kilométrage estimé au <?= htmlspecialchars($date_demandee) ?> : <strong><?= $kilometrage_estime ?>
                    km</strong>
            </div>
        <?php } ?>

        <h3 class="mt-4">Historique des relevés</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Kilométrage</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kilometrages as $k) { ?>
                    <tr>
                        <td><?= $k['date_releve'] ?></td>
                        <td><?= $k['kilometrage'] ?> km</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <h3 class="mt-4">Évolution du kilométrage</h3>
        <canvas id="kmChart"></canvas>
    </div>

    <script>
        const labels = <?= json_encode(array_column($kilometrages, 'date_releve')) ?>;
        const data = <?= json_encode(array_column($kilometrages, 'kilometrage')) ?>;

        const ctx = document.getElementById('kmChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kilométrage',
                    data: data,
                    borderColor: 'blue',
                    fill: false
                }]
            }
        });
    </script>
</body>

</html>