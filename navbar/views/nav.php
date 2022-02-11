<?php
!defined('EMLOG_ROOT') && exit('access deined!');

?>
<?php if (isset($_GET['add_success'])) : ?>
    <div class="alert alert-success">创建成功</div><?php endif; ?>
<?php if (isset($_GET['edit_success'])) : ?>
    <div class="alert alert-success">修改成功</div><?php endif; ?>
<?php if (isset($_GET['active_del'])) : ?>
    <div class="alert alert-success">删除成功</div><?php endif; ?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">导航</h1>
</div>

<div class="panel-heading">
    <ul class="nav nav-pills">
        <li class="nav-item"><a class="nav-link active" href="navbar.php">编辑菜单</a></li>
        <?php if (count($menus) >= 2) : ?>
            <li class="nav-item"><a class="nav-link" href="navbar.php?action=locations">管理位置</a></li>
        <?php endif; ?>
    </ul>
</div>

<div class="card shadow mb-2 mt-3">
    <div class="card-body">
        <?php
        if (count($menus) <= 1) {
            echo '<span>在此创建您的第一个菜单。</span>';
        } elseif (count($menus) == 2) {
            echo '<span>在此编辑您的菜单，或<a href="navbar.php?action=edit&menu=0">创建新菜单</a>。不要忘了保存您的修改！</span>';
        } else {
        ?>
            <div class="manage-menus">
                <form method="get" action="navbar.php">
                    <input type="hidden" name="action" value="edit">
                    <div class="mb-3">
                        <label for="select-menu-to-edit" class="selected-menu">选择要编辑的菜单：</label>
                        <select name="menu" id="select-menu-to-edit" class="form-control">
                            <option value="0" <?= $menu == '0' ? 'selected' : ''; ?>>—选择—</option>
                            <?php
                            foreach ($menus as $key => $value) {
                                if ($key == 0) continue;
                                $selected = $the_menu == $key ? 'selected' : '';
                                echo "<option value=\"{$key}\" {$selected}>{$value}</options>";
                            }
                            ?>
                        </select>
                    </div>
                    <span class="submit-btn"><input type="submit" class="btn btn-success" value="选择"></span>
                    <span class="add-new-menu-action">或<a href="navbar.php?action=edit&menu=0">创建新菜单</a>。不要忘了保存您的修改！ <span class="screen-reader-text">点击保存菜单按钮来保存您的修改。</span>
                    </span>
                </form>
            </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-xl-3">
        <h4 class="m-1">添加菜单项目</h4>
        <div class="accordion shadow mb-4" id="accordionAddMenu">
            <div class="card">
                <div class="card-header">
                    <div data-toggle="collapse" data-target="#collapse-page" aria-expanded="true" aria-controls="collapse-page" class="h5 mb-0" role="button">页面</div>
                </div>
                <div id="collapse-page" class="collapse" data-parent="#accordionAddMenu">
                    <div class="card-body">
                        <form action="navbar.php?action=add_menu" method="post" name="navi" id="navi">
                            <div class="form-group form-check">
                                <input type="checkbox" style="vertical-align:middle;" name="pages[0]" value="首页" class="ids form-check-input" id="checkbox-page-0" />
                                <label for="checkbox-page-0" class="form-check-label">首页</label>
                            </div>
                            <?php foreach ($pages as $key => $value) : ?>
                                <div class="form-group form-check">
                                    <input type="checkbox" style="vertical-align:middle;" name="pages[<?= $value['gid']; ?>]" value="<?= $value['title']; ?>" class="ids form-check-input" id="<?= 'checkbox-page-' . $value['gid']; ?>" />
                                    <label for="<?= 'checkbox-page-' . $value['gid']; ?>" class="form-check-label"><?= $value['title']; ?></label>
                                </div>
                            <?php endforeach; ?>
                            <input type="hidden" name="type" value="page">
                            <input type="hidden" name="menu" value="<?= $the_menu; ?>">
                            <input type="hidden" name="token" id="token" value="<?= LoginAuth::genToken() ?>">
                            <input type="submit" class="btn btn-sm btn-outline-success mt-2" name="" value="添加至菜单" />
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div data-toggle="collapse" data-target="#collapse-sort" aria-expanded="true" aria-controls="collapse-sort" class="h5 mb-0" role="button">分类</div>
                </div>
                <div id="collapse-sort" class="collapse" data-parent="#accordionAddMenu">
                    <div class="card-body">
                        <form action="navbar.php?action=add_menu" method="post" name="navi" id="navi">
                            <div class="form-group">
                                <?php
                                if ($sorts) :
                                    foreach ($sorts as $key => $value) :
                                        if ($value['pid'] != 0) {
                                            continue;
                                        }
                                ?>
                                        <div class="form-group form-check">
                                            <input type="checkbox" id="<?= 'checkbox-' . $value['sid']; ?>" class="form-check-input" style="vertical-align:middle;" name="sort_ids[]" value="<?= $value['sid']; ?>" class="ids" />
                                            <label for="<?= 'checkbox-' . $value['sid']; ?>" class="form-check-label"><?= $value['sortname']; ?></label>
                                        </div>
                                        <?php
                                        $children = $value['children'];
                                        foreach ($children as $key) :
                                            $value = $sorts[$key];
                                        ?>
                                            <div class="form-group form-check ml-4">
                                                <input type="checkbox" id="<?= 'checkbox-' . $value['sid']; ?>" class="form-check-input" style="vertical-align:middle;" name="sort_ids[]" value="<?= $value['sid']; ?>" class="ids" />
                                                <label for="<?= 'checkbox-' . $value['sid']; ?>" class="form-check-label"><?= $value['sortname']; ?></label>
                                            </div>
                                    <?php
                                        endforeach;
                                    endforeach;
                                    ?>
                                    <input type="hidden" name="type" value="sort">
                                    <input type="hidden" name="menu" value="<?= $the_menu; ?>">
                                    <input type="hidden" name="token" id="token" value="<?= LoginAuth::genToken() ?>">
                                    <input type="submit" name="" class="btn btn-sm btn-outline-success mt-2" value="添加至菜单" />

                                <?php else : ?>
                                    还没有分类，<a href="sort.php">新建分类</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div data-toggle="collapse" data-target="#collapse-diy" aria-expanded="true" aria-controls="collapse-diy" class="h5 mb-0" role="button">自定义链接</div>
                </div>
                <div id="collapse-diy" class="collapse" data-parent="#accordionAddMenu">
                    <div class="card-body">
                        <form action="navbar.php?action=add_menu" method="post" name="navi" id="navi">
                            <div class="form-group">
                                <input class="form-control" name="naviname" placeholder="导航名称" required />
                            </div>
                            <div class="form-group">
                                <textarea maxlength="512" class="form-control" placeholder="地址（URL）" name="url" id="url" rows="2" required /></textarea>
                            </div>
                            <div class="form-group">
                                <label>父导航</label>
                                <select name="pid" id="pid" class="form-control">
                                    <option value="0">无</option>
                                    <?php
                                    foreach ($navis as $key => $value) :
                                        if ($value['pid'] !== 0) {
                                            continue;
                                        }
                                    ?>
                                        <option value="<?= $value['id']; ?>"><?= $value['naviname']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" value="y" name="newtab" id="newtab">
                                <label class="form-check-label" for="newtab">在新窗口打开</label>
                            </div>
                            <input type="hidden" name="type" value="diy">
                            <input type="hidden" name="menu" value="<?= $the_menu; ?>">
                            <input type="hidden" name="token" id="token" value="<?= LoginAuth::genToken() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-success mt-2">添加至菜单</button>
                            <span id="alias_msg_hook"></span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-9">
        <h4 class="m-1">菜单结构</h4>
        <div class="card shadow mb-4 card-header-actions" style="overflow:visible">
            <form id="update-nav-menu" method="post" action="navbar.php?action=update">
                <div class="card-header" style="justify-content: flex-start;">
                    <h6 class="m-0 mr-2">菜单名称</h6>
                    <div><input class="form-control" type="text" name="menu-name" id="menu-name" value="<?= $menu_name; ?>" required></div>
                </div>
                <div class="card-body p-3">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="menu" id="menu" value="<?= $the_menu; ?>">
                    <input type="hidden" name="token" id="token" value="<?= LoginAuth::genToken() ?>">

                    <?php
                    if ($menu == '0' || count($menus) <= 1) {
                        echo '<p class="post-body-plain" id="menu-name-desc">给菜单命名，然后点击“创建菜单”。</p>';
                    } else {
                    ?>
                        <?php if (count($navis) >= 1 && isset($navis)) : ?>
                            <div class="accordion p-0" id="accordionMenuEdit">
                                <?php foreach ($navis as $key => $value) :
                                    $depth = 0;
                                    if ($value['pid'] !== 0) {
                                        $depth = 1;
                                    }
                                    $value['type_name'] = '';
                                    switch ($value['type']) {
                                        case Navi_Model::navitype_home:
                                        case Navi_Model::navitype_t:
                                        case Navi_Model::navitype_admin:
                                            $value['type_name'] = '系统';
                                            $value['url'] = '/' . $value['url'];
                                            break;
                                        case Navi_Model::navitype_sort:
                                            $value['type_name'] = '<span class="text-primary">分类</span>';
                                            break;
                                        case Navi_Model::navitype_page:
                                            $value['type_name'] = '<span class="text-success">页面</span>';
                                            break;
                                        case Navi_Model::navitype_custom:
                                            $value['type_name'] = '<span class="text-danger">自定</span>';
                                            break;
                                    } ?>
                                    <div class="menu-item-depth-<?= $depth; ?> menu-item">
                                        <div class="card mb-3 p-0 col-xl-4">
                                            <div class="card-header" style="justify-content:space-between">
                                                <div data-toggle="collapse" data-target="#collapse-<?= $value['id']; ?>" aria-expanded="true" aria-controls="collapse-<?= $value['id']; ?>">
                                                    <?= $value['naviname']; ?> <small><?= $value['pid'] !== 0 ? '子项目' : ''; ?></small>
                                                </div>
                                                <div class="btn btn-sm"><?= $value['type_name'] ?></div>
                                            </div>
                                            <div id="collapse-<?= $value['id']; ?>" class="collapse" data-parent="#accordionMenuEdit">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="naviname">导航名称</label>
                                                        <input class="form-control menu_naviname" id="naviname" value="<?= $value['naviname']; ?>" name="menus[<?= $value['id']; ?>][naviname]">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="url">导航地址</label>
                                                        <input class="form-control menu_url" id="url" value="<?= $value['url']; ?>" name="menus[<?= $value['id']; ?>][url]" <?= $value['type'] !== 0 ? 'disabled' : ''; ?>>
                                                    </div>
                                                    <div class="form-group form-check mb-3">
                                                        <input class="form-check-input menu_newtab" type="checkbox" value="y" id="newtab-<?= $value['id']; ?>" name="menus[<?= $value['id']; ?>][newtab]" <?= $value['newtab'] == 'y' ? 'checked' : ''; ?> />
                                                        <label class="form-check-label" for="newtab-<?= $value['id']; ?>">在新窗口打开</label>
                                                    </div>

                                                    <input type="hidden" class="menu_id" value="<?= $value['id']; ?>" name="menus[<?= $value['id']; ?>][id]" />
                                                    <input type="hidden" class="menu_type" value="<?= $value['type']; ?>" name="menus[<?= $value['id']; ?>][type]" />
                                                    <input type="hidden" class="menu_type_id" value="<?= $value['type_id']; ?>" name="menus[<?= $value['id']; ?>][type_id]" />
                                                    <input type="hidden" class="menu_pid" value="<?= $value['pid']; ?>" name="menus[<?= $value['id']; ?>][pid]" />
                                                    <input type="hidden" class="menu_isdefault" value="<?= $value['isdefault']; ?>" name="menus[<?= $value['id']; ?>][isdefault]" />
                                                    <input type="button" value="取消" class="btn btn-sm btn-secondary" data-toggle="collapse" data-target="#collapse-<?= $value['id']; ?>" aria-expanded="true" aria-controls="collapse-<?= $value['id']; ?>" />
                                                    <a href="javascript:;" class="btn btn-danger btn-sm removeMenu">移除</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $depth++; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <p>从左边栏中添加菜单项目。</p>
                        <?php endif; ?>
                    <?php } ?>
                    <hr>
                    <div class="menu-settings">
                        <div class="menu-settings-group menu-theme-locations">
                            <h4 class="menu-settings-group-name howto">显示位置</h4>
                            <?php
                            foreach ($_em_registered_nav_menus as $key => $value) : ?>
                                <div class="menu-settings-input form-check">
                                    <input class="form-check-input" type="checkbox" name="menu-locations[<?= $key; ?>]" id="locations-<?= $key; ?>" value="<?= $the_menu; ?>" <?= ($key == $use || (isset($locations[$key]) && $locations[$key] == $the_menu)) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="locations-<?= $key; ?>"><?= $value; ?></label> <small><?= isset($locations[$key]) && $locations[$key] !== '0' && $locations[$key] !== $the_menu ? '（当前设置为：' . $menus[$locations[$key]] . '）' : ''; ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer position-sticky" style="bottom: 0;">
                    <button class="btn btn-success">
                        <?= ($menu == '0' || count($menus) <= 1) ? '创建菜单' : '保存菜单'; ?>
                    </button>
                    <?php if (count($menus) >= 2) : ?>
                        <span><a href="<?= $menu == '0' ? 'navbar.php' : "javascript:del_confirm('{$the_menu}','menus','" . LoginAuth::genToken() . "')"; ?>" class="btn btn-outline-danger"><?= $menu == '0' ? '取消' : '删除菜单'; ?></a></span>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    setTimeout(hideActived, 2600);
    $("#menu_category_view").addClass('active');
    $("#menu_view").addClass('show');
    $("#menu_navi").addClass('active');

    function del_confirm(menu, property, token) {
        switch (property) {
            case 'menus':
                var urlreturn = "navbar.php?action=del&menu=" + menu;
                var msg = "您将永久删除这一菜单。\n点击\"取消\"停止，点击\"确定\"删除。";
                break;
            case 'menu':
                var urlreturn = "navbar.php?action=del&id=" + id;
                var msg = "确定要删除该评论吗？";
                break;
        }
        if (confirm(msg)) {
            window.location = urlreturn + "&token=" + token;
        } else {
            return;
        }
    }

    $(function() {
        $("#accordionMenuEdit").sortable({
            cursor: "move",
            placeholder: "sortable-placeholder p-0 col-xl-4",
            stop: function(event, ui) {
                var depth_data = $(".menu-item").first()[0].className.match(/menu-item-depth-(\d+)/g);

                if (depth_data == null) {
                    $(".menu-item").first().addClass('menu-item-depth-0').find(".menu_pid").val('0');
                }

                for (i = 0; i < depth_data.length; i++) {
                    if (depth_data[i] !== 'menu-item-depth-0') {
                        $(".menu-item").first().removeClass(depth_data[i]);
                    }
                }

                var menu_item = $(this).find(".menu-item");
                for (i = 0; i < menu_item.length; i++) {
                    var menu_pid = $(menu_item[i]).find(".menu_pid").val();
                    var depth = $(menu_item[i])[0].className.match(/menu-item-depth-(\d+)/g);
                    console.log($(menu_item[i]).find(".menu_naviname").val() + ":" + depth);
                    if (depth == 'menu-item-depth-0') {
                        $(menu_item[i]).find(".menu_pid").val('0')
                    } else if (depth == 'menu-item-depth-1') {
                        var prev_menu = $(menu_item[i]).prev(".menu-item");
                        var prev_pid = $(prev_menu).find(".menu_pid").val();
                        if (prev_pid == 0) {
                            $(menu_item[i]).find(".menu_pid").val($(prev_menu).find(".menu_id").val())
                        } else {
                            $(menu_item[i]).find(".menu_pid").val($(prev_menu).find(".menu_pid").val())
                        }
                    }
                }
            },
            sort: function(event, ui) {
                var original = ui.originalPosition;
                var position = ui.position;

                $(ui.placeholder).css('height', $(ui.helper).height())

                prev_menu = ui.placeholder.prev(".menu-item");
                next_menu = ui.placeholder.next(".menu-item");

                if (prev_menu[0] == ui.item[0]) {
                    prev_menu = prev_menu.prev(".menu-item");
                }

                if (next_menu[0] == ui.item[0]) {
                    next_menu = next_menu.next(".menu-item");
                }

                if (position.left - original.left > 50) {

                    if (prev_menu.length > 0) {
                        $(ui.placeholder).removeClass('menu-item-depth-0').addClass('menu-item-depth-1')
                        $(ui.helper).removeClass('menu-item-depth-0').addClass('menu-item-depth-1')
                        $(ui.item).find('small').text('子项目');

                        prev_pid = parseInt($(prev_menu).find('.menu_pid').val());

                        if (prev_pid == 0) {
                            $(ui.item).find('.menu_pid').val($(prev_menu).find('.menu_id').val())
                        } else {
                            $(ui.item).find('.menu_pid').val(prev_pid)
                        }
                    } else {
                        $(ui.placeholder).removeClass('menu-item-depth-1')
                        $(ui.helper).removeClass('menu-item-depth-1')
                        $(ui.item).find('.menu_pid').val('0');
                        $(ui.item).find('small').text('');
                    }
                } else {
                    $(ui.placeholder).removeClass('menu-item-depth-1')
                    $(ui.helper).removeClass('menu-item-depth-1')
                    $(ui.item).find('.menu_pid').val('0');
                    $(ui.item).find('small').text('');
                }

            }
        });



        $(".removeMenu").click(function() {
            $(this).parent().parent().parent().parent().remove();
            if ($("#accordionMenuEdit").find("div.ui-sortable-handle").length == 0) {
                $("#accordionMenuEdit").parent().prepend("<p>从左边栏中添加菜单项目。</p>");
                $("#accordionMenuEdit").remove();
            }
        })

    })
</script>


<style>
    .sortable-placeholder {
        height: 3.5625rem;
        min-width: 0;
        border-radius: 0.35rem;
        border: 1px dashed #c3c4c7;
        margin-bottom: 20px
    }

    .col-form-label {
        padding-top: calc(0.875rem + 1px);
        padding-bottom: calc(0.875rem + 1px);
        margin-bottom: 0;
        font-size: inherit;
        line-height: 1;
    }

    .col-form-label-lg {
        padding-top: calc(1.125rem + 1px);
        padding-bottom: calc(1.125rem + 1px);
        font-size: 1rem;
    }

    .col-form-label-sm {
        padding-top: calc(0.5rem + 1px);
        padding-bottom: calc(0.5rem + 1px);
        font-size: 0.75rem;
    }

    .form-text {
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #a7aeb8;
    }

    .form-control,
    .dataTable-input {
        display: block;
        width: 100%;
        /* padding: 0.875rem 1.125rem; */
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #c5ccd6;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {

        .form-control,
        .dataTable-input {
            transition: none;
        }
    }

    .form-control[type=file],
    [type=file].dataTable-input {
        overflow: hidden;
    }

    .form-control[type=file]:not(:disabled):not([readonly]),
    [type=file].dataTable-input:not(:disabled):not([readonly]) {
        cursor: pointer;
    }

    .form-control:focus,
    .dataTable-input:focus {
        color: #69707a;
        background-color: #fff;
        border-color: transparent;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(0, 97, 242, 0.25);
    }

    .form-control::-webkit-date-and-time-value,
    .dataTable-input::-webkit-date-and-time-value {
        height: 1em;
    }

    .form-control::-moz-placeholder,
    .dataTable-input::-moz-placeholder {
        color: #a7aeb8;
        opacity: 1;
    }

    .form-control:-ms-input-placeholder,
    .dataTable-input:-ms-input-placeholder {
        color: #a7aeb8;
        opacity: 1;
    }

    .form-control::placeholder,
    .dataTable-input::placeholder {
        color: #a7aeb8;
        opacity: 1;
    }

    .form-control:disabled,
    .dataTable-input:disabled,
    .form-control[readonly],
    [readonly].dataTable-input {
        background-color: #e0e5ec;
        opacity: 1;
    }

    .form-control::-webkit-file-upload-button,
    .dataTable-input::-webkit-file-upload-button {
        padding: 0.875rem 1.125rem;
        margin: -0.875rem -1.125rem;
        -webkit-margin-end: 1.125rem;
        margin-inline-end: 1.125rem;
        color: #69707a;
        background-color: #fff;
        pointer-events: none;
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border-inline-end-width: 1px;
        border-radius: 0;
        -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .form-control::file-selector-button,
    .dataTable-input::file-selector-button {
        padding: 0.875rem 1.125rem;
        margin: -0.875rem -1.125rem;
        -webkit-margin-end: 1.125rem;
        margin-inline-end: 1.125rem;
        color: #69707a;
        background-color: #fff;
        pointer-events: none;
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border-inline-end-width: 1px;
        border-radius: 0;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {

        .form-control::-webkit-file-upload-button,
        .dataTable-input::-webkit-file-upload-button {
            -webkit-transition: none;
            transition: none;
        }

        .form-control::file-selector-button,
        .dataTable-input::file-selector-button {
            transition: none;
        }
    }

    .form-control:hover:not(:disabled):not([readonly])::-webkit-file-upload-button,
    .dataTable-input:hover:not(:disabled):not([readonly])::-webkit-file-upload-button {
        background-color: #f2f2f2;
    }

    .form-control:hover:not(:disabled):not([readonly])::file-selector-button,
    .dataTable-input:hover:not(:disabled):not([readonly])::file-selector-button {
        background-color: #f2f2f2;
    }

    .form-control::-webkit-file-upload-button,
    .dataTable-input::-webkit-file-upload-button {
        padding: 0.875rem 1.125rem;
        margin: -0.875rem -1.125rem;
        -webkit-margin-end: 1.125rem;
        margin-inline-end: 1.125rem;
        color: #69707a;
        background-color: #fff;
        pointer-events: none;
        border-color: inherit;
        border-style: solid;
        border-width: 0;
        border-inline-end-width: 1px;
        border-radius: 0;
        -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {

        .form-control::-webkit-file-upload-button,
        .dataTable-input::-webkit-file-upload-button {
            -webkit-transition: none;
            transition: none;
        }
    }

    .form-control:hover:not(:disabled):not([readonly])::-webkit-file-upload-button,
    .dataTable-input:hover:not(:disabled):not([readonly])::-webkit-file-upload-button {
        background-color: #f2f2f2;
    }

    .form-control-plaintext {
        display: block;
        width: 100%;
        padding: 0.875rem 0;
        margin-bottom: 0;
        line-height: 1;
        color: #69707a;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
    }

    .form-control-plaintext.form-control-sm,
    .form-control-plaintext.form-control-lg {
        padding-right: 0;
        padding-left: 0;
    }

    .form-control-sm {
        min-height: calc(1em + 1rem + 2px);
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.25rem;
    }

    .form-control-sm::-webkit-file-upload-button {
        padding: 0.5rem 0.75rem;
        margin: -0.5rem -0.75rem;
        -webkit-margin-end: 0.75rem;
        margin-inline-end: 0.75rem;
    }

    .form-control-sm::file-selector-button {
        padding: 0.5rem 0.75rem;
        margin: -0.5rem -0.75rem;
        -webkit-margin-end: 0.75rem;
        margin-inline-end: 0.75rem;
    }

    .form-control-sm::-webkit-file-upload-button {
        padding: 0.5rem 0.75rem;
        margin: -0.5rem -0.75rem;
        -webkit-margin-end: 0.75rem;
        margin-inline-end: 0.75rem;
    }

    .form-control-lg {
        min-height: calc(1em + 2.25rem + 2px);
        padding: 1.125rem 1.5rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }

    .form-control-lg::-webkit-file-upload-button {
        padding: 1.125rem 1.5rem;
        margin: -1.125rem -1.5rem;
        -webkit-margin-end: 1.5rem;
        margin-inline-end: 1.5rem;
    }

    .form-control-lg::file-selector-button {
        padding: 1.125rem 1.5rem;
        margin: -1.125rem -1.5rem;
        -webkit-margin-end: 1.5rem;
        margin-inline-end: 1.5rem;
    }

    .form-control-lg::-webkit-file-upload-button {
        padding: 1.125rem 1.5rem;
        margin: -1.125rem -1.5rem;
        -webkit-margin-end: 1.5rem;
        margin-inline-end: 1.5rem;
    }

    textarea.form-control,
    textarea.dataTable-input {
        min-height: calc(1em + 1.75rem + 2px);
    }

    textarea.form-control-sm {
        min-height: calc(1em + 1rem + 2px);
    }

    textarea.form-control-lg {
        min-height: calc(1em + 2.25rem + 2px);
    }

    .form-control-color {
        width: 3rem;
        height: auto;
        padding: 0.875rem;
    }

    .form-control-color:not(:disabled):not([readonly]) {
        cursor: pointer;
    }

    .form-control-color::-moz-color-swatch {
        height: 1em;
        border-radius: 0.35rem;
    }

    .form-control-color::-webkit-color-swatch {
        height: 1em;
        border-radius: 0.35rem;
    }

    .form-select,
    .dataTable-selector {
        display: block;
        width: 100%;
        padding: 0.875rem 3.375rem 0.875rem 1.125rem;
        -moz-padding-start: calc(1.125rem - 3px);
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23363d47' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1.125rem center;
        background-size: 16px 12px;
        border: 1px solid #c5ccd6;
        border-radius: 0.35rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    @media (prefers-reduced-motion: reduce) {

        .form-select,
        .dataTable-selector {
            transition: none;
        }
    }

    .form-select:focus,
    .dataTable-selector:focus {
        border-color: transparent;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(0, 97, 242, 0.25);
    }

    .form-select[multiple],
    [multiple].dataTable-selector,
    .form-select[size]:not([size="1"]),
    [size].dataTable-selector:not([size="1"]) {
        padding-right: 1.125rem;
        background-image: none;
    }

    .form-select:disabled,
    .dataTable-selector:disabled {
        background-color: #e0e5ec;
    }

    .form-select:-moz-focusring,
    .dataTable-selector:-moz-focusring {
        color: transparent;
        text-shadow: 0 0 0 #69707a;
    }

    .form-select-sm {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        padding-left: 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.25rem;
    }

    .form-select-lg {
        padding-top: 1.125rem;
        padding-bottom: 1.125rem;
        padding-left: 1.5rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }

    .form-check {
        display: block;
        min-height: 1.5rem;
        padding-left: 1.5em;
        margin-bottom: 0.125rem;
    }

    .form-check .form-check-input {
        float: left;
        margin-left: -1.5em;
    }

    .form-check-input {
        width: 1em;
        height: 1em;
        margin-top: 0.25em;
        vertical-align: top;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid rgba(0, 0, 0, 0.25);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .form-check-input[type=checkbox] {
        border-radius: 0.25em;
    }

    .form-check-input[type=radio] {
        border-radius: 50%;
    }

    .form-check-input:active {
        filter: brightness(90%);
    }

    .form-check-input:focus {
        border-color: transparent;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(0, 97, 242, 0.25);
    }

    .form-check-input:checked {
        background-color: #0061f2;
        border-color: #0061f2;
    }

    .form-check-input:checked[type=checkbox] {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10l3 3l6-6'/%3e%3c/svg%3e");
    }

    .form-check-input:checked[type=radio] {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='2' fill='%23fff'/%3e%3c/svg%3e");
    }

    .form-check-input[type=checkbox]:indeterminate {
        background-color: #0061f2;
        border-color: #0061f2;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3e%3cpath fill='none' stroke='%23fff' stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M6 10h8'/%3e%3c/svg%3e");
    }

    .form-check-input:disabled {
        pointer-events: none;
        filter: none;
        opacity: 0.5;
    }

    .form-check-input[disabled]~.form-check-label,
    .form-check-input:disabled~.form-check-label {
        opacity: 0.5;
    }

    .form-switch {
        padding-left: 2.5em;
    }

    .form-switch .form-check-input {
        width: 2em;
        margin-left: -2.5em;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
        background-position: left center;
        border-radius: 2em;
        transition: background-position 0.15s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .form-switch .form-check-input {
            transition: none;
        }
    }

    .form-switch .form-check-input:focus {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='transparent'/%3e%3c/svg%3e");
    }

    .form-switch .form-check-input:checked {
        background-position: right center;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    .form-check-inline {
        display: inline-block;
        margin-right: 1rem;
    }

    .btn-check {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }

    .btn-check[disabled]+.btn,
    .btn-check:disabled+.btn {
        pointer-events: none;
        filter: none;
        opacity: 0.65;
    }

    .form-range {
        width: 100%;
        height: 1.5rem;
        padding: 0;
        background-color: transparent;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .form-range:focus {
        outline: 0;
    }

    .form-range:focus::-webkit-slider-thumb {
        box-shadow: 0 0 0 1px #f2f6fc, 0 0 0 0.25rem rgba(0, 97, 242, 0.25);
    }

    .form-range:focus::-moz-range-thumb {
        box-shadow: 0 0 0 1px #f2f6fc, 0 0 0 0.25rem rgba(0, 97, 242, 0.25);
    }

    .form-range::-moz-focus-outer {
        border: 0;
    }

    .form-range::-webkit-slider-thumb {
        width: 1rem;
        height: 1rem;
        margin-top: -0.25rem;
        background-color: #0061f2;
        border: 0;
        border-radius: 1rem;
        -webkit-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -webkit-appearance: none;
        appearance: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .form-range::-webkit-slider-thumb {
            -webkit-transition: none;
            transition: none;
        }
    }

    .form-range::-webkit-slider-thumb:active {
        background-color: #b3d0fb;
    }

    .form-range::-webkit-slider-runnable-track {
        width: 100%;
        height: 0.5rem;
        color: transparent;
        cursor: pointer;
        background-color: #d4dae3;
        border-color: transparent;
        border-radius: 1rem;
    }

    .form-range::-moz-range-thumb {
        width: 1rem;
        height: 1rem;
        background-color: #0061f2;
        border: 0;
        border-radius: 1rem;
        -moz-transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -moz-appearance: none;
        appearance: none;
    }

    @media (prefers-reduced-motion: reduce) {
        .form-range::-moz-range-thumb {
            -moz-transition: none;
            transition: none;
        }
    }

    .form-range::-moz-range-thumb:active {
        background-color: #b3d0fb;
    }

    .form-range::-moz-range-track {
        width: 100%;
        height: 0.5rem;
        color: transparent;
        cursor: pointer;
        background-color: #d4dae3;
        border-color: transparent;
        border-radius: 1rem;
    }

    .form-range:disabled {
        pointer-events: none;
    }

    .form-range:disabled::-webkit-slider-thumb {
        background-color: #a7aeb8;
    }

    .form-range:disabled::-moz-range-thumb {
        background-color: #a7aeb8;
    }

    .form-floating {
        position: relative;
    }

    .form-floating>.form-control,
    .form-floating>.dataTable-input,
    .form-floating>.form-select,
    .form-floating>.dataTable-selector {
        height: calc(3.5rem + 2px);
        line-height: 1.25;
    }

    .form-floating>label {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        padding: 1rem 1.125rem;
        pointer-events: none;
        border: 1px solid transparent;
        transform-origin: 0 0;
        transition: opacity 0.1s ease-in-out, transform 0.1s ease-in-out;
    }

    @media (prefers-reduced-motion: reduce) {
        .form-floating>label {
            transition: none;
        }
    }

    .form-floating>.form-control,
    .form-floating>.dataTable-input {
        padding: 1rem 1.125rem;
    }

    .form-floating>.form-control::-moz-placeholder,
    .form-floating>.dataTable-input::-moz-placeholder {
        color: transparent;
    }

    .form-floating>.form-control:-ms-input-placeholder,
    .form-floating>.dataTable-input:-ms-input-placeholder {
        color: transparent;
    }

    .form-floating>.form-control::placeholder,
    .form-floating>.dataTable-input::placeholder {
        color: transparent;
    }

    .form-floating>.form-control:not(:-moz-placeholder-shown),
    .form-floating>.dataTable-input:not(:-moz-placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    .form-floating>.form-control:not(:-ms-input-placeholder),
    .form-floating>.dataTable-input:not(:-ms-input-placeholder) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    .form-floating>.form-control:focus,
    .form-floating>.dataTable-input:focus,
    .form-floating>.form-control:not(:placeholder-shown),
    .form-floating>.dataTable-input:not(:placeholder-shown) {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    .form-floating>.form-control:-webkit-autofill,
    .form-floating>.dataTable-input:-webkit-autofill {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    .form-floating>.form-select,
    .form-floating>.dataTable-selector {
        padding-top: 1.625rem;
        padding-bottom: 0.625rem;
    }

    .form-floating>.form-control:not(:-moz-placeholder-shown)~label,
    .form-floating>.dataTable-input:not(:-moz-placeholder-shown)~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .form-floating>.form-control:not(:-ms-input-placeholder)~label,
    .form-floating>.dataTable-input:not(:-ms-input-placeholder)~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .form-floating>.form-control:focus~label,
    .form-floating>.dataTable-input:focus~label,
    .form-floating>.form-control:not(:placeholder-shown)~label,
    .form-floating>.dataTable-input:not(:placeholder-shown)~label,
    .form-floating>.form-select~label,
    .form-floating>.dataTable-selector~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .form-floating>.form-control:-webkit-autofill~label,
    .form-floating>.dataTable-input:-webkit-autofill~label {
        opacity: 0.65;
        transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
    }

    .input-group {
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
        width: 100%;
    }

    .input-group>.form-control,
    .input-group>.dataTable-input,
    .input-group>.form-select,
    .input-group>.dataTable-selector {
        position: relative;
        flex: 1 1 auto;
        width: 1%;
        min-width: 0;
    }

    .input-group>.form-control:focus,
    .input-group>.dataTable-input:focus,
    .input-group>.form-select:focus,
    .input-group>.dataTable-selector:focus {
        z-index: 3;
    }

    .input-group .btn {
        position: relative;
        z-index: 2;
    }

    .input-group .btn:focus {
        z-index: 3;
    }

    .input-group-text {
        display: flex;
        align-items: center;
        padding: 0.875rem 1.125rem;
        font-size: 0.875rem;
        font-weight: 400;
        line-height: 1;
        color: #69707a;
        text-align: center;
        white-space: nowrap;
        background-color: #fff;
        border: 1px solid #c5ccd6;
        border-radius: 0.35rem;
    }

    .input-group-lg>.form-control,
    .input-group-lg>.dataTable-input,
    .input-group-lg>.form-select,
    .input-group-lg>.dataTable-selector,
    .input-group-lg>.input-group-text,
    .input-group-lg>.btn {
        padding: 1.125rem 1.5rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }

    .input-group-sm>.form-control,
    .input-group-sm>.dataTable-input,
    .input-group-sm>.form-select,
    .input-group-sm>.dataTable-selector,
    .input-group-sm>.input-group-text,
    .input-group-sm>.btn {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
        border-radius: 0.25rem;
    }

    .input-group-lg>.form-select,
    .input-group-lg>.dataTable-selector,
    .input-group-sm>.form-select,
    .input-group-sm>.dataTable-selector {
        padding-right: 4.5rem;
    }

    .input-group:not(.has-validation)> :not(:last-child):not(.dropdown-toggle):not(.dropdown-menu),
    .input-group:not(.has-validation)>.dropdown-toggle:nth-last-child(n+3) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group.has-validation> :nth-last-child(n+3):not(.dropdown-toggle):not(.dropdown-menu),
    .input-group.has-validation>.dropdown-toggle:nth-last-child(n+4) {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }

    .input-group> :not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
        margin-left: -1px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
    }

    .card-header-actions .card-header {
        height: 3.5625rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-top: 0.5625rem;
        padding-bottom: 0.5625rem;
    }

    .card-header-actions .card-header .dropdown-menu {
        margin-top: 0;
        top: 0.5625rem !important;
    }

    .menu-item-depth-0 {
        margin-left: 0
    }

    .menu-item-depth-1 {
        margin-left: 30px
    }

    .menu-item-depth-2 {
        margin-left: 60px
    }

    .menu-item-depth-3 {
        margin-left: 90px
    }

    .menu-item-depth-4 {
        margin-left: 120px
    }

    .menu-item-depth-5 {
        margin-left: 150px
    }

    .menu-item-depth-6 {
        margin-left: 180px
    }

    .menu-item-depth-7 {
        margin-left: 210px
    }

    .menu-item-depth-8 {
        margin-left: 240px
    }

    .menu-item-depth-9 {
        margin-left: 270px
    }

    .menu-item-depth-10 {
        margin-left: 300px
    }

    .menu-item-depth-11 {
        margin-left: 330px
    }
</style>