// src/Resources/app/administration/src/module/license-configuration/page/license-configuration-list/index.js
import template from './license-configuration-list.html.twig';

Shopware.Component.register('license-configuration-list', {
    template,

    inject: ['repositoryFactory'],

    data() {
        return {
            isLoading: false,
            licenses: null,
            page: 1,
            limit: 10,
            total: 0,
            repository: null,
            criteria: null
        };
    },
    computed: {
        columns() {
            return this.getColumns();
        },
        assetFilter() {
            return Shopware.Filter.getByName('asset');
        },
    },

    created() {
        this.repository = this.repositoryFactory.create('school_licences_information');
        this.criteria = new Shopware.Data.Criteria(this.page, this.limit);
        this.criteria.addAssociation('customerGroup');
        this.criteria.addAssociation('category');
        this.criteria.addAssociation('product');

        this.fetchLicenses();
    },

    methods: {
        fetchLicenses() {
            this.isLoading = true;
            this.repository.search(this.criteria, this.context).then((result) => {
                this.licenses = result;
                this.total = result.total
                // console.log("licenses", this.licenses)
                this.isLoading = false;
            }).catch((error) => {
                this.isLoading = false;
                console.error('Error fetching licenses:', error);
            });
        },

        getColumns() {
            return [{
                property: 'customerGroup.name',
                dataIndex: 'customerGroupId',
                label: this.$tc('license-configuration.label.customerGroup'),
                allowResize: true,
            }, {
                property: 'category.name',
                dataIndex: 'categoryId',
                label: this.$tc('license-configuration.label.category'),
                allowResize: true,
            }, {
                property: 'product.name',
                dataIndex: 'productId',
                label: this.$tc('license-configuration.label.product'),
                allowResize: true,
            }];
        },

        updateTotal({total}) {
            this.total = total;
        },
    },
});
