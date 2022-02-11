<?php
!defined('EMLOG_ROOT') && exit('access deined!'); ?>

<?php if (isset($_GET['success'])) : ?>
    <div class="alert alert-success">菜单位置已更新。</div><?php endif; ?>


<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">导航</h1>
</div>

<div class="panel-heading">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link" href="navbar.php">编辑菜单</a></li>
        <li class="nav-item"><a class="nav-link active" href="navbar.php?action=locations">管理位置</a></li>
    </ul>
</div>

<div class="row mt-2">
    <div class="col-xl-9">
        <div class="card shadow mb-4">
            <div class="card-body">
                <p>您的主题支持<?= count($_em_registered_nav_menus); ?>个菜单，请分别选择您希望在每一处出现的菜单。</p>
                <div id="menu-locations-wrap" class="table-responsive">
                    <form method="post" action="navbar.php?action=update">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer" id="menu-locations-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="manage-column column-locations">主题位置</th>
                                    <th scope="col" class="manage-column column-menus">已指派的菜单</th>
                                    <th scope="col" class="manage-column column-action">操作</th>
                                </tr>
                            </thead>
                            <tbody class="menu-locations">
                                <?php foreach ($_em_registered_nav_menus as $key => $value) : ?>
                                    <tr class="menu-locations-row">
                                        <td class="menu-location-title">
                                            <label for="locations-<?= $key; ?>"><?= $value; ?></label>
                                        </td>
                                        <td class="menu-location-menus">
                                            <select name="menu-locations[<?= $key; ?>]" id="locations-<?= $key; ?>" class="form-control">
                                                <option value="0">— 选择菜单 —</option>
                                                <?php
                                                foreach ($menus as $keyid => $value) {
                                                    if ($keyid == 0) continue;
                                                    $selected = isset($locations[$key]) && $locations[$key] == $keyid ? 'selected' : '';
                                                    echo "<option value=\"{$keyid}\" {$selected}>{$value}</options>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        <td class="menu-location-menus">
                                            <div class="locations-row-links">
                                                <span class="locations-edit-menu-link">
                                                    <a href="javascript:edit_menu();">
                                                        <span class="screen-reader-text">编辑选中的菜单</span>
                                                    </a>
                                                </span>
                                                <span class="locations-add-menu-link">
                                                    <a href="navbar.php?action=edit&menu=0&use-location=<?= $key; ?>"> 使用新菜单 </a>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <input type="submit" name="nav-menu-locations" id="nav-menu-locations" class="btn btn-success" value="保存更改">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(hideActived, 2600);
    $("#menu_category_view").addClass('active');
    $("#menu_view").addClass('show');
    $("#menu_navi").addClass('active');

    function edit_menu() {

    }
</script>