<?php
include "update.php";
include "log.php";
//////////////// Default Values ///////////////////////////////
//$baseUrl= $_SERVER['REQUEST_SCHEME'] .'://'. $_SERVER['HTTP_HOST'] 
//     . explode('?', $_SERVER['REQUEST_URI'], 2)[0]."?";
$baseUrl = "?";
$settings = [];
$settings["tab"] = "1";
$settings["assignedUser"] = "all";
$settings["startDate"] = "01/01/2018";
$settings["sortBy"] = "date_start";
$settings["orderBy"] = "DESC";
if (isset($_GET['page'])) {
    $page = (int) trim($_GET['page']);
    $offset = $limit * ($page - 1);
} else {
    $page = 1;
    $offset = 0;
}

if (isset($_GET['searchDescription'])) {
    $searchDescription = trim($_GET['searchDescription']);
} else {
    $searchDescription = "";
}

if (isset($_GET['tab'])) {
    if (trim($_GET['tab']) == '1' or trim($_GET['tab']) == '0') {
        $settings["tab"] = trim($_GET['tab']);
    }
}
if (isset($_GET['assignedUser'])) {
    $settings["assignedUser"] = trim($_GET["assignedUser"]);
}
if (isset($_GET['startDate'])) {
    $settings["startDate"] = trim($_GET["startDate"]);
}
if (isset($_GET['sortBy'])) {
    $settings["sortBy"] = trim($_GET["sortBy"]);
}
if (isset($_GET['orderBy'])) {
    $settings["orderBy"] = trim($_GET["orderBy"]);
}
if (isset($_GET['mode'])) {
    $mode = trim($_GET["mode"]);
    if ($mode != "live" and $mode != "beta") {
        $mode = "live";
    }
} else {
    $mode = "live";
}

if ($mode == "beta") {
    $baseUrl = $baseUrl . "mode=beta&";
}
if ($mode == "live") {
    $linkmode = "laravel";
} else {
    $linkmode = "laravel-beta";
}
///////////////// AssignedUser //////////////////////////////

$assignedUserList = getAssignedUser($mode);
if (isset($assignedUserList['code']) != True) {
    $assignedUserList['code'] = 0;
}
if ($assignedUserList['code'] == 1) {
    $assignedUserList = (array) $assignedUserList['data'];
} else {
    $assignedUserList = [];
}

array_unshift($assignedUserList, ["id" => 'all', "name" => "All"]);



$result = array();
$totalPages = 0;
$totalRecords = 0;

$data = dbRequest($limit, $offset, $searchDescription, $settings["startDate"], $settings["sortBy"], $settings["orderBy"], $settings["assignedUser"], $settings["tab"], $mode);
$left_rec = 0;

if (isset($data['code']) != True) {
    $data['code'] = 0;
}
if ($data['code'] != 0 and $data['description'] != "No data found") {
    $totalRecords = $data['total'];
    $totalPages = ceil($totalRecords / $limit);
    $left_rec = $totalRecords - ($page * $limit);
    $result = $data['data'];
}

?>

<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="bootstrap-datepicker.css">
    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600&display=swap" rel="stylesheet">
    <!-- Fontawesome Kit -->
    <script src="https://kit.fontawesome.com/9c7309bfe2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.js"></script>
    <script>
        $(window).ready(function() {
            $(".loader").fadeOut("slow");
        });
    </script>
</head>

