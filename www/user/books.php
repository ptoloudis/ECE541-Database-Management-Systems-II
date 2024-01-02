<?php
session_start();
if ($_SESSION['admin'] != '0' && $_SESSION['admin'] != '1') {
    header("Location: ../index.php");
    exit();
}

require_once '../db.php';

$userId = $_SESSION['user_id'];

// Pagination settings for rented books
$rentedBooksPerPage = 5;
$currentPageRented = isset($_GET['page_rented']) ? $_GET['page_rented'] : 1;
$startFromRented = ($currentPageRented - 1) * $rentedBooksPerPage;

// Fetch user's rented books with pagination
$sqlRented = "SELECT Book.title, Book.author, Booking.booked_date, Booking.expiration_date FROM Booking INNER JOIN Book ON Booking.book_id = Book.id WHERE Booking.user_id = $userId LIMIT $startFromRented, $rentedBooksPerPage";
$resultRented = $conn->query($sqlRented);

// Count total number of rented books
$sqlCountRented = "SELECT COUNT(*) AS total FROM Booking WHERE user_id = $userId";
$resultCountRented = $conn->query($sqlCountRented);
$rowRented = $resultCountRented->fetch_assoc();
$totalRentedBooks = $rowRented['total'];
$totalPagesRented = ceil($totalRentedBooks / $rentedBooksPerPage);

// Pagination settings for available books
$availableBooksPerPage = 5;
$currentPageAvailable = isset($_GET['page_available']) ? $_GET['page_available'] : 1;
$startFromAvailable = ($currentPageAvailable - 1) * $availableBooksPerPage;

// Fetch all available books with pagination
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sqlAvailable = "SELECT * FROM Book WHERE title LIKE '%$search%' LIMIT $startFromAvailable, $availableBooksPerPage";
$resultAvailable = $conn->query($sqlAvailable);

// Count total number of available books
$sqlCountAvailable = "SELECT COUNT(id) AS total FROM Book WHERE title LIKE '%$search%'";
$resultCountAvailable = $conn->query($sqlCountAvailable);
$rowAvailable = $resultCountAvailable->fetch_assoc();
$totalAvailableBooks = $rowAvailable['total'];
$totalPagesAvailable = ceil($totalAvailableBooks / $availableBooksPerPage);

// Include your CSS file
echo '<link rel="stylesheet" type="text/css" href="../style.css">';

// Function to generate pagination links
function generatePagination($currentPage, $totalPages, $pageType) {
    $maxPagesToShow = 5; // Max number of pages to show in the pagination
    $pagesOffset = floor($maxPagesToShow / 2);
    $startPage = max(1, $currentPage - $pagesOffset);
    $endPage = min($totalPages, $currentPage + $pagesOffset);

    // Previous button
    if ($currentPage > 1) {
        echo '<a href="books.php?page_' . $pageType . '=' . ($currentPage - 1) . '">&laquo; Previous</a> ';
    }

    // Page links
    for ($page = $startPage; $page <= $endPage; $page++) {
        echo '<a href="books.php?page_' . $pageType . '=' . $page . '" ' . ($page == $currentPage ? 'class="active"' : '') . '>' . $page . '</a> ';
    }

    // Next button
    if ($currentPage < $totalPages) {
        echo '<a href="books.php?page_' . $pageType . '=' . ($currentPage + 1) . '">Next &raquo;</a> ';
    }
}

echo "<div class='container'>"; 
echo "<div class='logout-container'>";
echo "<a href='../logout.php' class='logout-button'>Logout</a>";
echo "</div>";

echo "<h2>Your Rented Books</h2>";
if ($resultRented->num_rows > 0) {
    echo "<table><tr><th>Title</th><th>Author</th><th>Rented On</th><th>Due By</th></tr>";
    while($row = $resultRented->fetch_assoc()) {
        echo "<tr><td>".$row["title"]."</td><td>".$row["author"]."</td><td>".$row["booked_date"]."</td><td>".$row["expiration_date"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "You have no rented books.<br>";
}

// Pagination for rented books
echo "<div class='pagination'>";
generatePagination($currentPageRented, $totalPagesRented, 'rented');
echo "</div>";

echo "</div>";
echo "<div class='container'>"; 

echo "<h2>Available Books</h2>";

echo "<form method='get' action=''>
        <input type='text' name='search' placeholder='Search by book title...'>
        <input type='submit' value='Search'>
      </form>";

if ($resultAvailable->num_rows > 0) {
    echo "<table><tr><th>Title</th><th>Author</th><th>Description</th><th>Publisher</th><th>Genres</th><th>Quantity</th><th>Action</th></tr>";
    while($row = $resultAvailable->fetch_assoc()) {
        $shortDescription = strlen($row["description"]) > 50 ? substr($row["description"], 0, 50) . "..." : $row["description"];
        echo "<tr>
                <td>".$row["title"]."</td>
                <td>".$row["author"]."</td>
                <td class='tooltip'>".$shortDescription."
                    <span class='tooltiptext'>".$row["description"]."</span>
                </td>
                <td>".$row["publisher"]."</td>
                <td>".$row["genres"]."</td>
                <td id='quantity_" . $row["id"] . "'>" . $row["quantity"] . "</td>
                <td><button onclick='rentBook(" . $row["id"] . ")' class='rent-button'>Rent</button></td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No books available at the moment.<br>";
}

// Pagination for available books
echo "<div class='pagination'>";
generatePagination($currentPageAvailable, $totalPagesAvailable, 'available');
echo "</div>";

echo "</div>"; // Close container

closeDb($conn);
?>

<script>
function rentBook(bookId) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = JSON.parse(this.responseText);
            alert(response.message);
            if (response.status === "success") {
                // Decrease the quantity in the HTML
                var quantityElement = document.getElementById('quantity_' + bookId);
                var currentQuantity = parseInt(quantityElement.innerHTML);
                if (currentQuantity > 0) {
                    quantityElement.innerHTML = currentQuantity - 1;
                }
            }
        }
    };
    xhttp.open("GET", "rent_book.php?book_id=" + bookId, true);
    xhttp.send();
}
</script>
