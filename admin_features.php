<?php
session_start();

// Check if user is logged in
if (isset($_SESSION["user_id"])) {
    require_once __DIR__ . "/database.php";

    $stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Fetch all accounts (admin-only)
$query_accounts = "SELECT id, name, email, user_type FROM user";
$result_accounts = $mysqli->query($query_accounts);

if (!$result_accounts) {
    die("Database query failed: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - User Management</title>
    <link rel="stylesheet" href="homepage.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <div class="header d-flex justify-content-between align-items-center p-3">
        <?php if (isset($user)): ?>
            <h2>Welcome, <?= htmlspecialchars($user["name"]) ?></h2>
        <?php endif; ?>
        <div>
            <a href="home.php" class="btn btn-secondary">Home</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <div class="container mt-4">

    <!-- Success/Failure Alerts -->
        <?php if (isset($_GET['updated'])): ?>
            <?php if ($_GET['updated'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    User role updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php else: ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Failed to update user role.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($_GET['deleted'])): ?>
            <?php if ($_GET['deleted'] == 1): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    User deleted successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php else: ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Failed to delete user.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if (isset($user) && $user['user_type'] === 'admin'): ?>
            <h2>All Accounts
                <button class="btn btn-success mb-3" style="float:right" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fa fa-user-plus"></i> Add Account
                </button>
            </h2>

            <?php if (isset($_GET['added'])): ?>
                <div class="alert alert-success">User added successfully!</div>
            <?php endif; ?>

            <!-- Search/filter input -->
            <input class="form-control mb-3" id="searchInput" type="text" placeholder="Search by username, email, or type..." />

            <table class="table table-striped" id="userTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_accounts->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['id']) ?></td>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['email']) ?></td>
                            <td><?= htmlspecialchars($row['user_type']) ?></td>
                            <td>
                                <button
                                    class="btn btn-sm btn-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="<?= $row['id'] ?>"
                                    data-name="<?= $row['name'] ?>"
                                    data-email="<?= $row['email'] ?>"
                                    data-type="<?= $row['user_type'] ?>"
                                >
                                    Edit User Type
                                </button>
                                <a href="delete_user.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Edit User Role Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="update_user_role.php" method="post">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit User Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit-id" />
                                <p><strong>Username:</strong> <span id="edit-name"></span></p>
                                <p><strong>Email:</strong> <span id="edit-email"></span></p>
                                <div class="form-group">
                                    <label for="user_type">User Type</label>
                                    <select class="form-control" name="user_type" id="edit-type">
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add User Modal -->
            <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="admin_create_account.php" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addUserModalLabel">Create New Account</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" name="username" id="username" required />
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email (must be @frostburg.edu)</label>
                                    <input type="email" class="form-control" name="email" id="email" required />
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password (min. 8 characters)</label>
                                    <input type="password" class="form-control" name="password" id="password" required />
                                </div>

                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" name="confirm_password" id="confirm_password" required />
                                </div>

                                <div class="mb-3">
                                    <label for="user_type" class="form-label">User Type</label>
                                    <select class="form-select" name="user_type" id="user_type" required>
                                        <option value="user">User</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p class="text-danger">You do not have permission to view this page.</p>
        <?php endif; ?>
    </div>

    <div class="footer text-center mt-5">
        <p>Website Created by - Nathan Murphy | <i class="fa fa-phone"></i> 240-457-3326 | <i class="fa fa-envelope"></i> Nathanmurphy0507@gmail.com</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search functionality
        document.getElementById("searchInput").addEventListener("keyup", function () {
            const filter = this.value.toLowerCase();
            const rows = document.querySelectorAll("#userTable tbody tr");
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });

        // Populate edit modal with user data
        var editModal = document.getElementById("editModal");
        editModal.addEventListener("show.bs.modal", function (event) {
            var button = event.relatedTarget;
            document.getElementById("edit-id").value = button.getAttribute("data-id");
            document.getElementById("edit-name").textContent = button.getAttribute("data-name");
            document.getElementById("edit-email").textContent = button.getAttribute("data-email");
            document.getElementById("edit-type").value = button.getAttribute("data-type");
        });
    </script>
</body>
</html>