<body>
    <?php include "header.php" ?>

    <!----------------------  TABS ------------------------>
    <div class="navigation-tabs-main margin-b">

        <nav>
            <div class="container">
                <div class="nav nav-tabs border-0" id="nav-tab" role="tablist">
                    <!--<button style="background-color: Transparent;border: none;overflow: hidden">-->
                    <a id="nav-home-tab" href=<?php echo $baseUrl . "tab=1&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=1" ?> role="tab" aria-controls="nav-home" aria-selected="false" value="<?php echo rawurlencode(json_encode(["tab" => "1", "settings" => $settings])) ?>" <?php if ($settings["tab"] == "1") { ?> onclick="return false;" class='nav-item nav-link border-0 active' aria-selected="true" <?php } else { ?> class='nav-item nav-link border-0' <?php } ?>>
                        Pending/Accepted
                        <i class="fas fa-info-circle ml-2" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<h4>This tab contains feedback with the following status:</h4><ul><li>Planned</li><li>Negotiating</li><li>Feedback Required</li><li>Interest Shown-Chase</li><li>Deal Agreed</li><li>Viewing Confirmed</li></ul>"></i>
                    </a>


                    <a id="nav-profile-tab" href=<?php echo $baseUrl . "tab=0&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=1" ?> role="tab" aria-controls="nav-profile" aria-selected="false" value="<?php echo rawurlencode(json_encode(["tab" => "0", "settings" => $settings])) ?>" <?php if ($settings["tab"] == "0") {
                                                                                                                                                                                                                                                                                                                                                                                                    echo "tab0"; ?> onclick="return false;" class='nav-item nav-link border-0 active' aria-selected="true" <?php } else { ?> class="nav-item nav-link border-0" <?php } ?>>
                        Pending/Rejected
                        <i class="fas fa-info-circle ml-2" data-toggle="tooltip" data-placement="bottom" data-html="true" title="<h4>This tab contains feedback with the following status:</h4><ul><li>Not Interested / Dead</li><li>Cancelled / No Show – Dead</li><li>Pending By Seller</li><li>Pending by Buyer</li><li>Rejected</li></ul>"></i>
                    </a>

                </div>
            </div>
        </nav>
    </div>

    <div class="container">
        <div class="loader"></div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <div>
                    <div id="displayInfo" class="text-center table-wrapper">
                        <table id="feedbacktable" class="table">
                            <thead class="table-head">
                                <tr>
                                    <th>
                                        <h4>Original Feedback</h4>
                                    </th>
                                    <th>
                                        <h4>Accepted Feedback</h4>
                                        <h6>Currently being shown to user</h6>
                                    </th>
                                    <th>
                                        <h4> New Feedback</h4>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="mytbody" class="table-body">
                                <?php if ($data['description'] == "No data found") ?>
                                <?php
                                for ($i = 0; $i < count($result); $i++) {
                                    $result[$i] = (array) $result[$i];
                                    $rowId = "row" . strval($i);
                                    $orignalFeedback = "original" . strval($i);
                                    $feedback = "feedback" . strval($i);
                                    $newFeedbackField = $result[$i]["description"];
                                    $newFeedback = "newFeedback" . strval($i);

                                    ?>
                                    <tr id="<?php echo $rowId ?>">
                                        <td>
                                            <a target="_blank" href="<?php echo "http://titanhub.co.uk/$linkmode/crm?module=Viewings&viewtype=DetailView&id=" . $result[$i]['id'] ?>">
                                                <p id="<?php echo $orignalFeedback ?>" name="<?php echo $orignalFeedback ?>" size="20">
                                                    <?php
                                                        echo nl2br($result[$i]["description"]);
                                                        ?>
                                                </p>
                                            </a>
                                        </td>
                                        <td>
                                            <label id="<?php echo $feedback ?>" name="<?php echo $feedback ?>" size="20">
                                                <?php
                                                    echo nl2br($result[$i]["description_modified"]);
                                                    ?>
                                            </label>
                                        </td>
                                        <td>
                                            <textarea rows="4" cols="30" id="<?php echo $newFeedback ?>" name="<?php echo $newFeedback ?>" size="20"><?php echo $newFeedbackField ?></textarea>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary accept-btn" onclick="updateFeedback(<?php echo $i ?>,'update','<?php echo $mode ?>')" class="updateButton" name="<?php echo 'update' . strval($i) ?>" value="<?php echo $result[$i]["id"] ?>">
                                                Accept
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-primary reject-btn" onclick="updateFeedback(<?php echo $i ?>,'reject','<?php echo $mode ?>')" name="<?php echo 'reject' . strval($i) ?>" value="<?php echo  $result[$i]["id"] ?>" class="rejectButton">
                                                Reject
                                            </button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="pagination-bottom">
                <form method="post">
                    <input type="hidden" name="searchDescription" value="<?php echo $searchDescription ?>">
                    <input type="hidden" name="settingsArray" value=<?php echo rawurlencode(json_encode($settings)) ?>>

                    <ul class="pagination">
                        <?php
                        if ($page > 1) { ?>
                            <li class="page-item"><a class="page-link chevron" href=<?php echo $baseUrl . "tab=" . $settings['tab'] . "&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=" . strval($page - 1) . "&searchDescription=" . rawurlencode($searchDescription) ?>><i class="fa fa-chevron-left"></i></a></li>

                        <?php } ?>

                        <?php
                        if (($totalPages - 1 - $page) > 5) {
                            $apage = $page + 4;
                        } else {
                            $apage = $totalPages;
                        }
                        //if (($page+4)<
                        $bpage = $page - 4;
                        if ($bpage > 1) {
                            $i = $bpage;
                            ?>
                            <li class="page-item"><a href=<?php echo $baseUrl . "tab=" . $settings['tab'] . "&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=1&searchDescription=" . rawurlencode($searchDescription) ?> class="page-link" name="next" value="-1" class="viewButton">1</a></li>
                            <li class="page-item"><a class="backButton" disabled>...</a></li>
                            <?php
                            } else {
                                $i = 0;
                            }
                            for (; $i < $apage; $i++) {
                                if ($i == ($page - 1)) { ?>
                                <li><a href=<?php echo $baseUrl . "tab=" . $settings['tab'] . "&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=" . strval($i + 1) . "&searchDescription=" . rawurlencode($searchDescription) ?> class="page-link" style="background-color:#83c1ff;" name="next" value="<?php echo $i ?>" onclick="return false" class="viewButton"><?php echo $i + 1 ?></a></li>
                            <?php
                                } else {
                                    ?>
                                <li><a href=<?php echo $baseUrl . "tab=" . $settings['tab'] . "&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=" . strval($i + 1) . "&searchDescription=" . rawurlencode($searchDescription) ?> class="page-link" name="next" value="<?php echo $i - 1 ?>" class="viewButton"><?php echo $i + 1 ?></a></li>
                            <?php
                                }
                            }
                            if (($totalPages - 1 - $page) > 5) {
                                ?>
                            <li class="page-item"><a href=<?php echo $baseUrl . "tab=" . $settings['tab'] . "&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=" . strval($totalPages) . "&searchDescription=" . rawurlencode($searchDescription) ?> class="page-link" name="next" value="<?php echo $totalPages - 2 ?>" class="viewButton"><?php echo $totalPages ?></a></li>
                        <?php
                        }
                        ?>

                        <?php
                        if ($page < $totalPages) { ?>
                            <li class="page-item"><a class="page-link chevron" href=<?php echo $baseUrl . "tab=" . $settings['tab'] . "&startDate=" . $settings['startDate'] . "&assignedUser=" . $settings['assignedUser'] . "&sortBy=" . $settings['sortBy'] . "&orderBy=" . $settings['orderBy'] . "&page=" . strval($page + 1) . "&searchDescription=" . rawurlencode($searchDescription) ?>><i class="fa fa-chevron-right"></i></a></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="update.js"></script>
    <script src="bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="custom.js"></script>

    <script>
        function searchFunc() {

            fullUrl = document.getElementById("search").getAttribute('value');

            searchDescription = document.getElementById("searchDescription").value
            finalUrl = fullUrl + "&searchDescription=" + encodeURIComponent(searchDescription);
            window.location.href = finalUrl;

        }

        inputSearch = document.getElementById("searchDescription");

        inputSearch.addEventListener("keyup", function(event) {

            if (event.keyCode == 13) {
                event.preventDefault();
                searchFunc();
            }
        });

    </script>
</body>

</html>