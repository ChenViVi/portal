<?php
require_once("config.php");
header('content-type:text/html;charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ViVi的传送门</title>
    <script src="http://code.jquery.com/jquery-latest.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/css/materialize.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.1/js/materialize.min.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.9/css/all.css" integrity="sha384-5SOiIsAziJl6AWe0HWRKTXlfcSHKmYV4RBF18PPJ173Kzn7jzMyFuTtk8JA7QQG1" crossorigin="anonymous">
    <style>
        .searchbar{
            margin:20px auto;
            background-color: rgba(255,255,255,0.65);
            width:60%;
        }
        .tabnav{
            min-height:500px; height:auto!important; height:500px;
            margin:20px auto;
            background-color: rgba(255,255,255,0.55);
            width:80%;
            position: relative;
            top: 20px;
        }
        .website{
            background-color: rgba(255,255,255,0.2);
            padding: 5px;
            border-radius:5px;
        }
    </style>
    <script type="text/javascript">
        $(document).ready(function() {
            $("body").css("background-image","url(" + Math.round(Math.random()*26) + ".jpg)");
            $('.searchbar').on('keydown',function(event){
                if(event.keyCode == 13){
                    window.open ($('input[name=group1]:checked').val() + $("input[name='search_param']").val());
                    $("input[name='search_param']").val("");
                }
            });
            $(".searchbtn").click(function(){
                window.open ($('input[name=group1]:checked').val() + $("input[name='search_param']").val());
                $("input[name='search_param']").val("");
            });
        });
    </script>
</head>
<body style="background-size:cover;">
<div class= "searchbar">
    <div class="row">
        <div class="row col s12" style="position:relative;">
            <div class="input-field col s11" >
                <input name="search_param" type="text" class="validate">
            </div>
            <a class="waves-effect waves-light btn col s1 searchbtn" style="position: absolute;top: 50%;transform: translateY(-50%);"><i class="material-icons">search</i></a>
        </div>
        <div class="row col s12">
            <?php
                $mysqli=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME,$DB_PORT);
                $mysqli->set_charset("utf8");
                $stmt=$mysqli->prepare("select * from search");
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
            <?php while ($row = $result->fetch_assoc()) {?>
                <div class="col s2">
                    <input class="with-gap" name="group1" type="radio" id="radio<?php echo $row['id']; ?>"  value="<?php echo $row['url']; ?>"/>
                    <label for="radio<?php echo $row['id']; ?>"><?php echo $row['name']; ?></label>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<?php if ($ENABLE_FAB){?>
    <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
        <a class="btn-floating btn-large red">
            <i class="fas fa-crosshairs"></i>
        </a>
        <ul>
            <?php
                $stmt=$mysqli->prepare("select * from fab");
                $stmt->execute();
                $result = $stmt->get_result();
            ?>
            <?php while ($row = $result->fetch_assoc()) {?>
                <li><a class="btn-floating <?php echo $row['fab_color'] ?>" style="transform: scaleY(0.4) scaleX(0.4) translateY(40px) translateX(0px); opacity: 0;" target="_blank" href="<?php echo $row['url'] ?>"><i class="<?php echo $row['icon_img'] ?>" style="color:<?php echo $row['icon_color'] ?>;"></i></a></li>
            <?php } ?>
        </ul>
    </div>
<?php } ?>
<div class="tabnav">
    <nav class="nav-extended transparent">
        <div class="nav-content">
            <ul class="tabs pink transparent">
                <?php
                    $stmt=$mysqli->prepare("select * from site_type");
                    $stmt->execute();
                    $result = $stmt->get_result();
                ?>
                <?php while ($row = $result->fetch_assoc()) {?>
                    <li class="tab"><a href="#<?php echo $row['id']; ?>"  class="teal-text"><?php echo $row['name']; ?></a></li>
                <?php } ?>
                <li class="indicator teal" style="right: 186px; left: 68px;"></li>
            </ul>
        </div>
    </nav>
    <?php
        $query="select id, name from site_type";
        $stmt=$mysqli->prepare("select COUNT(1) from site_type");
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count=$row['COUNT(1)'];
    ?>
    <?php for($i=1; $i<=$count ;$i++){ ?>
    <div id="<?php echo $i ?>" class="row" style="margin-top: 20px; display: block;">
        <?php
            $stmt=$mysqli->prepare("select * from site WHERE type_id = ?");
            $stmt->bind_param('i', $i);
            $stmt->execute();
            $result = $stmt->get_result();
        ?>
        <?php while ($row = $result->fetch_assoc()) {?>
            <div class="col s3" style="margin-top: 20px; display: block;">
                <a href="<?php echo $row['url'] ?>" target="_blank">
                    <div class="website hoverable" style="position:relative;">
                        <img src="http://www.google.com/s2/favicons?domain=<?php echo $row['url'] ?>" width="16px" style="position: absolute;top: 50%;transform: translateY(-50%);">
                        <p class="teal-text center"><?php echo $row['name'] ?></p>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
    <?php }mysqli_close($mysqli);?>
</div>
</body>
</html>