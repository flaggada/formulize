<?php$modversion['name'] = _IM_IMENU_NAME;$modversion['version'] = "3.1";$modversion['description'] = _IM_IMENU_DESC;$modversion['credits'] = "";$modversion['author'] = "<a href='http://www.luinithil.com'>luinithil</a> and Freeform Solutions";$modversion['help'] = "help.html";$modversion['license'] = "GPL see LICENSE";$modversion['official'] = 0;$modversion['image'] = "icon_imenu.gif";$modversion['dirname'] = "iMenu";$modversion['sqlfile']['mysql'] = "sql/mysql.sql";$modversion['tables'][0] = "imenu";$modversion['hasMain'] = 0;$modversion['blocks'][1]['file'] = "imenu.php";$modversion['blocks'][1]['name'] = _IM_IMENU_NAME;$modversion['blocks'][1]['description'] = "link menu";$modversion['blocks'][1]['show_func'] = "b_imenu_show";$modversion['blocks'][1]['edit_func'] = "b_imenu_edit";$modversion['blocks'][1]['options'] = "40";$modversion['blocks'][1]['template'] = 'imenu_block.html';$modversion['hasAdmin'] = 1;$modversion['adminindex'] = "admin/index.php";// Config$modversion['config'][0]['name'] = 'send_all_subs';$modversion['config'][0]['title'] = '_MI_IMENU_SAS';$modversion['config'][0]['description'] = '_MI_IMENU_SAS_DESC';$modversion['config'][0]['formtype'] = 'yesno';$modversion['config'][0]['valuetype'] = 'int';$modversion['config'][0]['default'] = 0;?>