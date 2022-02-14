<?php
/*
Plugin Name: 导航菜单增强
Version: v1.0
Plugin URL: https://www.emlog.net/plugin/detail/438
Description: emlog导航菜单增强插件，功能更强
Author: MaLaGeBe
Author Email:
Author URL: https://www.emlog.net/plugin/detail/438
ForEmlog: pro 1.2.0
*/
!defined('EMLOG_ROOT') && exit('access deined!');

$_em_registered_nav_menus = [];

doAction('reg_menus', $_em_registered_nav_menus, $result = []);

function register_nav_menus($locations = [], &$result = [])
{
    global $_em_registered_nav_menus;

    foreach ($locations as $key => $value) {
        if (is_int($key)) {
            emMsg(__FUNCTION__ . '导航菜单位置必须是字符串。');
            break;
        }
    }

    $_em_registered_nav_menus = array_merge((array) $_em_registered_nav_menus, $locations);
    $result = $_em_registered_nav_menus;
}


if ($_SERVER['PHP_SELF'] == '/admin/navbar.php' && !empty($_em_registered_nav_menus)) {

    loginAuth::checkLogin();

    $Navi_Model = new Navi_Model();

    $nav_bar = Storage::getInstance('nav_bar');

    $action = isset($_GET['action']) ? addslashes($_GET['action']) : '';
    $menu = isset($_GET['menu']) ? addslashes($_GET['menu']) : '';
    $use = isset($_GET['use-location']) ? addslashes($_GET['use-location']) : '';

    if (empty($action) || $action == 'edit') {
        $emPage = new Log_Model();

        $menus = (array)$nav_bar->getValue('menus');
        $locations = (array)$nav_bar->getValue('menu_locations');
        $sorts = $CACHE->readCache('sort');
        $pages = $emPage->getAllPageList();

        $last_menu = array_key_last($menus);

        $menu_name = '';

        if ((empty($menu) && $last_menu == 0) || ($action == 'edit' && $menu == 0)) {
            $the_menu = 0;
            $menu_name = '';
        } elseif (empty($menu) && $last_menu > 0) {
            $the_menu = $last_menu;
            $menu_name = $menus[$the_menu];
        } else {
            $the_menu = $menu;
            $menu_name = $menus[$the_menu];
        }

        if ($menu > 0 && !array_key_exists($the_menu, $menus)) {
            emMsg('未找到该菜单', 'navbar.php');
        }

        $navis = getMenu($the_menu);

        require_once EMLOG_ROOT . '/admin/views/header.php';
        require_once('views/nav.php');
        require_once EMLOG_ROOT . '/admin/views/footer.php';
        View::output();
    }

    if ($action == 'locations') {
        $navis = (array)$nav_bar->getValue('menu_data');
        $menus = (array)$nav_bar->getValue('menus');
        $locations = (array)$nav_bar->getValue('menu_locations');

        require_once EMLOG_ROOT . '/admin/views/header.php';
        require_once('views/locations.php');
        require_once EMLOG_ROOT . '/admin/views/footer.php';
        View::output();
    }

    if ($action == 'update') {
        if (empty($_POST)) emDirect('navbar.php');

        $post = [];
        foreach ($_POST as $key => $value) {
            $post[$key] = $value;
        }

        if (isset($post['menu']) && $post['menu'] == 0) {
            LoginAuth::checkToken();
            $new_menu = $post['menu-name'];
            if ($new_menu == '') {
                emMsg('菜单名称不能为空！');
            }
            $menus = (array)$nav_bar->getValue('menus');
            $key = array_keys($menus);
            if (in_array($new_menu, $menus)) {
                emMsg("菜单名称“{$new_menu}”和另一菜单名称冲突，请另选一个名称。");
            }

            $menus[] = $new_menu;
            $nav_bar->updateValue('menus', $menus);
            $last_key = array_key_last($menus);

            if (isset($post['menu-locations'])) {
                $newlocations = (array)$nav_bar->getValue('menu_locations');
                $locations = $post['menu-locations'];
                foreach ($locations as $key => $value) {
                    $newlocations[$key] = $last_key;
                }

                $nav_bar->updateValue('menu_locations', $newlocations);
            }

            NavCache::getInstance()->updateCache('navis');

            emDirect('navbar.php?action=edit&menu=' . $last_key . '&add_success=1');
        }

        if (isset($post['menu']) && $post['menu'] > 0) {
            LoginAuth::checkToken();
            $new_menu = $post['menu-name'];
            if ($new_menu == '') {
                emMsg('菜单名称不能为空！');
            }

            $menus = (array)$nav_bar->getValue('menus');
            $old_menu = $menus[$post['menu']];

            $bac_menus = $menus;
            unset($bac_menus[$post['menu']]);

            if (in_array($new_menu, $bac_menus)) {
                emMsg("菜单名称“{$new_menu}”和另一菜单名称冲突，请另选一个名称。");
            } elseif ($new_menu !== $old_menu) {
                $menus[$post['menu']] = $new_menu;
                $nav_bar->updateValue('menus', $menus);
            }

            $menu_data = (array)$nav_bar->getValue('menu_data');

            if (isset($post['menus'])) {
                $i = 0;
                foreach ($post['menus'] as $key => $value) {
                    $menu_data[$post['menu']][$i]['naviname'] = $post['menus'][$key]['naviname'];
                    $menu_data[$post['menu']][$i]['url'] = isset($post['menus'][$key]['url']) ? $post['menus'][$key]['url'] : '';
                    $menu_data[$post['menu']][$i]['id'] = isset($post['menus'][$key]['id']) ? $post['menus'][$key]['id'] : $key + 1;
                    $menu_data[$post['menu']][$i]['pid'] = $post['menus'][$key]['pid'];
                    $menu_data[$post['menu']][$i]['isdefault'] = $post['menus'][$key]['isdefault'];
                    $menu_data[$post['menu']][$i]['newtab'] = isset($post['menus'][$key]['newtab']) ? $post['menus'][$key]['newtab'] : 'n';
                    $menu_data[$post['menu']][$i]['type'] = $post['menus'][$key]['type'];
                    $menu_data[$post['menu']][$i]['type_id'] = $post['menus'][$key]['type_id'];
                    $i++;
                }
            } else {
                $menu_data[$post['menu']] = [];
            }

            $nav_bar->updateValue('menu_data', $menu_data);

            if (isset($post['menu-locations'])) {
                $newlocations = (array)$nav_bar->getValue('menu_locations');
                $locations = $post['menu-locations'];
                foreach ($locations as $key => $value) {
                    $newlocations[$key] = $post['menu'];
                }

                $nav_bar->updateValue('menu_locations', $newlocations);
            }

            NavCache::getInstance()->updateCache('navis');

            emDirect('navbar.php?action=edit&menu=' . $post['menu'] . '&edit_success=1');
        }


        if (isset($post['nav-menu-locations'])) {
            LoginAuth::checkToken();
            $locations = $post['menu-locations'];
            $nav_bar->updateValue('menu_locations', $locations);

            NavCache::getInstance()->updateCache('navis');

            emDirect('navbar.php?action=locations&success=1');
        }
    }

    if ($action == 'add_menu') {
        if (isset($_POST['type'])) {
            LoginAuth::checkToken();

            $navis = (array)$nav_bar->getValue('menu_data');

            switch ($_POST['type']) {
                case 'page':
                    $pages = $_POST['pages'] ?? [];

                    if (empty($pages)) {
                        emDirect("./navbar.php?error_e=1");
                    }

                    foreach ($pages as $id => $title) {
                        $id = (int)$id;
                        $title = addslashes($title);
                        if ($id == 0) {
                            $navis[$_POST['menu']][] = array(
                                'naviname' => $title,
                                'url' => BLOG_URL,
                                'pid' => 0,
                                'isdefault' => 1,
                                'newtab' => 'n',
                                'type' => 3,
                                'type_id' => 0
                            );
                        } else {
                            $navis[$_POST['menu']][] = array(
                                'naviname' => $title,
                                'url' => '',
                                'isdefault' => 0,
                                'pid' => 0,
                                'newtab' => 'n',
                                'type' => Navi_Model::navitype_page,
                                'type_id' => $id
                            );
                        }

                        $nav_bar->updateValue('menu_data', $navis);
                    }

                    break;

                case 'sort':
                    $sort_ids = isset($_POST['sort_ids']) ? $_POST['sort_ids'] : [];

                    $sorts = $CACHE->readCache('sort');

                    if (empty($sort_ids)) {
                        emDirect("./navbar.php?error_d=1");
                    }

                    foreach ($sort_ids as $val) {
                        $sort_id = (int)$val;
                        $navis[$_POST['menu']][] = array(
                            'naviname' => addslashes($sorts[$sort_id]['sortname']),
                            'url' => '',
                            'pid' => 0,
                            'newtab' => 'n',
                            'type' => Navi_Model::navitype_sort,
                            'type_id' => $sort_id
                        );
                        $nav_bar->updateValue('menu_data', $navis);
                    }

                    break;

                case 'diy':
                    $naviname = isset($_POST['naviname']) ? addslashes(trim($_POST['naviname'])) : '';
                    $url = isset($_POST['url']) ? addslashes(trim($_POST['url'])) : '';
                    $pid = isset($_POST['pid']) ? (int)$_POST['pid'] : 0;
                    $newtab = isset($_POST['newtab']) ? addslashes(trim($_POST['newtab'])) : 'n';

                    if ($naviname == '' || $url == '') {
                        emDirect("./navbar.php?error_a=1");
                    }

                    if (!preg_match("/^(http|https|ftp):\/\/.*$/i", $url)) {
                        emDirect("./navbar.php?error_f=1");
                    }

                    $navis[$_POST['menu']][] = array(
                        'naviname' => $naviname,
                        'url' => $url,
                        'pid' => $pid,
                        'newtab' => $newtab,
                        'type' => 0,
                        'type_id' => 0
                    );
                    $nav_bar->updateValue('menu_data', $navis);
                    break;

                default:
                    # code...
                    break;
            }

            NavCache::getInstance()->updateCache('navis');

            emDirect('navbar.php?action=edit&menu=' . $_POST['menu']);
        }
    }

    if ($action == 'del') {
        LoginAuth::checkToken();
        $menus = (array)$nav_bar->getValue('menus');
        $menu_data = (array)$nav_bar->getValue('menu_data');
        $locations = (array)$nav_bar->getValue('menu_locations');

        unset($menus[$menu]);
        unset($menu_data[$menu]);

        foreach ($locations as $key => $value) {
            if ($value == $menu) {
                unset($locations[$key]);
            }
        }

        $nav_bar->updateValue('menus', $menus);
        $nav_bar->updateValue('menu_data', $menu_data);
        $nav_bar->updateValue('menu_locations', $locations);

        NavCache::getInstance()->updateCache('navis');

        emDirect('navbar.php?active_del=1');
        exit;
    }

    if ($action == 'update_cache') {
        NavCache::getInstance()->updateCache('navis');
        emDirect('navbar.php?update_cache=1');
        exit;
    }
}

