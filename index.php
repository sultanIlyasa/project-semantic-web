<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c3c1353c4c.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- Connector untuk menghubungkan PHP dan SPARQL -->
    <?php
    require_once("sparqllib.php");
    $test = "";
    if (isset($_POST['search'])) {
        $test = $_POST['search'];
        $data = sparql_get(
            "http://localhost:3030/TeamSepakBola",
            "
                prefix id: <https://EuropeFootballClub.com/> .
                prefix data: <https://EuropeFootballClub.com/ns/data#> .
                prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
                
                SELECT ?clubname ?foundationyear ?city ?manager
                WHERE
                { 
                    ?team
                    data:clubname       ?clubname ;
                    data:foundationyear   ?foundationyear ;
                    data:city           ?city ;
                    data:manager        ?manager .
                
                    FILTER 
                    (regex (?clubname, '$test', 'i') 
                    || regex (?foundationyear, '$test', 'i') 
                    || regex (?city, '$test', 'i') 
                    || regex (?manager, '$test', 'i'))
                    }"
        );
    } else {
        $data = sparql_get(
            "http://localhost:3030/TeamSepakBola",
            "
            prefix id: <https://EuropeFootballClub.com/> .
            prefix data: <https://EuropeFootballClub.com/ns/data#> .
            prefix rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
            
            SELECT ?clubname ?foundationyear ?city ?manager
            WHERE
                { 
                    ?team
                    data:clubname       ?clubname ;
                    data:foundationyear   ?foundationyear ;
                    data:city           ?city ;
                    data:manager        ?manager .
                
                }
            "
        );
    }

    if (!isset($data)) {
        print "<p>Error: " . sparql_errno() . ": " . sparql_error() . "</p>";
    }
    ?>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container container-fluid">
            <a class="navbar-brand" href="index.php"><img src="src/img/logo-pssi.png" style="width:80px" alt="Logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 h5">
                    <li class="nav-item px-2">
                        <a class="nav-link active text-black" aria-current="page" href="#">FIFA</a>
                    </li>
                </ul>
                <form class="d-flex" role="search" action="" method="post" id="nameform">
                    <input class="form-control me-2" type="search" placeholder="Ketik keyword disini" aria-label="Search" name="search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container container-fluid mt-3  ">
        <i class="fa-solid fa-magnifying-glass"></i><span>Menampilkan hasil pencarian untuk Team Sepak Bola "<?php echo $test; ?>"</span>
        <table class="table table-bordered table-striped table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Club Name</th>
                    <th>Founded</th>
                    <th>City</th>
                    <th>Manager</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                <?php foreach ($data as $dat) : ?>
                    <tr>
                        <td><?= ++$i ?></td>
                        <td><?= $dat['clubname'] ?></td>
                        <td><?= $dat['foundationyear'] ?></td>
                        <td><?= $dat['city'] ?></td>
                        <td><?= $dat['manager'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>

</html>