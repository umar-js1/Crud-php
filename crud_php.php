<?php
$conn = mysqli_connect("localhost", "root", "", "test");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = 0;
$name = "";
$email = "";
$phone = "";
$dob = "";
$editMode = false; 


if (isset($_POST['create'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];

    $sql = "INSERT INTO students (name, email, phone, dob) VALUES ('$name', '$email', '$phone', '$dob')";

    if (mysqli_query($conn, $sql)) {
        echo "<p>Student record created successfully.</p>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
    }
}


elseif (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $editMode = true;


    $sql = "SELECT * FROM students WHERE id=$id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $email = $row['email'];
        $phone = $row['phone'];
        $dob = $row['dob'];
    }
}


if (isset($_POST['update']) || isset($_GET['save']) ) {
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
}

    $sql = "UPDATE students SET name='$name',
 email='$email', phone='$phone', dob='$dob' WHERE id=$id";

    if (mysqli_query($conn, $sql)) {

     $editMode = false;
	
	
    } else {
        echo "<p>Error updating record: " . mysqli_error($conn) . "</p>";
    }
}


elseif (isset($_GET['delete'])) {
    $id = $_GET['delete'];


    $sql = "DELETE FROM students WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        echo "<p>Student record deleted successfully.</p>";
    } else {
        echo "<p>Error deleting record: " . mysqli_error($conn) . "</p>";
    }
}


$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    
</head>
<body>
    <h1>Student Management System</h1>

    <!-- Form for creating and updating a student record -->
    <h2><?php echo ($editMode) ? 'Edit Student Record' :
 'Create Student Record'; ?></h2>
    <form method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" 
value="<?php if ($editMode && $id == $row["id"]) {
echo $row['name'];
} ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="
 <?php if ($editMode && $id == $row["id"]) {
echo $row['email'];
} ?>"


 required><br>
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php if ($editMode && $id == $row["id"]) {
echo $row['phone'];
} ?>" required><br>
        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php if ($editMode && $id == $row["id"]) {
echo $row['dob'];
} ?>" required><br>
        <?php if ($editMode) : ?>
            <input type="submit" name="update" value="Update">
            
        <?php else : ?>
            <input type="submit" name="create" value="Create">
        <?php endif; ?>
    </form>

    <!-- Table to display student records with edit and delete options -->
    <h2>Student Records</h2>
    <?php if (mysqli_num_rows($result) > 0) : ?>
        <table border='1'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date of Birth</th>
                <th>Action</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr <?php echo ($editMode && $id == $row["id"]) ? 'class="edit-mode"' : ''; ?>>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo ($editMode && $id == 
$row["id"]) ? '<input type="text" name="name" value="' . $row["name"] . '">' : $row["name"]; ?></td>
                    <td><?php echo ($editMode && $id ==
 $row["id"]) ? '<input type="text" name="email"


 value="' . $row["email"] . '">' : $row["email"]; ?></td>
                    <td><?php echo ($editMode && $id == $row["id"]) ? '<input type="text" name="phone" value="' . $row["phone"] . '">' : $row["phone"]; ?></td>
                    <td><?php echo ($editMode && $id == $row["id"])
 ? '<input type="date" name="dob" value="' . $row["dob"] . '">' : $row["dob"]; ?></td>
                    <td>
                        <?php if ($editMode && $id == $row["id"]) : ?>
                        
 			<a href="?save=<?php 
			echo $row["id"]; ?>">

 			<button type="button" >

				save
				</button></a>



   
                        <?php else : ?>
                            <a href="?edit=<?php 
echo $row["id"]; ?>">Edit</a>
                            <a href="?delete=<?php echo $row["id"]; ?>">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else : ?>
        <p>No student records found.</p>
 <?php endif; ?>
</body>