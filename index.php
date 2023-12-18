<?php
    include_once "curlget.php";

    $data = fetch_cities();
    $curr_date = date("Y-m-d");

    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_POST['cities'])){
            $sel_city = $_POST['cities'];
        }
        $date_aft = $_POST['date_after'];
        $date_bfr = $_POST['date_before'];

        $pollen_data = fetch_polen($sel_city,$date_aft,$date_bfr);

    }
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Polen</title>

</head>
<body>
    <form action="" method="post">
        <select name="cities" id="cities" class="ml-2 mt-3">
            <?php
            foreach ($data as $item) {
                $id = $item['id'];
                $name = $item['name'];
                echo "<option value='$id'>$name</option>";
            }
            ?>
        </select>
        <p class="mt-2 mb-0 ml-2">Unesite datum od kog zelite da pocnete pretragu</p>
        <input type="date" class="ml-2" value="<?php echo $curr_date ?>" name="date_after"> <!--od npr 2023-11-02 -->
        <p class="mt-2 mb-0 ml-2">Unesite datum do kog zelite da pretrazite</p>
        <input type="date" class="ml-2" value="<?php echo $curr_date ?>" name="date_before"> <!--od npr 2023-11-05 -->
        <button type="submit" class="btn btn-primary">Enter</button>
    </form>
    <div class="container">
        <?php
        if ( isset($pollen_data) && !is_string($pollen_data)){
            foreach ($pollen_data as $date => $allergens){
                echo "<h1>Za datum:$date<br></h1>";
                foreach ($allergens as $allergen){
                    echo "<h2>Allergen: {$allergen['name']}<br></h2>";
                    echo "<h2>Trenutno stanje polena je {$allergen['allergenicity_display_serbian']}.</h2>";
                }
                echo "<hr>";
            }

        }
        ?>
    </div>

</body>
</html>
