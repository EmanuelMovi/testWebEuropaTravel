<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    $firstName = trim($_POST['firstName'] ?? '');
    $lastName = trim($_POST['lastName'] ?? '');
    $wantsToBeContacted = $_POST['wantsToBeContacted'] ?? 'nu';
    $phoneNumber = trim($_POST['phoneNumber'] ?? '');

    if (empty($firstName)) {
        $errors[] = "First name is required.";
    }

    if (empty($lastName)) {
        $errors[] = "Last name is required.";
    }

    if ($wantsToBeContacted === 'da' && empty($phoneNumber)) {
        $errors[] = "Phone number is required if user wants to be contacted.";
    }

    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Form Result</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; padding: 40px; }
        .card { background: white; padding: 25px; border-radius: 8px; max-width: 500px; margin: auto; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .success { color: #155724; background: #d4edda; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .error { color: #721c24; background: #f8d7da; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        a { text-decoration: none; color: #007bff; margin-top: 20px; display: inline-block; }
        ul { padding-left: 20px; }
    </style>
    </head><body><div class='card'>";

    if (!empty($errors)) {
        echo "<div class='error'><strong>Form Errors:</strong><ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul></div><a href='index.html'>&larr; Back to form</a>";
    } else {
        $data = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'wantsToBeContacted' => $wantsToBeContacted,
            'phoneNumber' => $wantsToBeContacted === 'da' ? $phoneNumber : '',
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $file = 'submissions.json';
        $existing = [];

        if (file_exists($file)) {
            $json = file_get_contents($file);
            $existing = json_decode($json, true) ?? [];
        }

        $existing[] = $data;
        file_put_contents($file, json_encode($existing, JSON_PRETTY_PRINT));

        echo "<div class='success'>✅ Multumim pentru completare!</div>
              <ul>
                <li><strong>First Name:</strong> " . htmlspecialchars($firstName) . "</li>
                <li><strong>Last Name:</strong> " . htmlspecialchars($lastName) . "</li>
                <li><strong>Wants to be contacted:</strong> " . htmlspecialchars($wantsToBeContacted) . "</li>";
        if ($wantsToBeContacted === 'da') {
            echo "<li><strong>Phone Number:</strong> " . htmlspecialchars($phoneNumber) . "</li>";
        }
        echo "</ul><a href='index.html'>Trimite alt formular</a>";
    }

    echo "</div></body></html>";
}
?>
