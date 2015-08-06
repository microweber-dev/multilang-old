<?php if (get_option('is_multilang', 'website')): ?>
    <?php $display = (isset($params['display'])) ? ($params['display']) : false; ?>
    <?php
    $class = '';

    if ($display=='small'){
        $class = 'mw-ui-field-small';
    }

    $data = multilang_locales();

    $module_template = get_option('data-template', $params['id']);

    if ($module_template==false and isset($params['template'])){
        $module_template = $params['template'];
    }
    if ($module_template!=false){
        $template_file = module_templates($config['module'], $module_template);
    } else {
        $template_file = module_templates($config['module'], 'default');
    }
 
    if (isset($template_file) and is_file($template_file)!=false){
        include($template_file);
    }

    ?>
     
<?php else: ?>
    <?php if (in_live_edit()): ?>
        <span>(Open module settings to enable multi language support)</span>
    <?php endif; ?>
<?php endif; ?>