function getMenu($menu = 0)
{
    $nav_bar = Storage::getInstance('nav_bar');
    $navis = [];
    $menu_data = (array)$nav_bar->getValue('menu_data');

    if (!isset($menu_data[$menu]) || count($menu_data) <= 1 || $menu == 0) return [];

    foreach ($menu_data[$menu] as $key => $value) {
        $url = Url::navi($value['type'], $value['type_id'], $value['url']);
        $id = isset($value['id']) ? $value['id'] : (int)$key + 1;
        $naviData = array(
            'id'        => $id,
            'naviname'  => htmlspecialchars(trim($value['naviname'])),
            'url'       => htmlspecialchars(trim($url)),
            'newtab'    => $value['newtab'],
            'isdefault' => isset($value['isdefault']) ? $value['isdefault'] : 0,
            'type'      => (int)$value['type'],
            'type_id'    => (int)$value['type_id'],
            'pid'       => (int)$value['pid'],
        );

        if ($naviData['pid'] == 0) {
            $naviData['childnavi'] = [];
        } elseif (isset($navis[$value['pid']])) {
            $navis[$value['pid']]['childnavi'][] = $naviData;
        }

        $navis[$id] = $naviData;
    }
    return $navis;
}

function getMenuByLoc($location)
{
    $menus = NavCache::getInstance()->readCache('navis');

    $navis = [];
    if (isset($menus['locations'][$location])) {
        $navis = $menus['navis'][$menus['locations'][$location]];
    }

    return $navis;
}


