<?php
session_start();

$errors = [];
$form_data = [];
$display_data = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_data = $_POST;

    $name = trim($_POST["name"]);
    if (empty($name)) {
        $errors["name"] = "Name is required";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors["name"] = "Only letters and spaces allowed";
    }

    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    if (empty($email)) {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    $facebook = filter_var($_POST["facebook"], FILTER_SANITIZE_URL);
    if (empty($facebook)) {
        $errors["facebook"] = "Facebook URL is required";
    } elseif (!filter_var($facebook, FILTER_VALIDATE_URL)) {
        $errors["facebook"] = "Invalid URL format";
    }

    $password = $_POST["password"];
    if (empty($password)) {
        $errors["password"] = "Password is required";
    } elseif (strlen($password) < 6 || !preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/", $password)) {
        $errors["password"] = "Password must be at least 6 characters and include uppercase, lowercase, and numbers";
    }

    $confirm_password = $_POST["confirm_password"];
    if ($password !== $confirm_password) {
        $errors["confirm_password"] = "Passwords do not match";
    }

    $phone = preg_replace("/[^0-9]/", "", $_POST["phone"]);
    if (empty($phone)) {
        $errors["phone"] = "Phone number is required";
    } elseif (!preg_match("/^(09|\+639)\d{9}$/", $phone)) {
        $errors["phone"] = "Invalid Philippine phone number format. Use 09XXXXXXXXX or +639XXXXXXXXX";
    }

    if (!isset($_POST["gender"])) {
        $errors["gender"] = "Gender is required";
    }

    $country = $_POST["country"];
    if (empty($country)) {
        $errors["country"] = "Country is required";
    }

    if (!isset($_POST["skills"]) || empty($_POST["skills"])) {
        $errors["skills"] = "At least one skill must be selected";
    }

    $biography = trim($_POST["biography"]);
    if (empty($biography)) {
        $errors["biography"] = "Biography is required";
    } elseif (strlen($biography) > 200) {
        $errors["biography"] = "Biography must not exceed 200 characters";
    }

    if (empty($errors)) {
        $display_data = [
            "name" => $name,
            "email" => $email,
            "facebook" => $facebook,
            "phone" => $phone,
            "gender" => $_POST["gender"],
            "country" => $country,
            "skills" => $_POST["skills"],
            "biography" => $biography
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Registration Form</h2>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="facebook" class="form-label">Facebook URL:</label>
                <input type="url" class="form-control" id="facebook" name="facebook" value="<?php echo isset($form_data['facebook']) ? htmlspecialchars($form_data['facebook']) : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number (Philippine format):</label>
                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo isset($form_data['phone']) ? htmlspecialchars($form_data['phone']) : ''; ?>" placeholder="09XXXXXXXXX or +639XXXXXXXXX">
            </div>
            <div class="mb-3">
                <label class="form-label">Gender:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" <?php echo (isset($form_data['gender']) && $form_data['gender'] == 'male') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" <?php echo (isset($form_data['gender']) && $form_data['gender'] == 'female') ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country:</label>
                <select class="form-select" id="country" name="country">
                    <option value="">Select a country</option>
                    <option value="philippines" <?php echo (isset($form_data['country']) && $form_data['country'] == 'philippines') ? 'selected' : ''; ?>>Philippines</option>
                    <option value="usa" <?php echo (isset($form_data['country']) && $form_data['country'] == 'usa') ? 'selected' : ''; ?>>USA</option>
                    <option value="uk" <?php echo (isset($form_data['country']) && $form_data['country'] == 'uk') ? 'selected' : ''; ?>>UK</option>
                    <option value="canada" <?php echo (isset($form_data['country']) && $form_data['country'] == 'canada') ? 'selected' : ''; ?>>Canada</option>
                    <option value="australia" <?php echo (isset($form_data['country']) && $form_data['country'] == 'australia') ? 'selected' : ''; ?>>Australia</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Skills:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" id="dance" value="dance" <?php echo (isset($form_data['skills']) && in_array('dance', $form_data['skills'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="dance">Dance</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" id="singing" value="singing" <?php echo (isset($form_data['skills']) && in_array('singing', $form_data['skills'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="singing">Singing</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" id="act" value="act" <?php echo (isset($form_data['skills']) && in_array('act', $form_data['skills'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="act">Acting</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" id="draw" value="draw" <?php echo (isset($form_data['skills']) && in_array('draw', $form_data['skills'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="draw">Drawing</label>
                </div>
            </div>
            <div class="mb-3">
                <label for="biography" class="form-label">Biography:</label>
                <textarea class="form-control" id="biography" name="biography" rows="3"><?php echo isset($form_data['biography']) ? htmlspecialchars($form_data['biography']) : ''; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>

    <?php if ($display_data): ?>
    <script>
        var displayWindow = window.open('', '_blank');
        displayWindow.document.write(`
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>User Information</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
                <div class="container mt-5">
                    <h2 class="mb-4">User Information</h2>
                    <div class="card">
                        <div class="card-body">
                            <p><strong>Name:</strong> <?php echo htmlspecialchars($display_data["name"]); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($display_data["email"]); ?></p>
                            <p><strong>Facebook URL:</strong> <?php echo htmlspecialchars($display_data["facebook"]); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($display_data["phone"]); ?></p>
                            <p><strong>Gender:</strong> <?php echo htmlspecialchars($display_data["gender"]); ?></p>
                            <p><strong>Country:</strong> <?php echo htmlspecialchars($display_data["country"]); ?></p>
                            <p><strong>Skills:</strong> <?php echo implode(", ", array_map('htmlspecialchars', $display_data["skills"])); ?></p>
                            <p><strong>Biography:</strong> <?php echo htmlspecialchars($display_data["biography"]); ?></p>
                        </div>
                    </div>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"><\/script>
            </body>
            </html>
        `);
        displayWindow.document.close();
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
