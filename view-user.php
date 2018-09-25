<?php include('template-parts/header.php'); ?>
<?php include('template-parts/session.php'); ?>
<?php include('template-parts/left-panel.php'); ?>
<?php
  if(isset($_POST)){
    updateUserInfo($_POST);
  }
  $userid = $_GET['userid'];
?>
    <div id="right-panel" class="right-panel">
      <?php if($_GET['action'] == 'done'): ?>
      <div class="col-sm-12 mt-5">
          <div class="alert  alert-success alert-dismissible fade show" role="alert">
            <span class="badge badge-pill badge-success">Success</span> Successfully banned user.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      </div>
      <?php endif;?>
      <?php if($_GET['action'] == 'error1'): ?>
      <div class="col-sm-12 mt-5">
          <div class="alert  alert-danger alert-dismissible fade show" role="alert">
            <span class="badge badge-pill badge-danger">Failed</span> User is already banned.
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      </div>
      <?php endif;?>

        <div class="content mt-3">
            <div class="animated fadeIn">
              <?php if(empty($userid)): ?>
                <div class="row">
                  <div class="col-lg-12">
                      <div class="card">
                          <div class="card-header">
                              <h4>No user selected</h4>
                          </div>
                          <div class="card-body">
                            <p>Find a usere here</p>
                          </div>
                      </div>
                  </div>
                </div>
              <?php else: ?>
                <?php
                  $userSql = " SELECT * FROM ".USERS_TABLE." WHERE ".USERS_TABLE.".id = {$userid}";
                  $resultUser = $link->query($userSql);

                while($user = mysqli_fetch_object($resultUser)){
                    $identifier = $user->identifier;
                    $license = $user->license;
                    $steamname = strip_tags($user->name);
                    $money = $user->money;
                    $bank = $user->bank;
                    $loadout = $user->loadout;
                    $timeplayed = $user->timeplayed;
                    $job = $user->job;
                    $jobGrade = $user->job_grade;
                    $online = $user->online;
                    $isDead = $user->isDead;
                    $userId = $user->id;
                }

                  $carSql = " SELECT * FROM ".OWNED_VEHICLES_TABLE." WHERE ".OWNED_VEHICLES_OWNER_COLUMN." = '{$identifier}'";
                  $resultCars = $link->query($carSql);
                  $ownedCarsCount = $resultCars->num_rows;

                  $blackMoneySql = " SELECT * FROM ".USER_ACCOUNTS_TABLE." WHERE ".USER_ACCOUNTS_IDENTIFIER_COLUMN." = '{$identifier}'";
                  $resultBlackMoney = $link->query($blackMoneySql);
                  $resultBlackMoneyCount = $resultBlackMoney->num_rows;
                    while($bm = mysqli_fetch_object($resultBlackMoney)){
                      $blackmoney = $bm->money;
                    }
                  $d = floor ($timeplayed / 1440);
                  $h = floor (($timeplayed - $d * 1440) / 60);
                  $m = $timeplayed - ($d * 1440) - ($h * 60);
                ?>
                <?php if($msg): ?>
                  <div class="row">
                    <div class="card-body">
                      <?=$msg?>
                    </div>
                  </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card userinfo">
                            <div class="card-header">
                                <h4><?=$steamname?> <?php if(checkIfBanned($identifier)): ?>[Banned]<?php endif;?></h4>
                            </div>
                            <div class="card-body user-info-list">
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">🕹️</div>
                                <div class="col-lg-6 col-xs-12 p-0">Status</div>
                                <div class="col-lg-5 col-xs-12 status-<?=($online == 0 ? "offline" : "online")?>"><?=($online == 0 ? "Offline" : "Online")?></div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">🚑</div>
                                <div class="col-lg-6 col-xs-12 p-0">Health</div>
                                <div class="col-lg-5 col-xs-12 status-<?=($isDead == 0 ? "alive" : "dead")?>">
                                  <div class="user-value <?=($online == 0 ? "offline" : "online")?>  isDead"  data-action="isDead">
                                    <div class="current-value isDead"><?=($isDead == 0 ? "Alive" : "Dead")?></div>
                                    <?=inlineEdit('isDead', $userId, $isDead)?>
                                  </div>
                                </div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1 hidden-xs">👔</div>
                                <div class="col-lg-6 col-xs-12 p-0">Job</div>
                                <div class="col-lg-5 col-xs-12"><?=ucfirst($job)?> <small>(<?=$jobGrade?>)</small></div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">💰</div>
                                <div class="col-lg-6 col-xs-12 p-0">Money</div>
                                <div class="col-lg-5 col-xs-12">
                                  <div class="user-value <?=($online == 0 ? "offline" : "online")?>  money"  data-action="money">
                                    <div class="current-value money">$ <?=thousandsCurrencyFormat($money)?></div>
                                    <?=inlineEdit('money', $userId, $money)?>
                                  </div>
                                </div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">💳</div>
                                <div class="col-lg-6 p-0">Bank</div>
                                <div class="col-lg-5">
                                  <div class="user-value <?=($online == 0 ? "offline" : "online")?> bank" data-action="bank">
                                    <div class="current-value bank">$ <?=thousandsCurrencyFormat($bank)?></div>
                                    <?=inlineEdit('bank', $userId, $bank)?>
                                  </div>
                                </div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">💵</div>
                                <div class="col-lg-6 p-0">Blackmoney</div>
                                <div class="col-lg-5">$ <?=$blackmoney?></div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">🚗</div>
                                <div class="col-lg-6 p-0">Owned Vehicles</div>
                                <div class="col-lg-5"><?=$ownedCarsCount?></div>
                              </div>
                              <div class="row pt-1 pb-2">
                                <div class="col-lg-1">⏱️</div>
                                <div class="col-lg-6 p-0">Time played</div>
                                <div class="col-lg-5"><?=$d?> days <?=$h?> hours <?=$m?> min</div>
                              </div>

                            </div>
                        </div>
                    </div>
                    <!-- /# column -->

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4>Loadout</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list">
                                  <?php
                                  $loadout = json_decode($loadout, true);
                                  if(empty($loadout)):
                                    echo '<p>No weapons found</p>';
                                  else:
                                    foreach($loadout as $key => $weapon):
                                      $weaponName = $weapon['name'];
                                      $weaponName = str_replace("WEAPON_","", $weaponName);
                                      $weaponName = strtolower($weaponName);
                                    ?>
                                    <li class="list-item delweapon" data-csrf="<?=getCurrentCsrfToken()?>" data-weaponid="<?=$key?>" data-userid="<?=$userId?>">
                                      <div class="row">
                                        <div class="col-md-6"><?=ucfirst($weaponName)?></div>
                                        <div class="col-md-6"><?=$weapon['ammo']?></div>
                                      </div>
                                    </li>
                                    <?php endforeach; ?>
                                  <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- /# column -->
                </div>

                    <div class="row">

                        <?php if(SHOW_CAR_LIST): ?>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Car list</h4>
                                </div>
                                <div class="card-body cars-list">
                                  <?php if($resultCars->num_rows > 0):?>
                                  <?php while($car = mysqli_fetch_object($resultCars)): ?>
                                    <div class="row pt-1 pb-1 bbrow">
                                      <div class="col-lg-12">
                                        <a href="#" class="delcar" data-steamid="<?=$car->owner?>" data-plate="<?=$car->plate?>"><?=$car->modelname?></a>
                                        <?php
                                        $plate = $car->plate;
                                        $trunkSql = " SELECT * FROM truck_inventory WHERE plate = '{$plate}' AND count != '0'";
                                        $resultTrunk = $link->query($trunkSql);
                                        if($resultTrunk->num_rows > 0):
                                        ?>
                                          <button type="button" class="btn btn-secondary mb-1 viewtrunkbtn" data-plate="<?=$plate?>" data-toggle="modal" data-csrf="<?=getCurrentCsrfToken()?>" data-target="#largeModal">View trunk</button>
                                        <?php endif;?>
                                      </div>
                                    </div>
                                  <?php endwhile; ?>
                                <?php else: ?>
                                  <p>User has no cars</p>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <!-- /# column -->

                        <?php if(SHOW_PROPERTIES): ?>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Inventory</h4>
                                </div>
                                <div class="card-body">
                                  <?php
                                  $inventorySql = " SELECT * FROM user_inventory WHERE identifier = '{$identifier}' AND count != '0'";
                                  $resultInventory = $link->query($inventorySql);
                                  ?>
                                  <?php if($resultInventory->num_rows > 0):?>
                                  <?php while($item = mysqli_fetch_object($resultInventory)): ?>
                                  <?php if($item->count != '0'): ?>
                                      <div class="row pb-2 pt-2">
                                        <div class="col-lg-7">
                                          <a href="#" class="remove-item" data-csrf="<?=getCurrentCsrfToken()?>" data-itemid="<?=$item->id?>"><?=$item->item?></a>
                                        </div>
                                        <div class="col-lg-4">
                                          <?=$item->count?>
                                        </div>
                                      </div>
                                    <?php endif; ?>
                                  <?php endwhile; ?>

                                <?php else: ?>
                                    <p>User has no inventory items</p>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                    </div><!-- END ROW -->

                    <div class="row">
                      <div class="col-lg-6">
                          <div class="card">
                              <div class="card-header">
                                  <h4>Account Data</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                $accountSQL = " SELECT * FROM addon_account_data WHERE owner = '{$identifier}'";
                                $resultAccount = $link->query($accountSQL);
                                ?>
                                <?php if($resultAccount->num_rows > 0):?>
                                <?php while($account = mysqli_fetch_object($resultAccount)): ?>
                                    <div class="row pb-2 pt-2">
                                      <div class="col-lg-5">
                                        <?=$account->account_name?>
                                      </div>
                                      <div class="col-lg-7">
                                        <?=thousandsCurrencyFormat($account->money)?>
                                      </div>
                                    </div>
                                <?php endwhile; ?>
                                <div class="row pb-2 pt-2">
                                  <div class="col-lg-5">
                                    Steamid
                                  </div>
                                  <div class="col-lg-7">
                                    <?=$identifier?>
                                  </div>
                                </div>
                                <div class="row pb-2 pt-2">
                                  <div class="col-lg-5">
                                    License
                                  </div>
                                  <div class="col-lg-7">
                                    <?=str_replace("license:","",$license)?>
                                  </div>
                                </div>
                              <?php else: ?>
                                  <p>User has no data</p>
                              <?php endif; ?>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-6">
                          <div class="card">
                              <div class="card-header">
                                  <h4>Licenses</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                $lisenceSQL = " SELECT * FROM user_licenses WHERE owner = '{$identifier}'";
                                $resultLicense = $link->query($lisenceSQL);
                                ?>
                                <?php if($resultLicense->num_rows > 0):?>
                                <?php while($licence = mysqli_fetch_object($resultLicense)): ?>
                                    <div class="row pb-2 pt-2">
                                      <div class="col-lg-12">
                                        <?=ucfirst($licence->type)?>
                                      </div>
                                    </div>
                                <?php endwhile; ?>
                              <?php else: ?>
                                  <p>User has no licenses</p>
                              <?php endif; ?>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <?php if(SHOW_PROPERTIES): ?>
                      <div class="col-lg-6">
                          <div class="card">
                              <div class="card-header">
                                  <h4>Properties</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                $housesSql = " SELECT * FROM owned_properties WHERE owner = '{$identifier}'";
                                $resultHouses = $link->query($housesSql);
                                ?>
                                <?php if($resultHouses->num_rows > 0):?>
                                <?php while($house = mysqli_fetch_object($resultHouses)): ?>
                                    <div class="row pb-2 pt-2">
                                      <div class="col-lg-7">
                                        <?=$house->name?>
                                      </div>
                                      <div class="col-lg-4">
                                        <?php if ($house->rented == 0 ):?>
                                            B: <?=thousandsCurrencyFormat($house->price)?>
                                        <?php else: ?>
                                            R: <?=thousandsCurrencyFormat($house->price)?>
                                        <?php endif; ?>
                                      </div>
                                    </div>
                                <?php endwhile; ?>

                              <?php else: ?>
                                  <p>User has no properties</p>
                              <?php endif; ?>
                              </div>
                          </div>
                      </div>
                      <?php endif;?>
                    </div>
                    <div class="row">
                      <div class="col-lg-6">
                          <div class="card">
                              <div class="card-header">
                                  <h4>Admin actions</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                $userActionSQL = " SELECT * FROM users WHERE identifier = '{$identifier}'";
                                $resultUserAction = $link->query($userActionSQL);
                                ?>
                                <?php if($resultUserAction->num_rows > 0):?>
                                  <?php while($account = mysqli_fetch_object($resultUserAction)): ?>
                                      <div class="row pb-2 pt-2">
                                        <div class="col-lg-12">
                                          <span class="kick admin-action" data-steamid="<?=$account->identifier?>">Kick</span>
                                          <span class="banuser admin-action" data-license="<?=$account->license?>" data-steamid="<?=$account->identifier?>">Ban</span>

                                          <div class="ban-player row mb-3 mt-3">
                                              <form action="/admin/actions/addBan.php" method="SELF">
                                                <input type="hidden" name="license" value="<?=$account->license?>">
                                                <input type="hidden" name="userid" value="<?=$account->id?>">
                                                <input type="hidden" name="username" value="<?=$account->name?>">
                                                <input type="hidden" name="steamid" value="<?=$account->identifier?>">
                                                <input type="hidden" name="csrf" value="<?=getCurrentCsrfToken()?>">
                                                <input type="hidden" name="bannedby" value="<?=$_SESSION['username'];?>">
                                                <input type="hidden" name="actionbyuser" value="yes">

                                                <div class="col-md-12">
                                                  <div class="form-group">
                                                      <label>Expire</label>
                                                      <select name="expires" class="form-control">
                                                        <option value="1d">1 day</option>
                                                        <option value="2d">2 days</option>
                                                        <option value="3d">3 days</option>
                                                        <option value="1w">1 week</option>
                                                        <option value="2w">2 weeks</option>
                                                        <option value="3w">3 weeks</option>
                                                        <option value="1m">1 month</option>
                                                        <option value="2m">2 months</option>
                                                        <option value="3m">3 months</option>
                                                        <option value="6m">6 months</option>
                                                        <option value="1y">1 year</option>
                                                        <option value="perma">perma</option>
                                                      </select>
                                                  </div>
                                                </div>
                                                <div class="col-md-12">
                                                  <div class="form-group">
                                                      <label>Reason</label>
                                                      <input type="text" name="reason" class="form-control" placeholder="Reason">
                                                  </div>
                                                </div>
                                                <div class="col-md-12">
                                                  <div class="form-group">
                                                      <input type="submit" name="ban" value="Ban now"> <span class="cancelban">cancel</span>
                                                  </div>
                                                </div>
                                              </form>
                                          </div>
                                        </div>
                                      </div>
                                  <?php endwhile; ?>
                                <?php endif;?>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                          <div class="card">
                              <div class="card-header">
                                  <h4>Admin Warnings</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                  $resultWarning = $link->query("SELECT * FROM user_warnings WHERE userid = '$userId'");
                                ?>
                                <?php if($resultWarning->num_rows > 0):?>
                                    <div class="row pb-2 pt-2">
                                      <div class="col-lg-12">
                                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                          <tr>
                                            <td>Date</td>
                                            <td>Type</td>
                                            <td>Comment</td>
                                            <td>By</td>
                                            <td></td>
                                          </tr>
                                          <?php while($warnings = mysqli_fetch_object($resultWarning)): ?>
                                            <tr>
                                              <td><?=date('d-m-y h:i',$warnings->time_added);?></td>
                                              <td><?=$warnings->type?></td>
                                              <td><?=$warnings->warning?></td>
                                              <td><?=$warnings->byadmin?></td>
                                              <td><span href="#" class="delete btn" data-csrf="<?=getCurrentCsrfToken()?>" data-id="<?=$warnings->id?>" data-action="user_warnings"> X </span></td>
                                            </tr>
                                          <?php endwhile; ?>
                                        </table>
                                      </div>
                                    </div>
                                <?php else: ?>
                                    User has no warnings
                                <?php endif;?>
                                <div class="row pt-2">
                                  <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#warning">Create Warning</button>
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                          <div class="card">
                              <div class="card-header">
                                  <h4>User received bans</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                  $resultBans = $link->query("SELECT * FROM received_bans WHERE userid = '$userId'");
                                ?>
                                <?php if($resultBans->num_rows > 0):?>
                                    <div class="row pb-2 pt-2">
                                      <div class="col-lg-12">
                                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                          <tr>
                                            <td>Date</td>
                                            <td>Expire</td>
                                            <td>Reason</td>
                                            <td>By</td>
                                          </tr>
                                          <?php while($ban = mysqli_fetch_object($resultBans)): ?>
                                            <tr>
                                              <td><?=date('d-m-y h:i',$ban->banned_on);?></td>
                                              <td><?=date('d-m-y h:i',$ban->ban_expires)?></td>
                                              <td><?=explode(" (",$ban->reason)[0]?></td>
                                              <td><?=explode("Banned by:",$ban->reason)[1]?></td>
                                            </tr>
                                          <?php endwhile; ?>
                                        </table>
                                      </div>
                                    </div>
                                <?php else: ?>
                                    User has no previous bans
                                <?php endif;?>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-lg-12">
                          <div class="card">
                              <div class="card-header">
                                  <h4>User reports</h4>
                              </div>
                              <div class="card-body">
                                <?php
                                  $resultReports = $link->query("SELECT * FROM user_reports WHERE userid = '$userId'");
                                ?>
                                <?php if($resultReports->num_rows > 0):?>
                                    <div class="row pb-2 pt-2">
                                      <div class="col-lg-12">
                                        <table id="bootstrap-data-table" class="table table-striped table-bordered">
                                          <tr>
                                            <td>Reported By</td>
                                            <td>Type</td>
                                            <td>Comment</td>
                                            <td>Date</td>
                                          </tr>
                                          <?php while($report = mysqli_fetch_object($resultReports)): ?>
                                            <tr>
                                              <td><?=$report->reported_by;?></td>
                                              <td><?=$report->report_type?></td>
                                              <td><?=$report->report_comment?></td>
                                              <td><?=date('d-m-y h:i',$report->report_time)?></td>
                                            </tr>
                                          <?php endwhile; ?>
                                        </table>
                                      </div>
                                    </div>
                                <?php else: ?>
                                    User has no reports
                                <?php endif;?>
                                <div class="row pt-2">
                                  <div class="col-md-12">
                                    <button type="button" class="btn btn-secondary mb-1" data-toggle="modal" data-target="#report">Create report</button>
                                  </div>
                                </div>
                              </div>
                          </div>
                      </div>
                    </div>
              <?php endif; ?>
            </div><!-- .animated -->
        </div><!-- .content -->
    </div><!-- /#right-panel -->

<?php include('template-parts/user-views/trunk-inventory.php'); ?>
<?php include('template-parts/reports/warning.php'); ?>
<?php include('template-parts/reports/report.php'); ?>
<?php include('template-parts/footer.php'); ?>