class NavCache extends Cache
{
    protected $navis_cache;

    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new NavCache();
        }
        return self::$instance;
    }

    function mc_navis()
    {
        $navis_cache = [];
        $nav_bar = Storage::getInstance('nav_bar');
        $navis = $nav_bar->getValue('menu_data');
        $locations = $nav_bar->getValue('menu_locations');

        foreach ($navis as $key => $value) {
            if (is_array($value)) {
                $navid = $key;
                foreach ($value as $key => $value) {
                    $url = Url::navi($value['type'], $value['type_id'], $value['url']);
                    $id = isset($value['id']) ? $value['id'] : (int)$key + 1;

                    $naviData = array(
                        'id'        => $id,
                        'naviname'  => htmlspecialchars(trim($value['naviname'])),
                        'url'       => htmlspecialchars(trim($url)),
                        'newtab'    => $value['newtab'],
                        'isdefault' => $value['isdefault'],
                        'type'      => (int)$value['type'],
                        'typeId'    => (int)$value['type_id'],
                        'pid'       => (int)$value['pid'],
                    );

                    if ($naviData['pid'] == 0) {
                        $naviData['childnavi'] = [];
                    } elseif (isset($navis_cache['navis'][$navid][$value['pid']])) {
                        $navis_cache['navis'][$navid][$value['pid']]['childnavi'][] = $naviData;
                    }

                    $navis_cache['navis'][$navid][$id] = $naviData;
                }
            }
        }
        $navis_cache['locations'] = $locations;
        $cacheData = serialize($navis_cache);
        $this->cacheWrite($cacheData, 'navis');
    }
}
