const PluginManager = window.PluginManager;
PluginManager.register('CustomerGroupSearchPlugin', () => import('./ZenitPlatformGravityChild/customer-group/index.js'), '[data-customer-group-plugin]');
