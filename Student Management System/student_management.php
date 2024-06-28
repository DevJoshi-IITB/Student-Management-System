<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "student_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS student_management");
$conn->query("CREATE TABLE IF NOT EXISTS `student_management`.`students` (`id` INT(50) NOT NULL AUTO_INCREMENT , `name` VARCHAR(100) NOT NULL , `roll_number` VARCHAR(50) NOT NULL , `department` VARCHAR(100) NOT NULL , `hostel` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB");



if (isset($_POST['add_student'])) {
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $department = $_POST['department'];
    $hostel = $_POST['hostel'];

    $sql = "INSERT INTO students (name, roll_number, department, hostel) VALUES ('$name', '$roll_number', '$department', '$hostel')";
    $conn->query($sql);
    header('Location: student_management.php');
}

if (isset($_POST['edit_student'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $department = $_POST['department'];
    $hostel = $_POST['hostel'];

    $sql = "UPDATE students SET name='$name', roll_number='$roll_number', department='$department', hostel='$hostel' WHERE id=$id";
    $conn->query($sql);
    header('Location: student_management.php');
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM students WHERE id=$id";
    $conn->query($sql);
    header('Location: student_management.php');
}

$students = $conn->query("SELECT * FROM students");

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <title>Student Information Management</title>
</head>

<body>
    <div class="container">
        <header>
            <h2>Student Information Management</h2>
        </header>

        <div class="search-container">
            <input type="text" id="searchName" class="search-input" placeholder="Search by Name" onkeyup="searchTable()">
            <input type="text" id="searchRollNumber" class="search-input" placeholder="Search by Roll Number" onkeyup="searchTable()">
            <input type="text" id="searchDepartment" class="search-input" placeholder="Search by Department" onkeyup="searchTable()">
            <input type="text" id="searchHostel" class="search-input" placeholder="Search by Hostel" onkeyup="searchTable()">
        </div>
        <table class="table" id="studentsTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Roll Number</th>
                    <th>Department</th>
                    <th>Hostel</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $students->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['roll_number']; ?></td>
                        <td><?php echo $row['department']; ?></td>
                        <td><?php echo $row['hostel']; ?></td>
                        <td>
                            <button class="button" onclick='editStudent(<?php echo json_encode($row); ?>)'>Edit</button>
                            <a href="student_management.php?delete=<?php echo $row['id']; ?>" class="btn ">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div>
            <button class="button" onclick="document.getElementById('addStudentForm').style.display='block'">Add New Student</button>
        </div>
    </div>


    <!-- Add Student Form -->
    <div id="addStudentForm" class="modal">
        <div class="modal-content">
            <header class="modal-header">
                New Student
                <button class="button " onclick="document.getElementById('addStudentForm').style.display='none'">&times;</button>
            </header>
            <form method="POST" action="student_management.php">
                <div class="form-control">
                    <label>Name</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-control">
                    <label>Roll Number</label>
                    <input type="text" name="roll_number" class="form-input" required>
                </div>
                <div class="form-control">
                    <label>Department</label>
                    <input type="text" name="department" class="form-input" required>
                </div>
                <div class="form-control">
                    <label>Hostel</label>
                    <input type="text" name="hostel" class="form-input" required>
                </div>
                <button type="submit" name="add_student" class="button">Add Student</button>
                <button class="button cls" onclick="document.getElementById('addStudentForm').style.display='none'">Close</button>
            </form>
        </div>
    </div>

    <!-- Edit Student Form -->
    <div id="editStudentForm" class="modal">
        <div class="modal-content">
            <header class="modal-header">
                Update Student
                <button class="button" onclick="document.getElementById('editStudentForm').style.display='none'">&times;</button>
            </header>
            <form method="POST" action="student_management.php">
                <input type="hidden" name="id" id="edit-id">
                <div class="form-control">
                    <label>Name</label>
                    <input type="text" name="name" id="edit-name" class="form-input" required>
                </div>
                <div class="form-control">
                    <label>Roll Number</label>
                    <input type="text" name="roll_number" id="edit-roll_number" class="form-input" required>
                </div>
                <div class="form-control">
                    <label>Department</label>
                    <input type="text" name="department" id="edit-department" class="form-input" required>
                </div>
                <div class="form-control">
                    <label>Hostel</label>
                    <input type="text" name="hostel" id="edit-hostel" class="form-input" required>
                </div>
                <button type="submit" name="edit_student" class="button">Update Student</button>
                <button class="button cls" onclick="document.getElementById('editStudentForm').style.display='none'">Close</button>

            </form>
        </div>


    </div>



    <script>
        function editStudent(student) {
            document.getElementById('edit-id').value = student.id;
            document.getElementById('edit-name').value = student.name;
            document.getElementById('edit-roll_number').value = student.roll_number;
            document.getElementById('edit-department').value = student.department;
            document.getElementById('edit-hostel').value = student.hostel;
            document.getElementById('editStudentForm').style.display = 'block';
        }

        function searchTable() {
            var nameFilter, rollNumberFilter, departmentFilter, hostelFilter, table, tr, td, i;
            nameFilter = document.getElementById("searchName").value.toUpperCase();
            rollNumberFilter = document.getElementById("searchRollNumber").value.toUpperCase();
            departmentFilter = document.getElementById("searchDepartment").value.toUpperCase();
            hostelFilter = document.getElementById("searchHostel").value.toUpperCase();
            table = document.getElementById("studentsTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                if (td) {
                    nameTxtValue = td[0].textContent || td[0].innerText;
                    rollNumberTxtValue = td[1].textContent || td[1].innerText;
                    departmentTxtValue = td[2].textContent || td[2].innerText;
                    hostelTxtValue = td[3].textContent || td[3].innerText;
                    if (nameTxtValue.toUpperCase().indexOf(nameFilter) > -1 && rollNumberTxtValue.toUpperCase().indexOf(rollNumberFilter) > -1 &&
                        departmentTxtValue.toUpperCase().indexOf(departmentFilter) > -1 && hostelTxtValue.toUpperCase().indexOf(hostelFilter) > -1) {
                        tr[i].style.display = "";
                    }
                }
            }
        }

        document.querySelectorAll('.close').forEach(button => {
            button.onclick = function() {
                this.parentElement.parentElement.style.display = 'none';
            }
        });
    </script>
</body>

</html>