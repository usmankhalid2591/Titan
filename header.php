
<!---------------------- Settings -------------------------->
<header class="header">
  <div class="container">
    <div id="settingDiv" class="navigation">
      <div class="d-inline-flex align-items-center header-logo-heading">
        <a href="javasctipt:void(0);">
          <img src="logo.png" alt="logo">
        </a>
        <h1>Titan Feedback Update</h1>
      </div>
      <a id="settingButton">
        <i class="fas fa-cogs toggler fa-2x"></i>
      </a>
    </div>
  </div>
</header>

<!---------------------- Search -------------------------->
<section class="search-top margin-tb">
  <div class="container">
    <div class="row">
      <div class="col-6 offset-3">
        <form id="searchForm" method="get" class="position-relative">

            <input  type="textarea" placeholder="Search" cols="50"  id="searchDescription" name="searchDescription" value="<?php echo $searchDescription ?>" size="20">
            <a href="javascript:void(0);" class="remove-text">
              <i class="fa fa-times text-black-50 position-absolute"></i>
            </a>

          <a  onclick="searchFunc()" id="search" class="search-btn" name="search" value=<?php echo $baseUrl."tab=".$settings['tab']."&startDate=".$settings['startDate']."&assignedUser=".$settings['assignedUser']."&sortBy=".$settings['sortBy']."&orderBy=".$settings['orderBy']."&page=1" ?>>
            <i class="fas fa-search"></i>
          </a>
        </form>
      </div>
    </div>
  </div>
</section>

<!---------------------- Sidebar -------------------------->
<div class="sidebar">
  <form method="get" >

    <div id="settingForm">
    <input type="hidden" name="mode" id="mode" value='<?php echo $mode ?>' >
      <div class="d-flex align-items-center justify-content-between w-100">
        <a href="javascript:void(0);" class="toggler">
          <i class="fa fa-times fa-2x"></i>
        </a>
        <h3>Settings</h3>
      </div>

      <div class="calendar-icon">
        <div class="form-group w-100">
          <label for="startDate">Start Date</label>
          <input readonly class="form-control datepicker" data-date-format="dd/mm/yyyy" id="startDate" name="startDate" autocomplete="off" value="<?php echo $settings["startDate"] ?>">
          <i class="fas fa-calendar-alt"></i>
        </div>
      </div>

      <div class="form-group w-100">
        <label for="assignedUser">Assigned User</label>
        <select class="form-control" name="assignedUser" id="assignedUser">
          <?php for ($j = 0; $j < count($assignedUserList); $j++) {
            $au = (array) $assignedUserList[$j];
            ?>
            <option value="<?php echo $au["id"] ?>" <?php if ($settings["assignedUser"] == $au["id"]) { ?> selected="selected" <?php } ?>><?php echo $au["name"] ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="form-group w-100">
        <label for="sortBy">Sort By</label>
        <select class="form-control" name="sortBy" id="sortBy">
          <option value="date_modified" <?php if ($settings["sortBy"] == "date_modified") { ?> selected="selected" <?php } ?>>Date Modified</option>
          <option value="date_start" <?php if ($settings["sortBy"] == "date_start") { ?> selected="selected" <?php } ?>>Viewing Date</option>
          <option value="date_entered" <?php if ($settings["sortBy"] == "date_entered") { ?> selected="selected" <?php } ?>>Record Created Date</option>
        </select>
      </div>

      <div class="form-group w-100">
        <label>Order</label>
        <select class="form-control" name="orderBy" id="orderBy">
          <option value="ASC" <?php if ($settings["orderBy"] == "ASC") { ?> selected="selected" <?php } ?>>Ascending</option>
          <option value="DESC" <?php if ($settings["orderBy"] == "DESC") { ?> selected="selected" <?php } ?>>Descending</option>
        </select>
      </div>

      <button name="tab" value="<?php echo $settings['tab'] ?>" id="settingSubmit" class="backButton btn btn-primary"><strong>Apply</strong></button>
    </div>
  </form>
</div>


