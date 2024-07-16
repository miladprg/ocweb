<?php
    require_once "./assets/pages/header.php";
    if (!isUserAuthenticated()) route("index.php");

    UsersDatabase::connect(DATABASE);

    if (isset($_GET["del"])) {
	$username = $_GET["del"];
        UsersDatabase::deleteUser($username);
        UsersDatabase::closeConnection();
	    terminal("delete_user $username");
        ServersDatabase::connect(DATABASE);
        foreach (ServersDatabase::getAllServers() as $server) {
            run_command_to_server($server->ip, "root", $server->password, $server->port, "sed -i '/$username/d' /etc/ocserv/logs.log; ocpasswd -d $username;");
        }
        ServersDatabase::closeConnection();
        route();
    }

    if (isset($_GET["edit-status"])) {
        UsersDatabase::changestatus($_GET["edit-status"], $_GET["status"]);
        UsersDatabase::closeConnection();
        ServersDatabase::connect(DATABASE);
        $changeStatusCommand = "ocpasswd" . (($_GET["status"]=="فعال") ? " -l " : " -u ") . $_GET["edit-status"];
        if ($_GET["status"]=="فعال") terminal("lock_user ".$_GET["edit-status"]);
        else terminal("unlock_user ".$_GET["edit-status"]);
        foreach (ServersDatabase::getAllServers() as $server) {
            run_command_to_server($server->ip, "root", $server->password, $server->port, $changeStatusCommand);
        }
        route();
    }

    $users = UsersDatabase::getAllUsers();

    UsersDatabase::closeConnection();
?>

      <div class="contents">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-lg-12">
                      <div class="todo-breadcrumb">
                          <div class="breadcrumb-main">
                              <h4 class="text-capitalize breadcrumb-title">لیست کاربران</h4>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
         <div class="container-fluid">
            <div class="row">
               <div class="col-lg-12 mb-30">
                  <div class="card">
                     <div class="card-body">
                        <div class="userDatatable adv-table-table global-shadow border-light-0 w-100 adv-table">
                           <div class="table-responsive">
                              <div id="filter-form-container"></div>
                              <table class="table mb-0 table-borderless adv-table" data-sorting="true" data-filter-container="#filter-form-container" data-paging-current="1" data-paging-position="right" data-paging-size="20">
                                 <thead>
                                    <tr class="userDatatable-header text-center">
                                       <th style="width: 5%;">
                                          <span class="userDatatable-title">آیدی</span>
                                       </th>
                                        <th style="width: 20%">
                                          <span class="userDatatable-title">نام کاربری</span>
                                       </th>
                                        <th style="width: 20%">
                                          <span class="userDatatable-title">رمز عبور</span>
                                       </th>
                                        <th style="width: 15%">
                                            <span class="userDatatable-title">تعداد کاربر</span>
                                        </th>
                                        <th style="width: 15%">
                                            <span class="userDatatable-title">تاریخ</span>
                                        </th>
                                       <th style="10%" data-type="html" data-name='status'>
                                          <span class="userDatatable-title">وضعیت</span>
                                       </th>
                                        <th style="width: 15%">
                                          <span class="userDatatable-title float-end">عملیات</span>
                                       </th>
                                    </tr>
                                 </thead>
                                 <tbody>
                                 <?php
                                 foreach ($users as $user) {
                                 ?>
                                    <tr class="text-end">
                                       <td>
                                          <div class="userDatatable-content"><?php echo $user->id;?></div>
                                       </td>
                                       <td>
                                          <div class="">
                                             <div class="">
                                                <a href="add-user.php?type=edit&username=<?php echo $user->username;?>">
                                                   <span class="fs-14 fw-bold text-gray"><?php echo $user->username;?></span>
                                                </a>
                                             </div>
                                          </div>
                                       </td>
                                       <td>
                                          <div class="fs-14 fw-bold text-gray"><?php echo $user->password;?></div>
                                       </td>
                                        <td>
                                            <div class="userDatatable-content text-gray"><?php echo $user->group;?></div>
                                        </td>
                                        <td>
                                            <div class="userDatatable-content text-gray"><?php echo get_datetime_by_timestamp($user->addDate);?></div>
                                        </td>
                                       <td>
                                          <div class="userDatatable-content d-inline-block">
                                             <span class="bg-opacity-<?php echo ($user->status=="فعال")? "success color-success" : "danger color-danger";?> rounded-pill userDatatable-content-status active"><?php echo $user->status;?></span>
                                          </div>
                                       </td>
                                       <td>
                                          <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                                              <li>
                                                  <a href="user-connections-history.php?user=<?php echo $user->username;?>" class="edit">
                                                      <i class="uil uil-clock"></i>
                                                  </a>
                                              </li>
                                              <li>
                                                  <a href="?edit-status=<?php echo $user->username;?>&status=<?php echo $user->status;?>" class="edit">
                                                      <i class="uil uil-exchange"></i>
                                                  </a>
                                              </li>
                                             <li>
                                                <a href="add-user.php?type=edit&username=<?php echo $user->username;?>" class="edit">
                                                   <i class="uil uil-edit"></i>
                                                </a>
                                             </li>
                                             <li>
                                                <a href="?del=<?php echo $user->username;?>" class="remove">
                                                   <i class="uil uil-trash-alt"></i>
                                                </a>
                                             </li>
                                          </ul>
                                       </td>
                                    </tr>
                                <?php } ?>
                                 </tbody>
                              </table>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

<?php
require_once "./assets/pages/footer.php";
?>
