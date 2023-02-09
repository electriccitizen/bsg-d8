<?php

$databases['default']['default'] = array (
  'database' => 'default',
  'username' => 'user',
  'password' => 'user',
  'prefix' => '',
  'host' => 'db',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['hash_salt'] = 'P0ylJzp9vEJsp8rxb8lTYeubSIwT5k7mqZkMRCyicNTQitXTusi2CSdq0tUtRY-a3J_p91lAqQ';

$settings['update_free_access'] = FALSE;

$base_url = "http://bsg-d8.docksal.site";

$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

$settings['entity_update_batch_size'] = 50;

$settings['entity_update_backup'] = TRUE;

$settings['migrate_node_migrate_type_classic'] = FALSE;

$settings['config_sync_directory'] = 'sites/default/files/config_R4RO7RUzNQ-mOpAK9ulSW9-TbiptkZbJ4ant2GklQMBRIS0_FZDERjyEBH9HZfZ2DCuo7qDf9Q/sync';

$config['system.logging']['error_level'] = 'verbose';

$settings['rebuild_access'] = TRUE;

$settings['skip_permissions_hardening'] = TRUE;

# Trusted Host Settings
$settings['trusted_host_patterns'] = [
  '^bsg-d8\.docksal\.site$',
];

# file path settings
$settings['file_temporary_path'] = '/tmp';
$settings['file_public_path'] = 'sites/default/files';
$settings['file_private_path'] = 'sites/default/files/private';

# enables twig debugging per page load
$settings['cache']['bins']['render'] = 'cache.backend.null';
$settings['cache']['bins']['page'] = 'cache.backend.null';
$settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';