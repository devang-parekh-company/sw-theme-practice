// src/Resources/app/administration/src/module/license-configuration/index.js
import './page/license-configuration-list';
import './page/license-configuration-detail';
import './page/license-configuration-create';

Shopware.Module.register('license-configuration', {
    type: 'plugin',
    name: 'license-configuration',
    title: 'license-configuration.general.mainMenuItemGeneral',
    description: 'license-configuration.general.descriptionTextModule',
    color: '#ff3d58',
    icon: 'default-object-lab-flask',
    routePrefixPath: 'license-configuration',
    entity: 'school_licences_information',

    routes: {
        list: {
            component: 'license-configuration-list',
            path: 'list',
        },
        detail: {
            component: 'license-configuration-detail',
            path: 'detail/:id',
            meta: {
                parentPath: 'license.configuration.list'
            },
        },
        create: {
            component: 'license-configuration-create',
            path: 'create',
            meta: {
                parentPath: 'license.configuration.list'
            }
        }
    },

    navigation: [{
        label: 'License Configuration',
        color: '#ff3d58',
        path: 'license.configuration.list',
        icon: 'default-object-lab-flask',
        parent: 'sw-catalogue',
        position: 100,
    }],
});
