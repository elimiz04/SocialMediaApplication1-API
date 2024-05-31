<?php
// Example: Fetch images from a database
$images = array(
    "image1.jpg",
    "image2.jpg",
    "image3.jpg",
    "image4.jpg",
    "image5.jpg",
    "image6.jpg"
);

foreach ($images as $image) {
    echo "<form action='post.php' method='post'>";
    echo "<button type='submit' name='image' value='../home.php/$image'>";
    echo "<img src='../home.php/$image' alt='Image'>";
    echo "</button>";
    echo "</form>";
}
?>
