<?php
$id_user = '';
if (isset($_SESSION['username']) && isset($_SESSION['id_user']) && is_int($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
}
$attraction_id = $_POST['attraction_id'] ?? '';
if (empty($attraction_id))
    redirection('attractions.php?e=0');


$attraction = getTableData($pdo, 'attractions', 'id_attraction', $attraction_id, false);
if(!$attraction)
{
   redirection('attractions.php?e=0');
}
$attractionName = $attraction['attraction_name'];
$images = getAttractionImages($pdo, $attraction_id);
echo "

<div class='container px-4 px-lg-5'>
    <h1 class='font-weight-light my-2 text-center text-decoration-underline'>{$attractionName}</h1>

<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>
    <div class='col-lg-5'>
        <div class='h-100 d-flex align-items-start'>
            <div>
                <h2 class='font-weight-light my-2'>Description</h2>
                <p>{$attraction['description']}</p>
            </div>
        </div>
    </div>
    <div class='col-lg-5'>
        <div class='h-100 d-flex align-items-start'>
            <div>
                <h2 class='font-weight-light my-2'>Attraction type</h2>
                <p>{$attraction['type']}</p>
                <h2 class='font-weight-light my-2'>Address</h2>
                <p>{$attraction['address']}</p>
                ";
if (!empty($id_user)) {
    $troll = isTroll($pdo, $id_user);
    if (!isFavouriteAttractionExist($pdo, $attraction['id_city'], $id_user)) {
        echo "
                <form method='post' class='text-center' action='form_action.php'>
                
                    <input type='hidden' name='action' value='makeFavourite'>
                    <input type='hidden' name='id_attraction' value='{$attraction_id}'>
                    <input type='submit' value='Make favourite' class='btn btn-danger'>
                </form>
                ";
    }
}
echo "
            </div>
        </div>
    </div>
</div>


<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>
      <div class='h-100 d-flex  justify-content-center text-center'>
          <div>
              <h2 class='font-weight-light my-2'>Image(s)</h2>
          </div>
      </div>
</div>
            ";
$imageCounter = 0;
echo "<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>";
foreach ($images as $image) {
    $imageCounter++;

    echo "<div class='col-lg-6 h-100 d-flex align-items-start justify-content-center'>";
    echo "<div>";
    echo "<img src='{$image['path']}' alt='{$attractionName}' class='img-thumbnail rounded img-fluid imgsmall'>";
    echo "</div>";
    echo "</div>";

    if ($imageCounter % 2 == 0) {
        echo "</div>";
        echo "<div class='row gx-4 gx-lg-5 align-items-stretch my-5'>";
    }
}

if ($imageCounter % 2 != 0) {
    echo "</div>";
}

if (!empty($id_user) and !$troll) {
    echo "
    
<div id='comment-section'>

    <h1>New comment</h1>


    <form action='form_action.php' method='post' class='pb-5'>
        <label for='comment' class='form-label'>Tell us, and others what you think about this attraction:</label>
        <br>
        <textarea class='form-control' name='comment' id='comment' rows='4' maxlength='200'></textarea>
        <br>
        <input type='hidden' name='action' value='newComment'>
        <input type='hidden' name='id_user' value='{$id_user}'>
        <input type='hidden' name='id_attraction' value='{$attraction_id}'>
        <button type='submit' class='btn btn-success border-3 border-dark text-center btn-lg'>Send</button>
    </form>
</div>
";
}


?>

<?php
$comments = getCommentData($pdo, $attraction_id);
if (!empty($comments))
    echo "
        
<div id='shown-comments' class=' gx-4 -lg-5 align-items-stretch my-5'>
    <h1 class='pb-2'>Comment section</h1>
        ";
foreach ($comments as $comment) {
    $firstname = $comment['firstname'];
    $date = strtotime($comment['date_time']);
    $backwardsDate = getDateBackwards($date);
    $filteredComment = $comment['filtered_comment'];

    echo
    "
        
    <div id='main' class='p-3 comment rounded-3 border border-1 border-info'>
        <div id='header' class='comment-header'>
            <b>{$firstname}  </b>
        </div>
        <div class='comment-time'>
            <time>{$backwardsDate}</time>
        </div>
       <hr> 
        <div id='comment-content' class='comment-content'>
            <p>{$filteredComment} </p>
        </div>
        

    </div>
    <hr>
        ";
}


?>


</div>

</div>

<!--Must leave this div closer here-->
</div>
