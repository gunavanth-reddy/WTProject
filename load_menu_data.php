<?php
session_start();
include 'db_connect.php';

// Function to load menu data into session
function loadMenuDataIntoSession($conn) {
    // Query to get all menu items with their availability
    $query = "SELECT id, item_name, category, price, availability FROM menu ORDER BY category, item_name";
    $result = $conn->query($query);

    if (!$result) {
        return false;
    }

    // Format the data for JavaScript
    $foodItems = [];
    $drinkItems = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Define image paths based on item names
            $imagePath = "";
            switch($row['item_name']) {
                case 'Butter Chicken':
                    $imagePath = "https://www.samaheats.com/wp-content/uploads/2024/03/Untitled-design-15-500x500.png";
                    break;
                case 'Paneer Tikka':
                    $imagePath = "https://kannanskitchen.com/wp-content/uploads/2023/01/DSC_4737-500x500.jpg";
                    break;
                case 'Garlic Naan':
                    $imagePath = "https://zestfulkitchen.com/wp-content/uploads/2020/01/garlic-naan-hero_for-web-4-683x1024.jpg";
                    break;
                case 'Chicken Biryani':
                    $imagePath = "https://www.licious.in/blog/wp-content/uploads/2022/06/chicken-hyderabadi-biryani-01.jpg";
                    break;
                case 'Dal Makhani':
                    $imagePath = "https://palatesdesire.com/wp-content/uploads/2018/12/Dal_Makhani-scaled.jpg";
                    break;
                case 'Chole Bhature':
                    $imagePath = "https://media.vogue.in/wp-content/uploads/2020/08/chole-bhature-recipe.jpg";
                    break;
                case 'Palak Paneer':
                    $imagePath = "https://www.vegrecipesofindia.com/wp-content/uploads/2021/06/palak-paneer-3.jpg";
                    break;
                case 'Masala Dosa':
                    $imagePath = "https://vismaifood.com/storage/app/uploads/public/8b4/19e/427/thumb__700_0_0_0_auto.jpg";
                    break;
                case 'Masala Chai':
                    $imagePath = "https://www.teacupsfull.com/cdn/shop/articles/Screenshot_2023-10-20_at_11.07.13_AM.png?v=1697780292";
                    break;
                case 'Mango Lassi':
                    $imagePath = "https://cakewhiz.com/wp-content/uploads/2022/05/Easy-Indian-Mango-Lassi.jpg";
                    break;
                case 'Sweet Lassi':
                    $imagePath = "https://pipingpotcurry.com/wp-content/uploads/2021/05/Lassi-in-a-glass.jpg";
                    break;
                case 'Badam Milk':
                    $imagePath = "https://www.indianveggiedelight.com/wp-content/uploads/2023/04/badam-milk-featured.jpg";
                    break;
                case 'Thandai':
                    $imagePath = "https://masalaandchai.com/wp-content/uploads/2021/03/Thandai.jpg";
                    break;
                case 'Coffee':
                    $imagePath = "https://images.pexels.com/photos/312418/pexels-photo-312418.jpeg?cs=srgb&dl=pexels-chevanon-312418.jpg&fm=jpg";
                    break;
                default:
                    $imagePath = "https://via.placeholder.com/250x180?text=Image+Not+Available";
            }

            $item = [
                'id' => $row['id'],
                'name' => $row['item_name'],
                'price' => (float)$row['price'],
                'availability' => $row['availability'] == 1 ? true : false,
                'image' => $imagePath
            ];
            
            // Separate food and drink items
            if (strtolower($row['category']) == 'food') {
                $foodItems[] = $item;
            } else {
                $drinkItems[] = $item;
            }
        }
    }

    // Store in session
    $_SESSION['foodItems'] = $foodItems;
    $_SESSION['drinkItems'] = $drinkItems;
    $_SESSION['menu_last_updated'] = time();
    
    return true;
}

// Load menu data into session
loadMenuDataIntoSession($conn);

// If this script was called directly, redirect back to the referring page
if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    header("Location: order.php");
}
exit;
?>
